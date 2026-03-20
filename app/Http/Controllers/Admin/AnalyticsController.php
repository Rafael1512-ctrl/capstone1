<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        // Basic Statistics
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $totalEvents = Event::count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $paidOrders = Order::where('status', 'paid')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Revenue Stats
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');

        // Top Events
        $topEvents = Event::withCount(['orders' => function ($q) {
            $q->where('status', 'paid');
        }])
            ->orderByDesc('orders_count')
            ->limit(5)
            ->with('category')
            ->get();

        // Top Organizers
        $topOrganizers = User::where('role', 'organizer')
            ->withCount(['events'])
            ->orderByDesc('events_count')
            ->limit(5)
            ->get();

        // Daily Sales (Last 7 Days)
        $dailySales = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalEvents',
            'totalOrganizers',
            'totalUsers',
            'totalOrders',
            'paidOrders',
            'pendingOrders',
            'todayRevenue',
            'monthRevenue',
            'topEvents',
            'topOrganizers',
            'dailySales'
        ));
    }

    /**
     * Get sales analytics
     */
    public function sales(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly, yearly

        $dateFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-w%v',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        $salasData = Order::where('status', 'paid')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$dateFormat') as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return view('admin.analytics.sales', compact('salasData', 'period'));
    }

    /**
     * Get transaction analytics
     */
    public function transactions(Request $request)
    {
        $days = $request->input('days', 30);

        $transactionData = Order::where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("'pending' as status"),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'pending')
            ->groupBy('date', 'status')
            ->union(
                Order::where('created_at', '>=', now()->subDays($days))
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw("'paid' as status"),
                        DB::raw('COUNT(*) as count')
                    )
                    ->where('status', 'paid')
                    ->groupBy('date', 'status')
            )
            ->orderBy('date')
            ->get();

        $paymentMethods = Payment::where('created_at', '>=', now()->subDays($days))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.analytics.transactions', compact('transactionData', 'paymentMethods', 'days'));
    }

    /**
     * Get event performance analytics
     */
    public function eventPerformance(Request $request)
    {
        $query = Event::query();

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $events = $query->with(['category', 'ticketTypes'])
            ->get()
            ->map(function ($event) {
                $totalTicketsAvailable = $event->ticketTypes->sum('quantity_total');
                $totalTicketsSold = $event->ticketTypes->sum('quantity_sold');
                $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

                $revenue = $event->orders()
                    ->where('status', 'paid')
                    ->sum('total_amount');

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'category' => $event->category?->name,
                    'date' => $event->date,
                    'location' => $event->location,
                    'status' => $event->status,
                    'total_available' => $totalTicketsAvailable,
                    'total_sold' => $totalTicketsSold,
                    'availability_rate' => round($soldPercentage, 2),
                    'revenue' => $revenue,
                    'orders_count' => $event->orders()->where('status', 'paid')->count(),
                ];
            });

        $categories = \App\Models\EventCategory::where('is_active', true)->get();

        return view('admin.analytics.event-performance', compact('events', 'categories'));
    }

    /**
     * Revenue by category
     */
    public function revenueByCategory()
    {
        $data = \App\Models\EventCategory::with(['events' => function ($q) {
            $q->with(['orders' => function ($q2) {
                $q2->where('status', 'paid');
            }]);
        }])
            ->get()
            ->map(function ($category) {
                $revenue = $category->events
                    ->flatMap(fn($event) => $event->orders)
                    ->sum('total_amount');

                return [
                    'category' => $category->name,
                    'revenue' => $revenue,
                    'color' => $category->color,
                    'event_count' => $category->events->count(),
                ];
            });

        return view('admin.analytics.revenue-category', compact('data'));
    }

    /**
     * User statistics
     */
    public function userStats()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $usersGrowth = User::where('role', 'user')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topBuyers = User::where('role', 'user')
            ->withCount(['orders' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get();

        return view('admin.analytics.user-stats', compact(
            'totalUsers',
            'totalOrganizers',
            'totalAdmins',
            'usersGrowth',
            'topBuyers'
        ));
    }
}
