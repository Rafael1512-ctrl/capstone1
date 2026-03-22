<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
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
        $totalRevenue = Order::where('payment_status', 'Verified')->sum('total_amount') ?? 0;
        $totalEvents = Event::count();
        $totalOrganizers = User::where('role_id', 2)->count();
        $totalUsers = User::where('role_id', 3)->count();
        $totalOrders = Order::count();
        $paidOrders = Order::where('payment_status', 'Verified')->count();
        $pendingOrders = Order::where('payment_status', 'Pending')->count();

        // Revenue Stats
        $todayRevenue = Order::where('payment_status', 'Verified')
            ->whereDate('payment_date', today())
            ->sum('total_amount') ?? 0;

        $monthRevenue = Order::where('payment_status', 'Verified')
            ->whereMonth('payment_date', date('m'))
            ->whereYear('payment_date', date('Y'))
            ->sum('total_amount') ?? 0;

        // Top Events
        $topEvents = Event::withCount([
            'orders' => function ($q) {
                $q->where('payment_status', 'Verified');
            }
        ])
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();

        // Top Organizers
        $topOrganizers = User::where('role_id', 2)
            ->withCount(['events'])
            ->orderByDesc('events_count')
            ->limit(5)
            ->get();

        // Daily Sales (Last 7 Days)
        $dailySales = Order::where('payment_status', 'Verified')
            ->where('payment_date', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
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

        $salasData = Order::where('payment_status', 'Verified')
            ->select(
                DB::raw("DATE_FORMAT(payment_date, '$dateFormat') as period"),
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

        $transactionData = Order::where('payment_date', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('payment_status as status'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        // Payment Methods stats
        $paymentMethods = Order::where('payment_date', '>=', now()->subDays($days))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
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

        $events = $query->with(['ticketTypes'])
            ->get()
            ->map(function ($event) {
                // In db_tixly, ticket_quota is on the event (acara) too?
                // Let's check ticket_quota.
                $totalTicketsAvailable = $event->ticket_quota;
                // tickets() relationship on Event
                $totalTicketsSold = $event->tickets()->count();
                $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

                $revenue = $event->orders()
                    ->where('payment_status', 'Verified')
                    ->sum('total_amount') ?? 0;

                return [
                    'event_id' => $event->event_id,
                    'name' => $event->name,
                    'date' => $event->schedule_time,
                    'location' => $event->location,
                    'status' => $event->status,
                    'total_available' => $totalTicketsAvailable,
                    'total_sold' => $totalTicketsSold,
                    'availability_rate' => round($soldPercentage, 2),
                    'revenue' => $revenue,
                    'orders_count' => $event->orders()->where('payment_status', 'Verified')->count(),
                ];
            });

        $categories = DB::table('kategori_acara')->get();

        return view('admin.analytics.event-performance', compact('events', 'categories'));
    }

    /**
     * Revenue by category
     */
    public function revenueByCategory()
    {
        $data = DB::table('kategori_acara')
            ->get()
            ->map(function ($category) {
                $revenue = DB::table('transaksi')
                    ->join('ticket', 'transaksi.transaction_id', '=', 'ticket.transaction_id')
                    ->join('acara', 'ticket.event_id', '=', 'acara.event_id')
                    ->where('acara.category_id', $category->category_id)
                    ->where('transaksi.payment_status', 'Verified')
                    ->sum('transaksi.total_amount') ?? 0;

                return [
                    'category' => $category->name,
                    'revenue' => $revenue,
                    'event_count' => DB::table('acara')->where('category_id', $category->category_id)->count(),
                ];
            });

        return view('admin.analytics.revenue-category', compact('data'));
    }

    /**
     * User statistics
     */
    public function userStats()
    {
        $totalUsers = User::where('role_id', 3)->count();
        $totalOrganizers = User::where('role_id', 2)->count();
        $totalAdmins = User::where('role_id', 1)->count();

        $usersGrowth = collect();

        $topBuyers = User::where('role_id', 3)
            ->withCount([
                'orders' => function ($q) {
                    $q->where('payment_status', 'Verified');
                }
            ])
            ->withSum([
                'orders as total_spent' => function ($q) {
                    $q->where('payment_status', 'Verified');
                }
            ], 'total_amount')
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
