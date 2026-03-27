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
use App\Http\Controllers\ProfileController;


// Public Routes
Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/concert/{event}', [\App\Http\Controllers\PublicController::class, 'showEvent'])->name('public.event.show');
Route::get('/landing', [\App\Http\Controllers\PublicController::class, 'index'])->name('landing')->middleware('auth');

// Dynamic Ticket & Checkout Routes
Route::get('/concert/{event}/ticket', [\App\Http\Controllers\PublicController::class, 'showTicket'])->name('public.ticket.show');
Route::get('/concert/{event}/checkout/{ticketType}', [\App\Http\Controllers\PublicController::class, 'showCheckout'])->name('public.checkout.show');
Route::post('/concert/{event}/checkout/{ticketType}', [\App\Http\Controllers\PublicController::class, 'processCheckout'])->name('public.checkout.process')->middleware('auth');

// Debug route for performer data (admin only)
Route::get('/debug/event/{event}/performers', function (\App\Models\Event $event) {
    return response()->json([
        'event_id' => $event->event_id,
        'title' => $event->title,
        'category' => $event->category?->name,
        'performers' => $event->performers ?? [],
    ]);
})->middleware(['auth', 'role:admin'])->name('debug.event.performers');

// Admin & Organizer Dashboards
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard.admin')->middleware(['auth', 'role:admin']);

Route::get('/starter-template', function () {
    return view('starter-template');
})->name('admin.starter')->middleware(['auth', 'role:admin']);

Route::get('/organizer-dashboard', [OrganizerController::class, 'index'])
    ->name('organizer.dashboard')
    ->middleware(['auth', 'role:organizer,admin']);

Route::get('/organizer-dashboard/event/{id}', [OrganizerController::class, 'showReport'])
    ->name('organizer.report')
    ->middleware(['auth', 'role:organizer,admin']);

// Concert Pages (Static / Public)
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

// Static Ticket Route (Legacy)
Route::get('/ticket', function () {
    $type = request('type', 'concert1');
    $concerts = [
        'concert1' => ['title' => 'Radiohead', 'date' => '12 Oct 2026', 'location' => 'JIS, Jakarta', 'image' => 'concert_1.jpg', 'reg' => '850.000', 'vip' => '2.500.000', 'vvip' => '5.000.000'],
        'concert2' => ['title' => 'Coldplay', 'date' => '15 Nov 2026', 'location' => 'Gelora Bung Karno', 'image' => 'coldplay.png', 'reg' => '1.200.000', 'vip' => '3.500.000', 'vvip' => '7.500.000'],
        'concert3' => ['title' => 'Taylor Swift', 'date' => '20 Dec 2026', 'location' => 'Exclusive Arena', 'image' => 'taylorswift.png', 'reg' => '2.500.000', 'vip' => '5.500.000', 'vvip' => '12.000.000'],
        'concert4' => ['title' => 'Arctic Monkeys', 'date' => '10 Jan 2027', 'location' => 'Stadium Jakarta', 'image' => 'arcticmonkeys.png', 'reg' => '650.000', 'vip' => '1.800.000', 'vvip' => '3.500.000'],
        'concert5' => ['title' => 'Bruno Mars', 'date' => '22 Feb 2027', 'location' => 'Sentul International', 'image' => 'BrunoMars.png', 'reg' => '950.000', 'vip' => '2.800.000', 'vvip' => '5.500.000'],
        'concert6' => ['title' => 'The Weeknd', 'date' => '30 Mar 2027', 'location' => 'GBK Madya Stadium', 'image' => 'the-weeknd.jpg', 'reg' => '1.100.000', 'vip' => '3.200.000', 'vvip' => '6.000.000'],
        'concert7' => ['title' => 'Ed Sheeran', 'date' => '15 May 2027', 'location' => 'JIS, Jakarta', 'image' => 'edsheeran.jpg', 'reg' => '900.000', 'vip' => '2.600.000', 'vvip' => '4.800.000'],
        'concert8' => ['title' => 'Billie Eilish', 'date' => '20 Jun 2027', 'location' => 'ICE BSD, Tangerang', 'image' => 'billie-eilish.jpg', 'reg' => '1.300.000', 'vip' => '3.800.000', 'vvip' => '7.000.000'],
    ];

    $data = $concerts[$type] ?? $concerts['concert1'];
    return view('ticket', compact('data'));
})->name('ticket');

// Static Checkout Route (Legacy)
Route::get('/checkout', function () {
    $type = request('type', 'concert1');
    $category = request('category', 'reg');

    $concerts = [
        'concert1' => ['title' => 'Radiohead', 'date' => '12 Oct 2026', 'location' => 'JIS, Jakarta', 'image' => 'concert_1.jpg', 'reg' => '850.000', 'vip' => '2.500.000', 'vvip' => '5.000.000'],
        'concert2' => ['title' => 'Coldplay', 'date' => '15 Nov 2026', 'location' => 'Gelora Bung Karno', 'image' => 'coldplay.png', 'reg' => '1.200.000', 'vip' => '3.500.000', 'vvip' => '7.500.000'],
        'concert3' => ['title' => 'Taylor Swift', 'date' => '20 Dec 2026', 'location' => 'Exclusive Arena', 'image' => 'taylorswift.png', 'reg' => '2.500.000', 'vip' => '5.500.000', 'vvip' => '12.000.000'],
        'concert4' => ['title' => 'Arctic Monkeys', 'date' => '10 Jan 2027', 'location' => 'Stadium Jakarta', 'image' => 'arcticmonkeys.png', 'reg' => '650.000', 'vip' => '1.800.000', 'vvip' => '3.500.000'],
        'concert5' => ['title' => 'Bruno Mars', 'date' => '22 Feb 2027', 'location' => 'Sentul International', 'image' => 'BrunoMars.png', 'reg' => '950.000', 'vip' => '2.800.000', 'vvip' => '5.500.000'],
        'concert6' => ['title' => 'The Weeknd', 'date' => '30 Mar 2027', 'location' => 'GBK Madya Stadium', 'image' => 'the-weeknd.jpg', 'reg' => '1.100.000', 'vip' => '3.200.000', 'vvip' => '6.000.000'],
        'concert7' => ['title' => 'Ed Sheeran', 'date' => '15 May 2027', 'location' => 'JIS, Jakarta', 'image' => 'edsheeran.jpg', 'reg' => '900.000', 'vip' => '2.600.000', 'vvip' => '4.800.000'],
        'concert8' => ['title' => 'Billie Eilish', 'date' => '20 Jun 2027', 'location' => 'ICE BSD, Tangerang', 'image' => 'billie-eilish.jpg', 'reg' => '1.300.000', 'vip' => '3.800.000', 'vvip' => '7.000.000'],
    ];

    $data = $concerts[$type] ?? $concerts['concert1'];
    $categoryNames = ['reg' => 'General Admission', 'vip' => 'VIP Experience', 'vvip' => 'VVIP Suite'];

    $selected = [
        'category' => $categoryNames[$category] ?? 'General Admission',
        'price' => $data[$category] ?? $data['reg'],
        'type' => $type
    ];

    return view('checkout', compact('data', 'selected'));
})->name('checkout');

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
    
    // Reset Password Routes
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('show-reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
});

// Email Verification Routes
Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verify-email');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email.post');
Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('resend-verification-email')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// PROFILE ROUTES (Authenticated Users)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

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
    Route::post('/orders/{order}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('orders.simulate-payment');

    // Payments
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/orders/{order}/payment/verify', [PaymentController::class, 'verify'])->name('payments.verify');

    // Tickets
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'view'])->name('tickets.view');
    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'generateQrCode'])->name('tickets.qr');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
    Route::post('/tickets/validate', [TicketController::class, 'verifyTicket'])->name('tickets.validate');
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
