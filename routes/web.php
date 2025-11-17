<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\MenuController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Middleware\CheckSellerSession;


Route::get('/', function () {
    return redirect()->route('seller.register');
});


Route::prefix('seller')->name('seller.')->group(function () {

    Route::get('register', [SellerAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [SellerAuthController::class, 'register'])->name('register.submit');

    Route::get('login', [SellerAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [SellerAuthController::class, 'login'])->name('login.submit');

    Route::post('logout', [SellerAuthController::class, 'logout'])->name('logout');
});

Route::prefix('seller')->name('seller.')->middleware(CheckSellerSession::class)->group(function () {


    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('menus', MenuController::class);

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('order-history', [OrderController::class, 'history'])->name('orders.history');

    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
});
