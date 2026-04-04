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

class ExportController extends Controller
{
    /**
     * Export events to Excel (CSV)
     */
    public function exportEvents(Request $request)
    {
        $events = Event::with(['category', 'organizer', 'ticketTypes'])
            ->orderBy('schedule_time', 'desc')
            ->get();

        // Create CSV
        $csv = "ID,Judul Event,Organizer,Kategori,Tanggal,Lokasi,Status,Tiket Terjual,Total Tiket,Revenue\n";

        foreach ($events as $event) {
            $totalTickets = $event->ticketTypes->sum('quantity_total');
            $soldTickets = $event->ticketTypes->sum('quantity_sold');
            // Check orders status 'Verified' for revenue
            $revenue = $event->tickets->sum(function($ticket) {
                return $ticket->order && $ticket->order->payment_status === 'Verified' ? $ticket->price : 0;
            });

            $csv .= "\"{$event->event_id}\"";
            $csv .= ",\"" . str_replace('"', '""', $event->title) . "\"";
            $csv .= ",\"" . ($event->organizer->name ?? '-') . "\"";
            $csv .= ",\"" . ($event->category?->name ?? '-') . "\"";
            $csv .= ",\"" . ($event->schedule_time ? $event->schedule_time->format('Y-m-d') : '-') . "\"";
            $csv .= ",\"" . str_replace('"', '""', $event->location) . "\"";
            $csv .= "," . $event->status;
            $csv .= "," . $soldTickets;
            $csv .= "," . $totalTickets;
            $csv .= "," . number_format($revenue, 2, ',', '.') . "\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="events-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Export orders to Excel (CSV)
     */
    public function exportOrders(Request $request)
    {
        $orders = Order::with(['user', 'event'])
            ->orderBy('payment_date', 'desc')
            ->get();

        $csv = "ID,User,Event,Amount,Method,Status,Created\n";

        foreach ($orders as $order) {
            $csv .= "\"" . $order->transaction_id . "\"";
            $csv .= ",\"" . ($order->user->name ?? '-') . "\"";
            $csv .= ",\"" . str_replace('"', '""', $order->event->title ?? '-') . "\"";
            $csv .= "," . number_format($order->total_amount, 2, ',', '.');
            $csv .= "," . ($order->payment_method ?? '-');
            $csv .= "," . $order->payment_status;
            $csv .= ",\"" . ($order->payment_date ? $order->payment_date->format('Y-m-d H:i:s') : '-') . "\"\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="orders-' . date('Y-m-d') . '.csv"');
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

        $csv = "Tanggal,Event,User,Order ID,Amount,Payment Status\n";
        $totalRevenue = 0;

        foreach ($orders as $order) {
            $csv .= "\"" . ($order->payment_date ? $order->payment_date->format('Y-m-d H:i:s') : '-') . "\"";
            $csv .= ",\"" . str_replace('"', '""', $order->event->title ?? '-') . "\"";
            $csv .= ",\"" . ($order->user->name ?? '-') . "\"";
            $csv .= ",\"" . $order->transaction_id . "\"";
            $csv .= "," . number_format($order->total_amount, 2, ',', '.');
            $csv .= "," . $order->payment_status . "\n";

            $totalRevenue += $order->total_amount;
        }

        $csv .= "\n\nTOTAL REVENUE," . number_format($totalRevenue, 2, ',', '.') . "\n";

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="sales-report-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Export event performance report
     */
    public function exportEventPerformance(Request $request)
    {
        $events = Event::with(['category', 'ticketTypes', 'tickets.order'])
            ->get();

        $csv = "Event ID,Event Title,Category,Status,Total Tickets,Sold Tickets,Availability %,Revenue\n";

        foreach ($events as $event) {
            $totalTickets = $event->ticketTypes->sum('quantity_total');
            $soldTickets = $event->ticketTypes->sum('quantity_sold');
            $availability = $totalTickets > 0 ? round((($totalTickets - $soldTickets) / $totalTickets) * 100, 2) : 0;
            
            // Revenue only from verified orders
            $revenue = $event->tickets->sum(function($ticket) {
                return $ticket->order && $ticket->order->payment_status === 'Verified' ? $ticket->price : 0;
            });

            $csv .= "\"" . $event->event_id . "\"";
            $csv .= ",\"" . str_replace('"', '""', $event->title) . "\"";
            $csv .= ",\"" . ($event->category?->name ?? '-') . "\"";
            $csv .= "," . $event->status;
            $csv .= "," . $totalTickets;
            $csv .= "," . $soldTickets;
            $csv .= "," . $availability . "%";
            $csv .= "," . number_format($revenue, 2, ',', '.') . "\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="event-performance-' . date('Y-m-d') . '.csv"');
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
                $totalTicketsSold = $event->tickets()->count();
                $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

                $revenue = $event->orders()
                    ->where('payment_status', 'Verified')
                    ->sum('total_amount') ?? 0;

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
                    'orders_count' => $event->orders()->where('payment_status', 'Verified')->count(),
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
}
