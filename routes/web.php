<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
<<<<<<< Updated upstream
=======
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
    return redirect()->route('admin.dashboard');
})->name('dashboard.admin')->middleware(['auth', 'role:admin']);

// Starter Template (Admin Only)
Route::get('/starter-template', function () {
>>>>>>> Stashed changes
    return view('starter-template');
});

Route::get('/concert1', function () {
    return view('concert1');
})->name('concert1');

Route::get('/landingconcert', function () {
    return view('landing-concert');
})->name('landingconcert');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Admin Routes
Route::get('/admin', function () {
    return view('admin');
})->name('admin');

// Event Management Routes
Route::resource('event', EventController::class);

// User Management Routes
Route::resource('user', UserController::class);