<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Crop Management
    Route::resource('crops', CropController::class)->except(['show']);
    Route::post('/crops/{crop}/toggle-status', [CropController::class, 'toggleStatus'])->name('crops.toggle-status');

    // Market Prices
    Route::get('/market', [MarketController::class, 'index'])->name('market.index');
    Route::get('/market/search', [MarketController::class, 'search'])->name('market.search');

    // Sell Crops / Orders
    Route::get('/sell', [OrderController::class, 'sellIndex'])->name('orders.sell');
    Route::resource('orders', OrderController::class)->except(['edit']);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Government Schemes
    Route::get('/schemes', [SchemeController::class, 'index'])->name('schemes.index');
    Route::get('/schemes/{scheme}', [SchemeController::class, 'show'])->name('schemes.show');
    Route::post('/schemes/{scheme}/apply', [SchemeController::class, 'apply'])->name('schemes.apply');

    // Weather
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
    Route::get('/weather/search', [WeatherController::class, 'search'])->name('weather.search');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Payment Routes
    Route::get('/payment/checkout', [PaymentController::class, 'showCheckout'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

    // Admin Specific Management
    Route::middleware(['can:admin-only'])->group(function () {
        Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{user}/toggle', [App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    });
});
