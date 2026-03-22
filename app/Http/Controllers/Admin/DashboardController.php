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
        // Total pendapatan dari order yang sudah Lunas
        $totalRevenue = Order::where('payment_status', 'Verified')->sum('total_amount') ?? 0;

        // Jumlah event
        $totalEvents = Event::count();

        // Jumlah tiket terjual
        $totalTicketsSold = Ticket::count();

        // Jumlah user biasa
        $totalUsers = User::where('role_id', 3)->count();

        // Jumlah organizer
        $totalOrganizers = User::where('role_id', 2)->count();

        // 5 event terlaris (berdasarkan jumlah tiket terjual)
        $topEvents = Event::withCount(['tickets as tickets_sold' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('payment_status', 'Verified');
                });
            }])
            ->orderByDesc('tickets_sold')
            ->limit(5)
            ->get(); // Fetch full models to avoid header issues

        // Grafik penjualan 7 hari terakhir
        $dailySales = Order::where('payment_status', 'Verified')
            ->where('payment_date', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(total_amount) as revenue'))
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