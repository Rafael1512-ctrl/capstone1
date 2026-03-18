<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/landingconcert', function () {
    return view('landing-concert');
})->name('landingconcert');

// Concert Pages (Public)
Route::get('/concert{number}', function ($number) {
    return view('concert' . $number);
})->where('number', '[1-8]')->name('concert');

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

Route::get('/ticket-detail', function () {
    return view('ticket');
})->name('ticket.detail');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events - Public Event Listing
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('can:view,order');
    Route::get('/events/{event}/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/events/{event}/order', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')->middleware('can:update,order');

    // Payments
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payments.show')->middleware('can:view,order');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'process'])->name('payments.process')->middleware('can:view,order');
    Route::get('/orders/{order}/payment/verify', [PaymentController::class, 'verify'])->name('payments.verify')->middleware('can:view,order');
    Route::post('/orders/{order}/refund', [PaymentController::class, 'refund'])->name('payments.refund')->middleware('can:view,order');

    // Tickets
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'view'])->name('tickets.view');
    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'generateQrCode'])->name('tickets.qr');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
    Route::post('/tickets/validate', [TicketController::class, 'validate'])->name('tickets.validate');
    Route::post('/tickets/scan', [TicketController::class, 'scan'])->name('tickets.scan');
});

// Organizer Routes (Organizer + Admin)
Route::middleware(['auth', 'organizer'])->group(function () {
    // Events Management
    Route::get('/organizer/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/organizer/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/organizer/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware('can:update,event');
    Route::put('/organizer/events/{event}', [EventController::class, 'update'])->name('events.update')->middleware('can:update,event');
    Route::delete('/organizer/events/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware('can:delete,event');
    Route::post('/organizer/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish')->middleware('can:update,event');

    // Ticket Categories
    Route::get('/organizer/events/{event}/tickets', [TicketCategoryController::class, 'index'])->name('ticket-categories.index')->middleware('can:update,event');
    Route::get('/organizer/events/{event}/tickets/create', [TicketCategoryController::class, 'create'])->name('ticket-categories.create')->middleware('can:update,event');
    Route::post('/organizer/events/{event}/tickets', [TicketCategoryController::class, 'store'])->name('ticket-categories.store')->middleware('can:update,event');
    Route::get('/organizer/events/{event}/tickets/{category}/edit', [TicketCategoryController::class, 'edit'])->name('ticket-categories.edit')->middleware('can:update,event');
    Route::put('/organizer/events/{event}/tickets/{category}', [TicketCategoryController::class, 'update'])->name('ticket-categories.update')->middleware('can:update,event');
    Route::delete('/organizer/events/{event}/tickets/{category}', [TicketCategoryController::class, 'destroy'])->name('ticket-categories.destroy')->middleware('can:update,event');
});

// Admin Routes Only
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin can access all organizer routes to manage events on behalf of organizers
    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');

    Route::get('/admin/analytics', function () {
        return view('admin.analytics');
    })->name('admin.analytics');

    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
});