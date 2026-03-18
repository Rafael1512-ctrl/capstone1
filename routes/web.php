<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('user');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/starter-template', function () {
        return view('starter-template');
    })->name('admin.dashboard');
});

Route::get('/landingconcert', function () {
    return view('landing-concert');
})->name('landingconcert');

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
    return view('concert1'); // Defaulting to concert1 for now
})->name('concert5');

Route::get('/concert6', function () {
    return view('concert1'); // Defaulting to concert1 for now
})->name('concert6');

Route::get('/concert7', function () {
    return view('concert1'); // Defaulting to concert1 for now
})->name('concert7');

Route::get('/concert8', function () {
    return view('concert1'); // Defaulting to concert1 for now
})->name('concert8');

Route::get('/about', function () {
    return view('about');
})->name('about');