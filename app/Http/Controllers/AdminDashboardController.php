<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_organizers' => \App\Models\User::where('role', 'organizer')->count(),
            'total_events' => \App\Models\Event::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_revenue' => \App\Models\Transaction::where('status', 'completed')->sum('amount'),
            'pending_transactions' => \App\Models\Transaction::where('status', 'pending')->count(),
        ]);
    }

    /**
     * Get analytics data
     */
    public function analytics(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly
        $days = $period === 'daily' ? 7 : ($period === 'weekly' ? 30 : 90);

        $transactionData = \App\Models\Transaction::where('completed_at', '>=', now()->subDays($days))
            ->where('status', 'completed')
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'period' => $period,
            'data' => $transactionData,
        ]);
    }

    /**
     * Get top events
     */
    public function topEvents()
    {
        $events = \App\Models\Event::withCount('orders')
            ->withCount('tickets')
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    /**
     * Get revenue by ticket category
     */
    public function revenueByCategory()
    {
        $data = \App\Models\TicketCategory::selectRaw('name, COUNT(*) as sold, SUM(price) as revenue')
            ->join('tickets', 'ticket_categories.id', '=', 'tickets.ticket_category_id')
            ->where('tickets.status', 'used')
            ->groupBy('name')
            ->get();

        return response()->json($data);
    }
}
