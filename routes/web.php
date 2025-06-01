<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;

// Basic Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Product Routes
Route::resource('products', ProductController::class);

// Transaction Routes
Route::resource('transactions', TransactionController::class)->except(['create', 'edit', 'destroy']);
Route::post('transactions/{transaction}/confirm', [TransactionController::class, 'confirm'])->name('transactions.confirm');
Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

// Review Routes
Route::post('transactions/{transaction}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// Message Routes
Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('messages/{user}', [MessageController::class, 'show'])->name('messages.show');
Route::post('messages/{user}', [MessageController::class, 'store'])->name('messages.store');
Route::get('messages/product/{product}', [MessageController::class, 'startConversation'])->name('messages.create');
Route::get('messages/unread/count', [MessageController::class, 'getUnreadCount'])->name('messages.unread');

// Admin Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products/{product}/toggle', [AdminController::class, 'toggleProductVisibility'])->name('admin.products.toggle');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});
