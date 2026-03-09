<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('starter-template');
});

Route::get('/concert1', function () {
    return view('concert1');
})->name('concert1');

Route::get('/landingconcert', function () {
    return view('landing-concert');
})->name('landingconcert');

Route::get('/concert2', function () {
    return view('concert2');
})->name('concert2');

Route::get('/about', function () {
    return view('about');
})->name('about');