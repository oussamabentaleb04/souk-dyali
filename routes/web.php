<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerApplicationController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\SellerProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('catalog.index');
})->name('home');

// Public catalog
Route::get('/shop', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/shop/product/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/shop/seller/{sellerProfile}', [CatalogController::class, 'shop'])->name('catalog.shop');

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

    // Cart and checkout (buyers)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Seller
    Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
        Route::resource('products', SellerProductController::class)->except(['show']);
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::put('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.status');
    });

    Route::get('/admin', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:admin');
});