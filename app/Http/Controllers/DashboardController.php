<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOrganizer()) {
            return $this->organizerDashboard();
        }

        return $this->userDashboard();
    }

    public function adminDashboardPage()
    {
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users'       => User::where('role_id', 3)->count(),
            'total_organizers'  => User::where('role_id', 2)->count(),
            'total_events'      => Event::count(),
            'total_orders'      => Order::count(),
            'total_revenue'     => Order::where('payment_status', 'Verified')->sum('total_amount'),
            'pending_payments'  => Order::where('payment_status', 'Pending')->count(),
        ];

        $recent_orders = Order::with(['user', 'tickets.event'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    private function organizerDashboard()
    {
        $user   = Auth::user();
        $events = Event::where('organizer_id', $user->user_id)->get();
        $eventIds = $events->pluck('event_id');

        $stats = [
            'total_events'  => $events->count(),
            'total_orders'  => Order::whereHas('tickets', function($q) use ($eventIds) {
                                    $q->whereIn('event_id', $eventIds);
                                })->count(),
            'total_revenue' => Order::whereHas('tickets', function($q) use ($eventIds) {
                                    $q->whereIn('event_id', $eventIds);
                                })->where('payment_status', 'Verified')
                                  ->sum('total_amount'),
            'pending_orders' => Order::whereHas('tickets', function($q) use ($eventIds) {
                                    $q->whereIn('event_id', $eventIds);
                                })->where('payment_status', 'Pending')
                                  ->count(),
        ];

        $recent_orders = Order::whereHas('tickets', function($q) use ($eventIds) {
                                    $q->whereIn('event_id', $eventIds);
                                })
            ->with(['user', 'tickets.event'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.organizer', compact('stats', 'events', 'recent_orders'));
    }

    private function userDashboard()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->user_id)
            ->with('tickets.event')
            ->orderBy('payment_date', 'desc')
            ->get();

        $tickets = Ticket::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->user_id);
        })->with('ticketType', 'event')->get();

        $stats = [
            'total_orders'   => $orders->count(),
            'total_tickets'  => $tickets->count(),
            'used_tickets'   => $tickets->where('ticket_status', 'Used')->count(),
            'active_tickets' => $tickets->where('ticket_status', 'Active')->count(),
        ];

        return view('dashboard.user', compact('orders', 'tickets', 'stats'));
    }
}
