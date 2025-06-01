<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;

// Test route
Route::get('/test', function() {
    return response()->json([
        'message' => 'API is working'
    ]);
});

// Product Routes
Route::get('/products', [ProductController::class, 'index']); // Menampilkan semua produk
Route::get('/products/{id}', [ProductController::class, 'show']); // Menampilkan detail produk

// User Routes
Route::get('/users', [UserController::class, 'index']); // Menampilkan semua user
Route::get('/users/{id}', [UserController::class, 'show']); // Menampilkan detail user
Route::get('/users/{id}/products', [UserController::class, 'products']); // Menampilkan produk milik user