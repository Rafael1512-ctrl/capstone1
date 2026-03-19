<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pendapatan dari order yang sudah paid
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');

        // Jumlah event
        $totalEvents = Event::count();

        // Jumlah tiket terjual
        $totalTicketsSold = Ticket::count();

        // Jumlah user biasa
        $totalUsers = User::where('role', 'user')->count();

        // Jumlah organizer
        $totalOrganizers = User::where('role', 'organizer')->count();

        // 5 event terlaris (berdasarkan jumlah tiket terjual)
        $topEvents = Event::withCount(['tickets as tickets_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'paid');
                });
            }])
            ->orderByDesc('tickets_sold')
            ->limit(5)
            ->get(['id', 'title']);

        // Grafik penjualan 7 hari terakhir
        $dailySales = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalEvents',
            'totalTicketsSold',
            'totalUsers',
            'totalOrganizers',
            'topEvents',
            'dailySales'
        ));
    }
}