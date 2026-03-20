<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrganizerController;

// ============================================================
Route::get('/', function () {
    return view('user');
})->name('home');
Route::get('/event/{event}', [\App\Http\Controllers\PublicController::class, 'showEvent'])->name('public.event.show');
// Route::get('/', function () {
//     if (auth()->check()) {
//         $user = auth()->user();
//         if ($user->isAdmin()) {
//             return redirect()->route('admin.starter');
//         }
//         if ($user->isOrganizer()) {
//             return redirect()->route('organizer.dashboard');
//         }
//         return redirect()->route('landing');
//     }
//     return redirect()->route('login');
// })->name('landingconcert');

Route::get('/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin')->middleware(['auth', 'role:admin']);

// Starter Template (Admin Only)
Route::get('/starter-template', function () {
    return view('starter-template');
})->name('admin.starter')->middleware(['auth', 'role:admin']);

// Landing Page (User & Organizer)
Route::get('/landing', function () {
    return view('user');
})->name('landing')->middleware('auth');

// Organizer Dashboard
Route::get('/organizer-dashboard', [OrganizerController::class, 'index'])
    ->name('organizer.dashboard')
    ->middleware(['auth', 'role:organizer,admin']);

// Concert Pages (Public)
Route::get('/concert1', function () {
    return view('concert1');
})->name('concert1');
Route::get('/concert2', function () {
    return view('concert2');
})->name('concert2');
Route::get('/concert3', function () {
    return view('concert3');
})->name('concert3');
Route::get('/concert4', function () {
    return view('concert4');
})->name('concert4');
Route::get('/concert5', function () {
    return view('concert5');
})->name('concert5');
Route::get('/concert6', function () {
    return view('concert6');
})->name('concert6');
Route::get('/concert7', function () {
    return view('concert7');
})->name('concert7');
Route::get('/concert8', function () {
    return view('concert8');
})->name('concert8');

Route::get('/about', function () {
    return view('about');
})->name('about');

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// PROTECTED ROUTES (Authenticated Users Only)
// ============================================================
Route::middleware('auth')->group(function () {

    // Dashboard (auto-routes based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboardPage'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    // Events - Public Event Listing (for logged-in users)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/events/{event}/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/events/{event}/order', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Payments
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/orders/{order}/payment/verify', [PaymentController::class, 'verify'])->name('payments.verify');

    // Tickets
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'view'])->name('tickets.view');
    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'generateQrCode'])->name('tickets.qr');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
    Route::post('/tickets/validate', [TicketController::class, 'validate'])->name('tickets.validate');
    Route::post('/tickets/scan', [TicketController::class, 'scan'])->name('tickets.scan');

    // ============================================================
    // ORGANIZER ROUTES (role: organizer or admin)
    // ============================================================
    Route::middleware('role:organizer,admin')->group(function () {
        Route::get('/organizer/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/organizer/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/organizer/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/organizer/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/organizer/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::post('/organizer/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

        // Ticket Types Management
        Route::get('/organizer/events/{event}/tickets', [TicketCategoryController::class, 'index'])->name('ticket-categories.index');
        Route::get('/organizer/events/{event}/tickets/create', [TicketCategoryController::class, 'create'])->name('ticket-categories.create');
        Route::post('/organizer/events/{event}/tickets', [TicketCategoryController::class, 'store'])->name('ticket-categories.store');
        Route::get('/organizer/events/{event}/tickets/{category}/edit', [TicketCategoryController::class, 'edit'])->name('ticket-categories.edit');
        Route::put('/organizer/events/{event}/tickets/{category}', [TicketCategoryController::class, 'update'])->name('ticket-categories.update');
        Route::delete('/organizer/events/{event}/tickets/{category}', [TicketCategoryController::class, 'destroy'])->name('ticket-categories.destroy');
    });

    // ============================================================
    // ADMIN-ONLY ROUTES
    // ============================================================
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', function () {
            return view('admin.users.index');
        })->name('admin.users.index');
    });
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('admin.dashboard');
    });
    Route::middleware('role:admin')->group(function () {
        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/admins', [App\Http\Controllers\Admin\UserManagementController::class, 'admins'])->name('admins');
            Route::get('/organizers', [App\Http\Controllers\Admin\UserManagementController::class, 'organizers'])->name('organizers');
            Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'users'])->name('users');

            Route::get('/create/{role?}', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}/update', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}/destroy', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('index');
            Route::get('/{order}', [App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('show');
            Route::put('/{order}/status', [App\Http\Controllers\Admin\OrderManagementController::class, 'updateStatus'])->name('status');
        });

        // Admin Event Management
        Route::prefix('admin/events')->name('admin.events.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\EventManagementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\EventManagementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\EventManagementController::class, 'store'])->name('store');
            Route::get('/{event}', [App\Http\Controllers\Admin\EventManagementController::class, 'show'])->name('show');
            Route::get('/{event}/edit', [App\Http\Controllers\Admin\EventManagementController::class, 'edit'])->name('edit');
            Route::put('/{event}', [App\Http\Controllers\Admin\EventManagementController::class, 'update'])->name('update');
            Route::delete('/{event}', [App\Http\Controllers\Admin\EventManagementController::class, 'destroy'])->name('destroy');

            // Ticket Management
            Route::get('/{event}/manage-tickets', [App\Http\Controllers\Admin\EventManagementController::class, 'manageTickets'])->name('manage-tickets');
            Route::post('/{event}/tickets', [App\Http\Controllers\Admin\EventManagementController::class, 'storeTicket'])->name('store-ticket');
            Route::put('/{event}/tickets', [App\Http\Controllers\Admin\EventManagementController::class, 'updateTicket'])->name('update-ticket');
            Route::delete('/{event}/tickets/{ticketId}', [App\Http\Controllers\Admin\EventManagementController::class, 'deleteTicket'])->name('delete-ticket');
        });

        // Analytics
        Route::prefix('admin/analytics')->name('admin.analytics.')->group(function () {
            Route::get('/sales', [App\Http\Controllers\Admin\AnalyticsController::class, 'sales'])->name('sales');
            Route::get('/transactions', [App\Http\Controllers\Admin\AnalyticsController::class, 'transactions'])->name('transactions');
            Route::get('/event-performance', [App\Http\Controllers\Admin\AnalyticsController::class, 'eventPerformance'])->name('event-performance');
            Route::get('/revenue-category', [App\Http\Controllers\Admin\AnalyticsController::class, 'revenueByCategory'])->name('revenue-category');
            Route::get('/user-stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'userStats'])->name('user-stats');
        });

        // Exports
        Route::prefix('admin/export')->name('admin.export.')->group(function () {
            Route::get('/events', [App\Http\Controllers\Admin\ExportController::class, 'exportEvents'])->name('events');
            Route::get('/orders', [App\Http\Controllers\Admin\ExportController::class, 'exportOrders'])->name('orders');
            Route::get('/sales', [App\Http\Controllers\Admin\ExportController::class, 'exportSalesReport'])->name('sales');
            Route::get('/event-performance', [App\Http\Controllers\Admin\ExportController::class, 'exportEventPerformance'])->name('event-performance');
        });

        // Event Categories
        Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\EventCategoryController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\EventCategoryController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\EventCategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [App\Http\Controllers\Admin\EventCategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [App\Http\Controllers\Admin\EventCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [App\Http\Controllers\Admin\EventCategoryController::class, 'destroy'])->name('destroy');
        });
    });
});
