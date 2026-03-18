<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
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