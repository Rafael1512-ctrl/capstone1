<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Order;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Export events to Excel (CSV)
     */
    public function exportEvents(Request $request)
    {
        $events = Event::with('category', 'organizer', 'ticketTypes')
            ->orderBy('schedule_time', 'desc')
            ->get();

        // Create CSV
        $csv = "ID,Judul Event,Organizer,Kategori,Tanggal,Lokasi,Status,Tiket Terjual,Total Tiket,Revenue\n";

        foreach ($events as $event) {
            $totalTickets = $event->ticketTypes->sum('quantity_total');
            $soldTickets = $event->ticketTypes->sum('quantity_sold');
            $revenue = $event->orders()->where('status', 'Lunas')->sum('total_amount');

            $csv .= "\"{$event->event_id}\"";
            $csv .= ",\"" . str_replace('"', '""', $event->name) . "\"";
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
        $orders = Order::with('user', 'event')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $csv = "ID,Order Number,User,Event,Amount,Status,Created\n";

        foreach ($orders as $order) {
            $csv .= "\"" . $order->transaction_id . "\"";
            $csv .= ",\"" . ($order->transaction_id) . "\""; // db_tixly uses ID as number too
            $csv .= ",\"" . ($order->user->name ?? '-') . "\"";
            $csv .= ",\"" . str_replace('"', '""', $order->event->name ?? '-') . "\"";
            $csv .= "," . number_format($order->total_amount, 2, ',', '.');
            $csv .= "," . $order->status;
            $csv .= ",\"" . ($order->transaction_date ? $order->transaction_date->format('Y-m-d H:i:s') : '-') . "\"\n";
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

        $query = Order::where('status', 'Lunas');

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate . ' 23:59:59');
        }

        $orders = $query->with('user', 'event')->get();

        $csv = "Tanggal,Event,User,Order ID,Amount,Payment Status\n";
        $totalRevenue = 0;

        foreach ($orders as $order) {
            $csv .= "\"" . ($order->transaction_date ? $order->transaction_date->format('Y-m-d H:i:s') : '-') . "\"";
            $csv .= ",\"" . str_replace('"', '""', $order->event->name ?? '-') . "\"";
            $csv .= ",\"" . ($order->user->name ?? '-') . "\"";
            $csv .= ",\"" . $order->transaction_id . "\"";
            $csv .= "," . number_format($order->total_amount, 2, ',', '.');
            $csv .= "," . $order->status . "\n";

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
        $events = Event::with('category', 'ticketTypes', 'orders')
            ->get();

        $csv = "Event ID,Event Title,Category,Status,Total Tickets,Sold Tickets,Availability %,Revenue,Orders Count\n";

        foreach ($events as $event) {
            $totalTickets = $event->ticketTypes->sum('quantity_total');
            $soldTickets = $event->ticketTypes->sum('quantity_sold');
            $availability = $totalTickets > 0 ? round((($totalTickets - $soldTickets) / $totalTickets) * 100, 2) : 0;
            $revenue = $event->orders()->where('status', 'Lunas')->sum('total_amount');
            $ordersCount = $event->orders()->where('status', 'Lunas')->count();

            $csv .= "\"" . $event->event_id . "\"";
            $csv .= ",\"" . str_replace('"', '""', $event->name) . "\"";
            $csv .= ",\"" . ($event->category?->name ?? '-') . "\"";
            $csv .= "," . $event->status;
            $csv .= "," . $totalTickets;
            $csv .= "," . $soldTickets;
            $csv .= "," . $availability . "%";
            $csv .= "," . number_format($revenue, 2, ',', '.');
            $csv .= "," . $ordersCount . "\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="event-performance-' . date('Y-m-d') . '.csv"');
    }
}
