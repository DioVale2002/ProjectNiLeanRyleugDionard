<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Guest routes (not logged in)
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register/otp/request', [AuthController::class, 'requestRegisterOtp'])->name('register.otp.request');
    Route::post('/register/otp/verify', [AuthController::class, 'verifyRegisterOtp'])->name('register.otp.verify');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
    Route::post('/login/otp/request', [AuthController::class, 'requestOtp'])->name('login.otp.request');

    Route::get('/login/password', [AuthController::class, 'showPasswordLogin'])->name('login.password');
    Route::post('/login/password', [AuthController::class, 'passwordLogin'])->name('login.password.submit');
});

// Authenticated routes (logged in)
Route::middleware(['auth:customer', 'no-back-history'])->group(function () {
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
    Route::patch('/account/orders/{order}/received', [AccountController::class, 'markReceived'])
        ->name('account.orders.received');
    Route::patch('/account/orders/{order}/cancel', [AccountController::class, 'cancelOrder'])
        ->name('account.orders.cancel');
    
    // Delete account
    Route::delete('/account/delete', [AccountController::class, 'deleteAccount'])->name('account.delete');
    Route::post('/account/delete/otp', [AccountController::class, 'requestDeleteOtp'])
        ->name('account.delete.otp.request');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->name('notifications.read-all');
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Admin - Inventory Management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::patch('products/{product}/unarchive', [\App\Http\Controllers\Admin\ProductController::class, 'unarchive'])
        ->name('products.unarchive');
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
        ->name('analytics.index');
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
        ->name('orders.index');
    Route::patch('orders/{order}/event', [\App\Http\Controllers\Admin\OrderController::class, 'handleEvent'])
        ->name('orders.event');
    Route::patch('orders/{order}/resolve', [\App\Http\Controllers\Admin\OrderController::class, 'resolveProblem'])
        ->name('orders.resolve');

    Route::get('stock', [\App\Http\Controllers\Admin\StockController::class, 'index'])
        ->name('stock.index');
    Route::post('stock/in', [\App\Http\Controllers\Admin\StockController::class, 'storeIn'])
        ->name('stock.in');
    Route::post('stock/out', [\App\Http\Controllers\Admin\StockController::class, 'storeOut'])
        ->name('stock.out');
});

// Catalog (public)
Route::get('/catalog', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [\App\Http\Controllers\CatalogController::class, 'show'])->name('catalog.show');

// Cart & Orders (auth required)
Route::middleware(['auth:customer', 'no-back-history'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout/address', [\App\Http\Controllers\OrderController::class, 'address'])->name('checkout.address');
    Route::post('/checkout/address', [\App\Http\Controllers\OrderController::class, 'saveAddressStep'])->name('checkout.address.save');
    Route::get('/checkout/payment', [\App\Http\Controllers\OrderController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment', [\App\Http\Controllers\OrderController::class, 'confirmPayment'])->name('checkout.payment.confirm');
    Route::get('/checkout/receipt/{order}', [\App\Http\Controllers\OrderController::class, 'receipt'])->name('checkout.receipt');
    Route::get('/checkout/receipt/{order}/pdf', [\App\Http\Controllers\OrderController::class, 'receiptPdf'])->name('checkout.receipt.pdf');

    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
});

Route::get('/landingpage', function () {
    return view('catalog.landingpage');
});