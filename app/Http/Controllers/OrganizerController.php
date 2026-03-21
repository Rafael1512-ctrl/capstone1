<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil events milik organizer beserta ticketTypes dan tickets (melalui Order)
        $events = Event::where('organizer_id', $user->user_id)
            ->with(['ticketTypes', 'tickets.order.user'])
            ->get();

        $myEvents = [];
        $totalTicketsSold = 0;
        $totalOrders = 0;
        $totalRevenue = 0;

        foreach ($events as $event) {
            $sold = 0;
            $revenue = 0;

            foreach ($event->ticketTypes as $type) {
                $sold += $type->quantity_sold;
                $revenue += ($type->quantity_sold * $type->price);
            }

            // Generate orders list by grouping active/verified tickets by their transaction
            // Transaksi hanya bisa di-link lewat "tickets", jadi kita filter tiket event ini
            $verifiedTickets = $event->tickets->filter(function($t) {
                return $t->order && $t->order->payment_status === 'Verified';
            });
            $uniqueOrdersCount = $verifiedTickets->pluck('transaction_id')->unique()->count();
            
            $ordersList = [];
            $groupedByOrder = $event->tickets->groupBy('transaction_id');

            foreach ($groupedByOrder as $transactionId => $tickets) {
                $order = $tickets->first()->order;
                if (!$order) continue;

                $qty = $tickets->count();
                $type = $tickets->first()->ticketType->name ?? 'Standard';

                $ordersList[] = [
                    'order_number' => $order->transaction_id,
                    'buyer_name'   => $order->user->name ?? 'Unknown',
                    'buyer_email'  => $order->user->email ?? 'Unknown',
                    'ticket_type'  => $type,
                    'qty'          => $qty,
                    'amount'       => $order->total_amount, // Note: total amount migt be over whole transaction
                    'status'       => strtolower($order->payment_status),
                    'date'         => $order->payment_date->format('d M Y'),
                ];
            }

            $statusClass = $event->status === 'published' ? 'active' : 'upcoming';

            $myEvents[] = [
                'id'           => $event->event_id,
                'name'         => $event->title,
                'emoji'        => '✨', // Just placeholder
                'venue'        => $event->location,
                'date'         => $event->schedule_time->format('d M Y'),
                'status_label' => ucfirst($event->status),
                'status_class' => $statusClass,
                'total'        => $event->ticket_quota,
                'sold'         => $sold,
                'orders'       => $uniqueOrdersCount,
                'orders_list'  => $ordersList,
            ];

            $totalTicketsSold += $sold;
            $totalOrders += $uniqueOrdersCount;
            $totalRevenue += $revenue;
        }

        return view('organizer', compact(
            'myEvents',
            'totalTicketsSold',
            'totalOrders',
            'totalRevenue'
        ));
    }
}
