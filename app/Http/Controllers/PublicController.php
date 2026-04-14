<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Services\GoogleCalendarService;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // Auto-update overdue events before showing them
        Event::updateOverdueEvents();

        $banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $categories = \Illuminate\Support\Facades\DB::table('kategori_acara')->get();
        
        $query = Event::active()->orderBy('schedule_time', 'desc');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Ambil SEMUA event aktif (yg belum overdue & published)
        $events = $query->get();

        // Ambil tiket milik user (jika login)
        $myTickets = auth()->check() ? \App\Models\Ticket::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['ticketType.event', 'order'])->get() : collect();

        return view('user', compact('events', 'myTickets', 'categories', 'banners'));
    }

    public function showEvent(Event $event)
    {
        // Selalu gunakan template 'concert' tunggal
        return view('concert', compact('event'));
    }

    public function showTicket(Event $event)
    {
        // Load ticket types with the event
        $event->load('ticketTypes');
        return view('ticket', compact('event'));
    }

    public function showCheckout(Event $event, TicketType $ticketType)
    {
        // Pastikan ticketType milik event tersebut
        if ($ticketType->event_id !== $event->event_id) {
            abort(404);
        }

        // Auto-update overdue events and cleanup expired orders before showing checkout
        Event::updateOverdueEvents();

        // --- BATCH ACTIVITY CHECK ON PAGE LOAD ---
        $activeBatch = $event->active_batch;
        if (!$activeBatch) {
            return redirect()->route('public.ticket.show', $event->event_id)
                             ->with('error', 'Penjualan tiket belum dimulai.');
        }

        if ($ticketType->batch_number != $activeBatch) {
            return redirect()->route('public.ticket.show', $event->event_id)
                             ->with('error', 'Batch ini tidak lagi tersedia.');
        }
        // ----------------------------------------
        
        // Find if user already has a pending order for this event
        $pendingOrder = null;
        if (Auth::check()) {
            $query = Order::where('user_id', Auth::id())
                ->where('payment_status', 'Pending')
                ->where('expires_at', '>', now());
            
            if (request('order_id')) {
                $query->where('transaction_id', request('order_id'));
            } else {
                $ticketTypeIds = $event->ticketTypes->pluck('id')->toArray();
                $query->whereIn('ticket_id', $ticketTypeIds)->orderBy('expires_at', 'desc');
            }
            
            $pendingOrder = $query->first();
        }

        return view('checkout', compact('event', 'ticketType', 'pendingOrder'));
    }

    /**
     * Process checkout - create order via stored procedure
     */
    public function processCheckout(Request $request, Event $event, TicketType $ticketType)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:5',
            'payment_method' => 'required|in:QRIS,Virtual Account',
        ]);

        if ($ticketType->event_id !== $event->event_id) {
            return response()->json(['success' => false, 'message' => 'Invalid ticket type'], 400);
        }

        // --- BATCH ACTIVITY CHECK ---
        $activeBatch = $event->active_batch;
        if (!$activeBatch) {
            return response()->json(['success' => false, 'message' => 'Penjualan tiket belum dimulai.'], 403);
        }

        // Ensure user is buying the TICKET FROM THE ACTIVE BATCH
        if ($ticketType->batch_number != $activeBatch) {
            $msg = $activeBatch == 1 ? "Saat ini hanya Batch 1 yang tersedia." : "Penjualan Batch 1 sudah berakhir, silakan beli tiket Batch 2.";
            return response()->json(['success' => false, 'message' => $msg], 403);
        }
        // ----------------------------

        try {
            $user = Auth::user();
            DB::beginTransaction();
            // Disable foreign key checks to handle circular dependency created by the stored procedure
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::statement("CALL AddTransaction(?, ?, ?, ?, ?, ?)", [
                (string) Auth::id(),
                (string) $event->event_id,
                (int) $ticketType->id,
                (string) $request->payment_method,
                'Pending', // Set to pending initially
                (int) $request->quantity
            ]);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Find the order that was just created
            $order = Order::where('user_id', $user->user_id)
                ->orderBy('payment_date', 'desc') // procedute sets payment_date to current timestamp
                ->first();

            if ($order) {
                // Set the 15 minute expiration
                $order->update([
                    'expires_at' => now()->addMinutes(15)
                ]);

                // Ensure tickets are created but marked accordingly if needed
                $currentTicketCount = DB::table('ticket')->where('transaction_id', $order->transaction_id)->count();
                if ($currentTicketCount < $request->quantity) {
                    for ($i = $currentTicketCount; $i < $request->quantity; $i++) {
                        DB::table('ticket')->insert([
                            'ticket_id' => 'TIX-' . strtoupper(Str::random(10)),
                            'event_id' => $event->event_id,
                            'ticket_type_id' => $ticketType->id,
                            'ticket_status' => 'Pending', // Mark tickets as pending
                            'transaction_id' => $order->transaction_id,
                            'qr_code' => ''
                        ]);
                    }
                }
            }

            // Update batch-category specific sold count in acara table (Quota Reservation)
            $catKey = strtolower($ticketType->name); 
            $batchNum = $ticketType->batch_number ?? 1;
            $column = "batch{$batchNum}_{$catKey}_sold";
            
            if (in_array($column, ['batch1_regular_sold', 'batch1_vip_sold', 'batch1_vvip_sold', 'batch2_regular_sold', 'batch2_vip_sold', 'batch2_vvip_sold'])) {
                DB::table('acara')
                    ->where('event_id', $event->event_id)
                    ->increment($column, (int) $request->quantity);
            }

            // Also increment TicketType's quantity_sold
            $ticketType->increment('quantity_sold', (int) $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran dalam 15 menit.',
                'order_id' => $order->transaction_id,
                'expires_at' => $order->expires_at->toDateTimeString(),
                'total_amount' => $order->total_amount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            Log::error('Checkout Processing Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment for a pending order
     */
    public function confirmPayment(Request $request, $orderId)
    {
        try {
            DB::beginTransaction();
            $order = Order::where('transaction_id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($order->payment_status === 'Verified') {
                return response()->json(['success' => true, 'message' => 'Pembayaran sudah dikonfirmasi.']);
            }

            if ($order->isExpired()) {
                // If checking manually while it's expired, trigger cleanup
                Event::cleanupExpiredOrders();
                return response()->json(['success' => false, 'message' => 'Maaf, batas waktu pembayaran 15 menit telah habis.'], 400);
            }

            // Update status to Verified
            $order->update([
                'payment_status' => 'Verified',
                'payment_date' => now()
            ]);

            // Update tickets status
            \App\Models\Ticket::where('transaction_id', $orderId)->update([
                'ticket_status' => 'Active'
            ]);

            DB::commit();

            // Send Email
            try {
                $user = Auth::user();
                $orderWithTickets = $order->load(['tickets.ticketType', 'tickets.event', 'event']);
                Mail::to($user->email)->send(new TicketMail($user, $orderWithTickets, $orderWithTickets->tickets));
            } catch (\Exception $mailEx) {
                Log::error('Failed to send ticket email after payment: ' . $mailEx->getMessage());
            }

            // Generate Google Calendar link
            $calendarLink = null;
            try {
                $event = $order->event;
                if ($event) {
                    $ticketTypeName = $order->tickets->first()?->ticketType?->name;
                    $calendarLink = GoogleCalendarService::generateCalendarLink($event, $ticketTypeName);
                }
            } catch (\Exception $calEx) {
                Log::error('Failed to generate Google Calendar link: ' . $calEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran Anda telah berhasil dikonfirmasi!',
                'calendar_link' => $calendarLink
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengonfirmasi pembayaran.'], 500);
        }
    }
    /**
     * Join the waiting list for Batch 1 transitioning to Batch 2
     */
    public function joinWaitingList(Request $request, Event $event, TicketType $ticketType)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        if ($ticketType->event_id !== $event->event_id) {
            return response()->json(['success' => false, 'message' => 'Invalid ticket type'], 400);
        }

        // Only allow waiting list for Batch 1
        if ($ticketType->batch_number != 1) {
            return response()->json(['success' => false, 'message' => 'Waiting list hanya tersedia untuk Batch 1.'], 403);
        }

        // Check if Batch 1 is actually sold out for this category
        $catKey = strtolower($ticketType->name);
        $soldColumn = "batch1_{$catKey}_sold";
        $quotaColumn = "batch1_{$catKey}_quota";

        if ($event->$soldColumn < $event->$quotaColumn) {
            return response()->json(['success' => false, 'message' => 'Tiket Batch 1 masih tersedia.'], 400);
        }

        // Check waiting list dates
        $now = now();
        if ($event->batch1_waiting_start_at && $now->isBefore($event->batch1_waiting_start_at)) {
            return response()->json(['success' => false, 'message' => 'Waiting list belum dimulai.'], 403);
        }
        if ($event->batch1_waiting_ended_at && $now->isAfter($event->batch1_waiting_ended_at)) {
            return response()->json(['success' => false, 'message' => 'Waiting list sudah berakhir.'], 403);
        }

        // Check waiting list quota
        $wlSoldColumn = "batch1_{$catKey}_waiting_sold";
        $wlQuotaColumn = "batch1_{$catKey}_waiting_quota";

        if ($event->$wlQuotaColumn <= 0) {
            return response()->json(['success' => false, 'message' => 'Waiting list tidak tersedia untuk kategori ini.'], 403);
        }

        if ($event->$wlSoldColumn >= $event->$wlQuotaColumn) {
            return response()->json(['success' => false, 'message' => 'Kuota waiting list sudah penuh.'], 403);
        }

        // Check if already in waiting list
        $exists = DB::table('waiting_list')
            ->where('event_id', $event->event_id)
            ->where('ticket_type_id', $ticketType->id)
            ->where('user_email', Auth::user()->email)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Anda sudah terdaftar di waiting list ini.'], 400);
        }

        try {
            DB::beginTransaction();

            // Insert into waiting_list table
            DB::table('waiting_list')->insert([
                'event_id' => $event->event_id,
                'ticket_type_id' => $ticketType->id,
                'user_email' => Auth::user()->email,
                'notified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update counts
            DB::table('acara')
                ->where('event_id', $event->event_id)
                ->increment($wlSoldColumn);

            DB::table('ticket_type')
                ->where('id', $ticketType->id)
                ->increment('waiting_list_sold');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil bergabung dengan waiting list! Anda akan mendapatkan prioritas tiket Batch 2 dengan harga Batch 2 saat dimulai.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Waiting List Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal bergabung dengan waiting list: ' . $e->getMessage()], 500);
        }
    }
}
