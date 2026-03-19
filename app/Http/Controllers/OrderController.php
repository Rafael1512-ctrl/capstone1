<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketType;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Show order form
     */
    public function create(Event $event)
    {
        $ticketTypes = $event->ticketTypes()->get()->filter(function ($type) {
            return $type->availableStock() > 0;
        });

        if ($ticketTypes->isEmpty()) {
            return view('orders.create', compact('event', 'ticketTypes'))->with('info', 'No tickets available. You can join the waiting list.');
        }

        return view('orders.create', compact('event', 'ticketTypes'));
    }

    /**
     * Store new order
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_type_id' => ['required', 'exists:ticket_types,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'join_waiting_list' => ['sometimes', 'boolean'],
        ]);

        $ticketType = TicketType::findOrFail($validated['ticket_type_id']);

        return DB::transaction(function () use ($validated, $event, $ticketType, $request) {
            $quantity = $validated['quantity'];

            // Check if tickets are available
            if ($ticketType->availableStock() >= $quantity) {
                return $this->createOrder($validated, $event, $ticketType, $quantity);
            } elseif ($request->has('join_waiting_list')) {
                // Add to waiting list
                return $this->addToWaitingList($event, $ticketType, $quantity);
            } else {
                return back()->with('error', 'Not enough tickets available. Would you like to join the waiting list?');
            }
        });
    }

    /**
     * Create order
     */
    private function createOrder($validated, $event, $ticketType, $quantity)
    {
        $total_amount = $ticketType->price * $quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $total_amount,
            'status' => 'pending',
        ]);

        // Create Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => $quantity,
            'unit_price' => $ticketType->price,
        ]);

        // Update quantity_sold
        $ticketType->increment('quantity_sold', $quantity);

        // Generate tickets as active (or wait until payment is done)
        $this->generateTickets($order, $event, $ticketType, $quantity);

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully! Proceed to payment.');
    }

    /**
     * Add to waiting list
     */
    private function addToWaitingList($event, $ticketType, $quantity)
    {
        WaitingList::updateOrCreate(
            [
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'user_email' => Auth::user()->email,
            ],
            [
                'notified' => false,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', 'You have been added to the waiting list. We will notify you when tickets become available.');
    }

    /**
     * Generate tickets for order
     */
    private function generateTickets($order, $event, $ticketType, $quantity)
    {
        for ($i = 0; $i < $quantity; $i++) {
            \App\Models\Ticket::create([
                'order_id' => $order->id,
                'ticket_type_id' => $ticketType->id,
                'unique_code' => Str::uuid(),
                'is_used' => false,
            ]);
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Simple manual authorization or use Policy if it exists
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['event', 'tickets', 'items.ticketType']);

        return view('orders.show', compact('order'));
    }

    /**
     * View user orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with(['event', 'items'])->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'failed'])) {
            return back()->with('error', 'Cannot cancel this order.');
        }

        return DB::transaction(function () use ($order) {
            // Refund quantities back to ticket types
            foreach ($order->items as $item) {
                $item->ticketType->decrement('quantity_sold', $item->quantity);
            }

            // Update status
            $order->tickets()->update(['status' => 'cancelled']);
            $order->update(['status' => 'cancelled']);

            return back()->with('success', 'Order cancelled successfully. Tickets refunded.');
        });
    }
}
