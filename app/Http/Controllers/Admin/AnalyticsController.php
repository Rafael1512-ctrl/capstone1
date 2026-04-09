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

        // Daily Sales (Last 7 Days) - Ensure all 7 days are present
        $rawDailySales = Order::where('payment_status', 'Verified')
            ->where('payment_date', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $dailySales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data = $rawDailySales->get($date);
            $dailySales->push((object)[
                'date' => $date,
                'orders' => $data ? $data->orders : 0,
                'revenue' => $data ? $data->revenue : 0
            ]);
        }

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
        $period = $request->input('period', 'monthly');
        $now = now();
        $startDate = null;
        $endDate = $now->copy();

        // Determine Start Date based on Period
        if ($period === 'daily') {
            $startDate = $now->copy()->subDays(29); // Last 30 days
        } elseif ($period === 'weekly') {
            $startDate = $now->copy()->subWeeks(11); // Last 12 weeks
        } elseif ($period === 'monthly') {
            $startDate = $now->copy()->startOfYear(); // From Jan
            $endDate = $now->copy()->endOfYear(); // To Dec
        } elseif ($period === 'yearly') {
            $startDate = $now->copy()->subYears(4); // Last 5 years
        }

        // Query verified orders
        $query = Order::where('payment_status', 'Verified');
        if ($startDate) {
            $query->where('payment_date', '>=', $startDate->startOfDay());
        }
        if ($endDate) {
            $query->where('payment_date', '<=', $endDate->endOfDay());
        }

        // Use a sortable grouped key (YYYY-MM-DD, YYYY-WW, YYYY-MM, or YYYY)
        $sqlFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%x-%v', // ISO Year and Week for MySQL
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        $rawSales = $query->select(
                DB::raw("DATE_FORMAT(payment_date, '$sqlFormat') as period_key"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->groupBy('period_key')
            ->get()
            ->keyBy('period_key');

        // Fill missing periods with zero data
        $salesData = collect();
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $key = match ($period) {
                'daily' => $current->format('Y-m-d'),
                'weekly' => $current->format('o-W'), // ISO Year and Week for PHP
                'monthly' => $current->format('Y-m'),
                'yearly' => $current->format('Y'),
            };

            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $label = match ($period) {
                'daily' => $current->format('d M Y'),
                'weekly' => 'M' . $current->format('W') . ' (' . $current->format('o') . ')',
                'monthly' => $monthNames[$current->month] . ' ' . $current->year,
                'yearly' => $current->format('Y'),
            };

            $data = $rawSales->get($key);

            $salesData->push((object)[
                'period' => $label,
                'total_orders' => $data ? $data->total_orders : 0,
                'total_revenue' => $data ? (float)$data->total_revenue : 0,
            ]);

            // Advance to next period
            match ($period) {
                'daily' => $current->addDay(),
                'weekly' => $current->addWeek(),
                'monthly' => $current->addMonth(),
                'yearly' => $current->addYear(),
            };

            // Safety break
            if ($salesData->count() > 500) break;
        }

        return view('admin.analytics.sales', compact('salesData', 'period'));
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
                // tickets() relationship on Event - Only count Active and Used
                $totalTicketsSold = $event->tickets()
                    ->whereIn('ticket_status', ['Active', 'Used'])
                    ->count();
                $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

                $revenue = $event->orders()
                    ->where('payment_status', 'Verified')
                    ->sum('total_amount') ?? 0;

                return [
                    'event_id' => $event->event_id,
                    'name' => $event->title,
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
      * Get detailed analytics for a single event
      */
     public function eventDetail(Request $request, Event $event)
     {
         $selectedBatch = $request->input('batch');
 
         // Filter ticketTypes based on batch if selected
         $event->load(['ticketTypes' => function($query) use ($selectedBatch) {
             if ($selectedBatch) {
                 $query->where('batch_number', $selectedBatch);
             }
         }, 'category', 'organizer']);
 
         // Update ticketStats distribution filtered by batch
         $ticketQuery = $event->tickets();
         if ($selectedBatch) {
             $ticketQuery->whereHas('ticketType', function($q) use ($selectedBatch) {
                 $q->where('batch_number', $selectedBatch);
             });
         }
 
         $ticketStats = [
             'total' => (clone $ticketQuery)->count(),
             'active' => (clone $ticketQuery)->where('ticket_status', 'Active')->count(),
             'used' => (clone $ticketQuery)->where('ticket_status', 'Used')->count(),
             'expired' => (clone $ticketQuery)->where('ticket_status', 'Expired')->count(),
         ];
 
         // Sales per Ticket Type - Grouped by Name to avoid duplicate cards for batches
         $ticketTypeSales = $event->ticketTypes->groupBy('name')->map(function ($types, $name) use ($event) {
             // Aggregate Used/Active counts for all ticket types in this group
             $typeIds = $types->pluck('id')->toArray();
             $usedCount = $event->tickets()->whereIn('ticket_type_id', $typeIds)->where('ticket_status', 'Used')->count();
             $activeCount = $event->tickets()->whereIn('ticket_type_id', $typeIds)->where('ticket_status', 'Active')->count();
             
             $actualSold = $usedCount + $activeCount; // Only count those that are confirmed
             $totalQuota = $types->sum('quantity_total');
             
             // Calculate revenue based on actual confirmed sold count
             $price = $types->first()->price;
             $totalRevenue = $actualSold * $price;
 
             return [
                 'name' => $name,
                 'price' => $price,
                 'total' => $totalQuota,
                 'sold' => $actualSold,
                 'revenue' => $totalRevenue,
                 'used' => $usedCount,
                 'active' => $activeCount,
                 'ids' => $typeIds
             ];
         })->values();
 
         // Daily Sales Trend for this specific event - Filtered by Batch if selected
         $salesTrendQuery = Order::whereHas('tickets', function ($q) use ($event, $selectedBatch) {
                 $q->where('event_id', $event->event_id);
                 if ($selectedBatch) {
                     $q->whereHas('ticketType', function($sq) use ($selectedBatch) {
                         $sq->where('batch_number', $selectedBatch);
                     });
                 }
             })
             ->where('payment_status', 'Verified');
 
         $salesTrend = $salesTrendQuery
             ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(total_ticket) as tickets'), DB::raw('SUM(total_amount) as revenue'))
             ->groupBy('date')
             ->orderBy('date')
             ->get();
 
         return view('admin.analytics.event-detail', compact('event', 'ticketStats', 'ticketTypeSales', 'salesTrend', 'selectedBatch'));
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
                    ->join('ticket', 'transaksi.ticket_id', '=', 'ticket.ticket_id')
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
