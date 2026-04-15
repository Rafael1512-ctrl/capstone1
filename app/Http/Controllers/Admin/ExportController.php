<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Order;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsExport;
use App\Exports\OrdersExport;
use App\Exports\SalesReportExport;
use App\Exports\EventPerformanceExport;

class ExportController extends Controller
{
    /**
     * Display centralized export center
     */
    public function index()
    {
        $events = Event::orderBy('title')->get();
        return view('admin.exports.index', compact('events'));
    }

    /**
     * Export events to Excel (CSV)
     */
    public function exportEvents(Request $request)
    {
        $events = Event::with(['category', 'organizer', 'ticketTypes'])
            ->orderBy('schedule_time', 'desc')
            ->get();

        if (ob_get_contents()) ob_end_clean();
        return Excel::download(new EventsExport($events), 'events-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export orders to Excel (CSV)
     */
    public function exportOrders(Request $request)
    {
        $orders = Order::with(['user', 'event'])
            ->orderBy('payment_date', 'desc')
            ->get();

        if (ob_get_contents()) ob_end_clean();
        return Excel::download(new OrdersExport($orders), 'orders-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export sales report
     */
    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::where('payment_status', 'Verified');

        if ($startDate) {
            $query->where('payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('payment_date', '<=', $endDate . ' 23:59:59');
        }

        $orders = $query->with(['user', 'event'])->get();
        $totalRevenue = $orders->sum('total_amount');

        if (ob_get_contents()) ob_end_clean();
        return Excel::download(new SalesReportExport($orders, $totalRevenue), 'sales-report-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export event performance report
     */
    public function exportEventPerformance(Request $request)
    {
        $events = Event::with(['category', 'ticketTypes', 'tickets.order'])
            ->get();

        if (ob_get_contents()) ob_end_clean();
        return Excel::download(new EventPerformanceExport($events), 'event-performance-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Sales Analytics to PDF
     */
    public function exportSalesPdf(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $dateFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => 'Week %v (%Y)',
            'monthly' => '%M %Y',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        $query = Order::where('payment_status', 'Verified');

        if ($period === 'daily') {
            $query->where('payment_date', '>=', now()->subDays(7));
        } elseif ($period === 'weekly') {
            $query->where('payment_date', '>=', now()->subDays(30));
        } elseif ($period === 'monthly') {
            $query->where('payment_date', '>=', now()->subDays(90));
        }

        $salesData = $query->select(
                DB::raw("DATE_FORMAT(payment_date, '$dateFormat') as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $pdf = Pdf::loadView('exports.sales', [
            'title' => 'Sales Analytics Report',
            'salesData' => $salesData,
            'period_label' => ucfirst($period),
        ]);

        return $pdf->download('sales-analytics-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Unified Comprehensive Sales Report (PDF)
     */
    public function exportComprehensiveSalesPdf(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $dateFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => 'Week %v (%Y)',
            'monthly' => '%M %Y',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        // 1. Periodic Sales Data (Overall Overview)
        $salesOverview = Order::where('payment_status', 'Verified')
            ->select(
                DB::raw("DATE_FORMAT(payment_date, '$dateFormat') as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // 2. Per-Event Breakdown
        $eventsData = Event::with(['category', 'tickets.order' => function($q) {
            $q->where('payment_status', 'Verified')->with('user');
        }])->get()->map(function($event) {
            $verifiedTickets = $event->tickets->filter(function($t) {
                return $t->order && $t->order->payment_status === 'Verified';
            });
            $uniqueOrders = $verifiedTickets->pluck('order')->unique('transaction_id');
            return [
                'event' => $event,
                'total_tickets' => $verifiedTickets->count(),
                'total_revenue' => $uniqueOrders->sum('total_amount'),
                'orders' => $uniqueOrders
            ];
        });

        $pdf = Pdf::loadView('exports.comprehensive-sales', [
            'title' => 'TIXLY — Comprehensive Sales Report',
            'salesOverview' => $salesOverview,
            'eventsData' => $eventsData,
            'grand_total_revenue' => $eventsData->sum('total_revenue'),
            'grand_total_tickets' => $eventsData->sum('total_tickets'),
            'period_label' => ucfirst($period),
        ]);

        return $pdf->download('comprehensive-sales-report-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Transactions Analytics to PDF
     */
    public function exportTransactionsPdf(Request $request)
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

        $paymentMethods = Order::where('payment_date', '>=', now()->subDays($days))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $pdf = Pdf::loadView('exports.transactions', [
            'title' => 'Transaction Analytics Report',
            'transactionData' => $transactionData,
            'paymentMethods' => $paymentMethods,
            'period_label' => $days . ' Hari Terakhir',
        ]);

        return $pdf->download('transaction-analytics-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Event Performance Analytics to PDF
     */
    public function exportEventPerformancePdf(Request $request)
    {
        $query = Event::query();

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $events = $query->with(['ticketTypes', 'tickets.order', 'category'])
            ->get()
            ->map(function ($event) {
                $totalTicketsAvailable = $event->ticket_quota;
                
                // Filter tickets that have a verified order
                $verifiedTickets = $event->tickets->filter(function($ticket) {
                    return $ticket->order && $ticket->order->payment_status === 'Verified';
                });
                
                $totalTicketsSold = $verifiedTickets->count();
                $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

                // Revenue is the sum of total_amount of unique verified orders
                $uniqueOrders = $verifiedTickets->pluck('order')->unique('transaction_id');
                $revenue = $uniqueOrders->sum('total_amount');

                return [
                    'event_id' => $event->event_id,
                    'name' => $event->title,
                    'category' => $event->category?->name,
                    'date' => $event->schedule_time,
                    'status' => $event->status,
                    'total_available' => $totalTicketsAvailable,
                    'total_sold' => $totalTicketsSold,
                    'availability_rate' => round($soldPercentage, 2),
                    'revenue' => $revenue,
                    'orders_count' => $uniqueOrders->count(),
                ];
            });

        $pdf = Pdf::loadView('exports.event-performance', [
            'title' => 'Event Performance Report',
            'events' => $events,
        ]);

        return $pdf->download('event-performance-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export User Stats Analytics to PDF
     */
    public function exportUserStatsPdf()
    {
        $totalUsers = User::where('role_id', 3)->count();
        $totalOrganizers = User::where('role_id', 2)->count();
        $totalAdmins = User::where('role_id', 1)->count();

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

        $pdf = Pdf::loadView('exports.user-stats', [
            'title' => 'User Statistics Report',
            'totalUsers' => $totalUsers,
            'totalOrganizers' => $totalOrganizers,
            'totalAdmins' => $totalAdmins,
            'topBuyers' => $topBuyers,
        ]);

        return $pdf->download('user-stats-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export sales for a SPECIFIC event (Excel)
     */
    public function exportEventSalesExcel(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $orders = Order::where('event_id', $eventId)
            ->where('payment_status', 'Verified')
            ->with(['user'])
            ->get();
        
        $totalRevenue = $orders->sum('total_amount');

        if (ob_get_contents()) ob_end_clean();
        return Excel::download(new SalesReportExport($orders, $totalRevenue), 'event-' . str_replace(' ', '-', strtolower($event->title)) . '-sales-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export sales for a SPECIFIC event (PDF)
     */
    public function exportEventSalesPdf(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $orders = Order::where('event_id', $eventId)
            ->where('payment_status', 'Verified')
            ->with(['user'])
            ->get();

        $pdf = Pdf::loadView('exports.event-sales', [
            'title' => 'Event Sales Report',
            'event' => $event,
            'orders' => $orders,
            'total_revenue' => $orders->sum('total_amount'),
            'total_tickets' => $orders->sum('total_ticket'),
        ]);

        return $pdf->download('event-' . str_replace(' ', '-', strtolower($event->title)) . '-sales-' . date('Y-m-d') . '.pdf');
    }
}
