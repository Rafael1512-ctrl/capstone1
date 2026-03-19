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
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.starter');
        }
        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }
        return redirect()->route('landing');
    }
    return redirect()->route('login');
})->name('landingconcert');

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
Route::get('/concert1', function () { return view('concert1'); })->name('concert1');
Route::get('/concert2', function () { return view('concert2'); })->name('concert2');
Route::get('/concert3', function () { return view('concert3'); })->name('concert3');
Route::get('/concert4', function () { return view('concert4'); })->name('concert4');
Route::get('/concert5', function () { return view('concert5'); })->name('concert5');
Route::get('/concert6', function () { return view('concert6'); })->name('concert6');
Route::get('/concert7', function () { return view('concert7'); })->name('concert7');
Route::get('/concert8', function () { return view('concert8'); })->name('concert8');

Route::get('/about', function () { return view('about'); })->name('about');

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
});