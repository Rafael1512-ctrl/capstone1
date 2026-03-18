<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\TicketCategory;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Show order form
     */
    public function create(Event $event)
    {
        $categories = $event->ticketCategories()->where('status', 'active')->where('available_tickets', '>', 0)->get();

        if ($categories->isEmpty()) {
            // Check if there's a waiting list option
            return view('orders.create', compact('event', 'categories'))->with('info', 'No tickets available. You can join the waiting list.');
        }

        return view('orders.create', compact('event', 'categories'));
    }

    /**
     * Store new order
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_category_id' => ['required', 'exists:ticket_categories,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'join_waiting_list' => ['sometimes', 'boolean'],
        ]);

        $category = TicketCategory::findOrFail($validated['ticket_category_id']);

        return DB::transaction(function () use ($validated, $event, $category, $request) {
            $quantity = $validated['quantity'];

            // Check if tickets are available
            if ($category->available_tickets >= $quantity) {
                return $this->createOrder($validated, $event, $category, $quantity);
            } elseif ($request->has('join_waiting_list')) {
                // Add to waiting list
                return $this->addToWaitingList($event, $category, $quantity);
            } else {
                return back()->with('error', 'Not enough tickets available. Would you like to join the waiting list?');
            }
        });
    }

    /**
     * Create order
     */
    private function createOrder($validated, $event, $category, $quantity)
    {
        $total_price = $category->price * $quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'quantity' => $quantity,
            'total_price' => $total_price,
            'status' => 'pending',
            'payment_method' => 'dummy',
        ]);

        // Decrease available tickets
        $category->decreaseAvailableTickets($quantity);

        // Generate tickets
        $this->generateTickets($order, $event, $category, $quantity);

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully! Proceed to payment.');
    }

    /**
     * Add to waiting list
     */
    private function addToWaitingList($event, $category, $quantity)
    {
        $position = WaitingList::where('event_id', $event->id)
            ->where('ticket_category_id', $category->id)
            ->max('position') + 1;

        WaitingList::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'ticket_category_id' => $category->id,
            'quantity' => $quantity,
            'position' => $position,
            'status' => 'waiting',
        ]);

        $category->increment('queue_count');

        return redirect()->route('dashboard')
            ->with('success', 'You have been added to the waiting list. Position: ' . $position);
    }

    /**
     * Generate tickets for order
     */
    private function generateTickets($order, $event, $category, $quantity)
    {
        for ($i = 0; $i < $quantity; $i++) {
            \App\Models\Ticket::create([
                'order_id' => $order->id,
                'event_id' => $event->id,
                'ticket_category_id' => $category->id,
                'status' => 'active',
            ]);
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('event', 'tickets');

        return view('orders.show', compact('order'));
    }

    /**
     * View user orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('event')->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if (!in_array($order->status, ['pending', 'failed'])) {
            return back()->with('error', 'Cannot cancel this order.');
        }

        return DB::transaction(function () use ($order) {
            // Refund tickets back to category
            $ticketsByCategory = $order->tickets()->with('ticketCategory')->get()->groupBy('ticket_category_id');

            foreach ($ticketsByCategory as $categoryId => $tickets) {
                $category = TicketCategory::find($categoryId);
                if ($category) {
                    $category->increaseAvailableTickets($tickets->count());
                }
            }

            // Cancel tickets
            $order->tickets()->update(['status' => 'cancelled']);

            // Cancel order
            $order->update(['status' => 'cancelled']);

            return back()->with('success', 'Order cancelled successfully. Tickets refunded.');
        });
    }
}
