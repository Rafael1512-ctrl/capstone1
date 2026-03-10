<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-concert');
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