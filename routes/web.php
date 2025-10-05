<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Guest routes (not logged in)
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes (logged in)
Route::middleware('auth:customer')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('account.orders');
    })->name('dashboard');
    
    // Account pages
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/archived', [AccountController::class, 'archived'])->name('account.archived');
    Route::get('/account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::get('/account/security', [AccountController::class, 'security'])->name('account.security');
    
    // Update routes
    Route::put('/account/address/update', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::put('/account/info/update', [AccountController::class, 'updateInfo'])->name('account.info.update');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});