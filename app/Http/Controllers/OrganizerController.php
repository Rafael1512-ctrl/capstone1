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

                $qty = $order->total_ticket ?? $tickets->count();
                $type = $tickets->first()->ticketType->name ?? 'Standard';

                $ordersList[] = [
                    'order_number' => $order->transaction_id,
                    'buyer_name'   => $order->user->name ?? 'Unknown',
                    'buyer_email'  => $order->user->email ?? 'Unknown',
                    'ticket_type'  => $type,
                    'qty'          => $qty,
                    'amount'       => $order->total_amount, // Note: total amount migt be over whole transaction
                    'status'       => strtolower($order->payment_status),
                    'date'         => $order->payment_date ? $order->payment_date->format('d M Y') : '-',
                ];
            }

            // Generate daily sales data for chart
            $dailySales = [];
            foreach ($verifiedTickets as $ticket) {
                if ($ticket->order && $ticket->order->payment_date) {
                    $date = $ticket->order->payment_date->format('Y-m-d');
                    // We only count 1 per ticket row here, but wait, if order total_ticket is 4 and there's only 1 ticket row, 
                    // we should group by order and add total_ticket
                }
            }

            // Better daily sales calculation based on verified orders unique
            $dailySalesData = collect($ordersList)
                ->where('status', 'verified')
                ->groupBy('date')
                ->map(function ($dayOrders) {
                    return $dayOrders->sum('qty');
                })
                ->sortKeys();

            $chartDates = $dailySalesData->keys()->toArray();
            $chartData = $dailySalesData->values()->toArray();

            $statusClass = $event->status === 'published' ? 'active' : 'upcoming';

            $pct = $event->ticket_quota > 0 ? round(($sold / $event->ticket_quota) * 100) : 0;
            $performance = 'Needs Marketing Boost';
            if ($pct >= 90) $performance = 'Excellent (Almost Sold Out!)';
            elseif ($pct >= 50) $performance = 'Good Progress';
            elseif ($pct >= 10) $performance = 'Slow Starter';

            $myEvents[] = [
                'id'           => $event->event_id,
                'name'         => $event->title,
                'emoji'        => '✨',
                'banner_url'   => $event->banner_url,
                'venue'        => $event->location,
                'date'         => $event->schedule_time->format('d M Y'),
                'status_label' => ucfirst($event->status),
                'status_class' => $statusClass,
                'total'        => $event->ticket_quota,
                'sold'         => $sold,
                'revenue'      => $revenue,
                'orders'       => $uniqueOrdersCount,
                'performance'  => $performance,
                'chart_dates'  => $chartDates,
                'chart_data'   => $chartData,
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

    public function showReport($id)
    {
        $user = Auth::user();

        // Get single event
        $event = Event::where('organizer_id', $user->user_id)
            ->where('event_id', $id)
            ->with(['ticketTypes', 'tickets.order.user'])
            ->firstOrFail();

        $sold = 0;
        $revenue = 0;

        foreach ($event->ticketTypes as $type) {
            $sold += $type->quantity_sold;
            $revenue += ($type->quantity_sold * $type->price);
        }

        $verifiedTickets = $event->tickets->filter(function($t) {
            return $t->order && $t->order->payment_status === 'Verified';
        });
        $uniqueOrdersCount = $verifiedTickets->pluck('transaction_id')->unique()->count();
        
        $ordersList = [];
        $groupedByOrder = $event->tickets->groupBy('transaction_id');

        foreach ($groupedByOrder as $transactionId => $tickets) {
            $order = $tickets->first()->order;
            if (!$order) continue;

            $qty = $order->total_ticket ?? $tickets->count();
            $type = $tickets->first()->ticketType->name ?? 'Standard';

            $ordersList[] = [
                'order_number' => $order->transaction_id,
                'buyer_name'   => $order->user->name ?? 'Unknown',
                'buyer_email'  => $order->user->email ?? 'Unknown',
                'ticket_type'  => $type,
                'qty'          => $qty,
                'amount'       => $order->total_amount,
                'status'       => strtolower($order->payment_status),
                'date'         => $order->payment_date ? $order->payment_date->format('d M Y') : '-',
            ];
        }

        $dailySalesData = collect($ordersList)
            ->where('status', 'verified')
            ->groupBy('date')
            ->map(function ($dayOrders) {
                return $dayOrders->sum('qty');
            })
            ->sortKeys();

        $chartDates = $dailySalesData->keys()->toArray();
        $chartData = $dailySalesData->values()->toArray();

        $statusClass = $event->status === 'published' ? 'active' : 'upcoming';
        $pct = $event->ticket_quota > 0 ? round(($sold / $event->ticket_quota) * 100) : 0;
        $performance = 'Needs Marketing Boost';
        if ($pct >= 90) $performance = 'Excellent (Almost Sold Out!)';
        elseif ($pct >= 50) $performance = 'Good Progress';
        elseif ($pct >= 10) $performance = 'Slow Starter';

        $eventData = [
            'id'           => $event->event_id,
            'name'         => $event->title,
            'emoji'        => '✨',
            'banner_url'   => $event->banner_url,
            'venue'        => $event->location,
            'date'         => $event->schedule_time->format('d M Y'),
            'status_label' => ucfirst($event->status),
            'status_class' => $statusClass,
            'total'        => $event->ticket_quota,
            'sold'         => $sold,
            'revenue'      => $revenue,
            'orders'       => $uniqueOrdersCount,
            'performance'  => $performance,
            'chart_dates'  => $chartDates,
            'chart_data'   => $chartData,
            'orders_list'  => $ordersList,
        ];

        return view('organizer_report', compact('eventData'));
    }
}
