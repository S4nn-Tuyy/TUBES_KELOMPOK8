<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Basic Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');


// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

