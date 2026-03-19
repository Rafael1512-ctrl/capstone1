<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Route ke halaman dashboard sesuai role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isOrganizer()) {
            return $this->organizerDashboard();
        }

        return $this->userDashboard();
    }

    /**
     * Admin Dashboard page (standalone route)
     */
    public function adminDashboardPage()
    {
        return $this->adminDashboard();
    }

    // -----------------------------------------------
    // Admin
    // -----------------------------------------------
    private function adminDashboard()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_organizers'  => User::where('role', 'organizer')->count(),
            'total_events'      => Event::count(),
            'total_orders'      => Order::count(),
            'total_revenue'     => Payment::where('status', 'success')->sum('amount'),
            'pending_payments'  => Payment::where('status', 'pending')->count(),
        ];

        $recent_orders = Order::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    // -----------------------------------------------
    // Organizer
    // -----------------------------------------------
    private function organizerDashboard()
    {
        $user   = Auth::user();
        $events = Event::where('organizer_id', $user->id)->withCount('orders')->get();
        $eventIds = $events->pluck('id');

        $stats = [
            'total_events'  => $events->count(),
            'total_orders'  => Order::whereIn('event_id', $eventIds)->count(),
            'total_revenue' => Order::whereIn('event_id', $eventIds)
                                    ->where('status', 'paid')
                                    ->sum('total_amount'),
            'pending_orders' => Order::whereIn('event_id', $eventIds)
                                    ->where('status', 'pending')
                                    ->count(),
        ];

        $recent_orders = Order::whereIn('event_id', $eventIds)
            ->with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.organizer', compact('stats', 'events', 'recent_orders'));
    }

    // -----------------------------------------------
    // Regular User
    // -----------------------------------------------
    private function userDashboard()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        $tickets = Ticket::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('ticketType.event')->get();

        $stats = [
            'total_orders'   => $orders->count(),
            'total_tickets'  => $tickets->count(),
            'used_tickets'   => $tickets->where('is_used', true)->count(),
            'active_tickets' => $tickets->where('is_used', false)->count(),
        ];

        return view('dashboard.user', compact('orders', 'tickets', 'stats'));
    }
}
