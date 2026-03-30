<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        try {
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
            DB::commit();

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
}
