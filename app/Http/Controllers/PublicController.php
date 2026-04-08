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

        return view('checkout', compact('event', 'ticketType'));
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
                'Verified',
                (int) $request->quantity
            ]);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Update batch-category specific sold count in acara table
            $catKey = strtolower($ticketType->name); // regular, vip, vvip
            $batchNum = $ticketType->batch_number ?? 1;
            $column = "batch{$batchNum}_{$catKey}_sold";
            
            // Check if column exists before incrementing to avoid errors with unmapped types
            if (in_array($column, ['batch1_regular_sold', 'batch1_vip_sold', 'batch1_vvip_sold', 'batch2_regular_sold', 'batch2_vip_sold', 'batch2_vvip_sold'])) {
                DB::table('acara')
                    ->where('event_id', $event->event_id)
                    ->increment($column, (int) $request->quantity);
            }
            
            // Check if multiple tickets need to be created (if stored procedure didn't)
            $order = Order::where('user_id', $user->user_id)
                ->orderBy('payment_date', 'desc')
                ->first();
            
            if ($order) {
                $currentTicketCount = DB::table('ticket')->where('transaction_id', $order->transaction_id)->count();
                if ($currentTicketCount < $request->quantity) {
                    for ($i = $currentTicketCount; $i < $request->quantity; $i++) {
                        DB::table('ticket')->insert([
                            'ticket_id' => 'TIX-' . strtoupper(Str::random(10)),
                            'event_id' => $event->event_id,
                            'ticket_type_id' => $ticketType->id,
                            'ticket_status' => 'Active', // Public checkout sets status to Verified immediately
                            'transaction_id' => $order->transaction_id,
                            'qr_code' => ''
                        ]);
                    }
                }
            }

            DB::commit();

            // Refresh order with all tickets for email
            $latestOrder = Order::where('user_id', $user->user_id)
                ->with(['tickets.ticketType', 'tickets.event', 'event'])
                ->orderBy('payment_date', 'desc')
                ->first();

            if ($latestOrder) {
                try {
                    Mail::to($user->email)->send(new TicketMail($user, $latestOrder, $latestOrder->tickets));
                } catch (\Exception $mailEx) {
                    Log::error('Failed to send ticket email: ' . $mailEx->getMessage());
                    // We don't abort since the purchase IS successful in the DB
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil dibeli!',
                'redirect' => route('tickets.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Ensure FK checks are re-enabled even on failure
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            Log::error('Checkout Processing Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembelian: ' . $e->getMessage()
            ], 500);
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
