<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;

class OrderController extends Controller
{
    public function create(Event $event)
    {
        $ticketTypes = DB::table('ticket_type')
            ->where('event_id', $event->event_id)
            ->get();

        return view('orders.create', compact('event', 'ticketTypes'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_type_id' => ['required', 'integer'],
            'payment_method' => ['required', 'in:Virtual Account,QRIS'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        try {
            DB::statement("CALL AddTransaction(?, ?, ?, ?, ?, ?)", [
                Auth::user()->user_id,
                $event->event_id,
                $validated['ticket_type_id'],
                $validated['payment_method'],
                'Pending', 
                $validated['quantity']
            ]);

            $order = Order::where('user_id', Auth::user()->user_id)
                ->orderBy('payment_date', 'desc')
                ->first();

            return redirect()->route('orders.show', $order->transaction_id)->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create order. ' . $e->getMessage());
        }
    }

    public function show($transaction_id)
    {
        $order = Order::with(['user'])->findOrFail($transaction_id);

        if ($order->user_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $ticket = DB::table('ticket')
            ->join('ticket_type', 'ticket.ticket_type_id', '=', 'ticket_type.id')
            ->join('acara', 'ticket.event_id', '=', 'acara.event_id')
            ->where('ticket.transaction_id', $transaction_id)
            ->select('ticket.*', 'ticket_type.name as ticket_type_name', 'acara.title as event_title')
            ->first();

        return view('orders.show', compact('order', 'ticket'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->user_id)
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }

    public function simulatePayment($transaction_id)
    {
        $order = Order::findOrFail($transaction_id);

        if ($order->user_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($order->payment_status !== 'Pending') {
            return back()->with('error', 'Pesanan sudah diproses sebelumnya.');
        }

        // Update status to Verified
        $order->update(['payment_status' => 'Verified']);

        // Update Stock via TicketType
        $ticket = DB::table('ticket')->where('transaction_id', $transaction_id)->first();
        if ($ticket) {
            DB::table('ticket_type')
                ->where('id', $ticket->ticket_type_id)
                ->increment('quantity_sold', $order->total_ticket);
        }

        // Send Email
        try {
            $user = Auth::user();
            $order->load(['tickets.ticketType', 'tickets.event', 'event']);
            Mail::to($user->email)->send(new TicketMail($user, $order, $order->tickets));
        } catch (\Exception $e) {
            Log::error('Failed to send simulated ticket email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran berhasil disimulasi! Tiket Anda sudah aktif sekarang.');
    }
}
