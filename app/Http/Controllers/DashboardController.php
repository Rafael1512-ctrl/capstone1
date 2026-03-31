<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }

        // Regular users are directed to the landing page (home)
        return redirect()->route('home');
    }

    public function adminDashboardPage()
    {
        // Delegate to the AnalyticsController which has the full dashboard logic
        return app(Admin\AnalyticsController::class)->dashboard();
    }
}
