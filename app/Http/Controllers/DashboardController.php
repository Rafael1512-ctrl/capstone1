<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isOrganizer()) {
            return $this->organizerDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_organizers' => \App\Models\User::where('role', 'organizer')->count(),
            'total_events' => Event::count(),
            'total_orders' => Order::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
        ];

        $recent_orders = Order::with('user', 'event')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_transactions = Transaction::with('order', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_orders', 'recent_transactions'));
    }

    /**
     * Organizer Dashboard
     */
    private function organizerDashboard()
    {
        $user = Auth::user();

        $events = Event::where('organizer_id', $user->id)
            ->withCount('orders', 'tickets')
            ->get();

        $stats = [
            'total_events' => $events->count(),
            'total_orders' => Order::whereIn('event_id', $events->pluck('id'))->count(),
            'total_revenue' => Order::whereIn('event_id', $events->pluck('id'))
                ->where('status', 'paid')
                ->sum('total_price'),
            'pending_orders' => Order::whereIn('event_id', $events->pluck('id'))
                ->where('status', 'pending')
                ->count(),
        ];

        $recent_orders = Order::whereIn('event_id', $events->pluck('id'))
            ->with('user', 'event')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.organizer', compact('stats', 'events', 'recent_orders'));
    }

    /**
     * User Dashboard
     */
    private function userDashboard()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        $tickets = \App\Models\Ticket::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('event', 'ticketCategory')
            ->get();

        $stats = [
            'total_tickets' => $tickets->count(),
            'used_tickets' => $tickets->where('status', 'used')->count(),
            'upcoming_events' => $orders->where('event.event_date', '>=', now()->toDateString())->count(),
        ];

        return view('dashboard.user', compact('orders', 'tickets', 'stats'));
    }
}
