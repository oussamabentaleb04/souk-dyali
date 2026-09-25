<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SellerApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Only for visitors who are NOT logged in
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Only for logged-in users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/buyer', [DashboardController::class, 'buyer'])
        ->name('buyer.dashboard')
        ->middleware('role:buyer,seller,admin');

    Route::get('/seller', [DashboardController::class, 'seller'])
        ->name('seller.dashboard')
        ->middleware('role:seller');

    Route::get('/seller/apply', [SellerApplicationController::class, 'create'])->name('seller.apply');
    Route::post('/seller/apply', [SellerApplicationController::class, 'store'])->name('seller.apply.store');

    Route::get('/admin', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:admin');
});