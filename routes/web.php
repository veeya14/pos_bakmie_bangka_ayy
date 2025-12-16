<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\MenuController as SellerMenuController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Middleware\CheckSellerSession;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

Route::get('/', function () { 
    return redirect()->route('customer.menuCustomer'); 
});

Route::prefix('customer')->name('customer.')->group(function () {

    Route::get('/view-order', [CustomerOrderController::class, 'viewOrder'])
        ->name('viewOrder');

    Route::get('/search-order-view', [CustomerOrderController::class, 'searchOrderView'])
        ->name('searchOrderView');

    Route::get('/menu/meja/{meja}', [CustomerMenuController::class, 'menuByMeja'])
    ->name('menu.meja');

    // MENU LIST
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menuCustomer');
    Route::get('/menu-detail/{id}', [CustomerMenuController::class, 'detail'])->name('menu.detail');

    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/note', [CartController::class, 'note'])->name('cart.note');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    // STORE ORDER
    Route::post('/order/store', [CustomerOrderController::class, 'store'])->name('order.store');

    // QRIS PAGE
    Route::get('/qris', function () {
        return view('customer.qris', [
            'order_id' => request('order_id'),
            'total' => request('total')
        ]);
    })->name('qris');

    // PAY SUCCESS
    Route::get('/paysuccess/{order_id}', [CustomerOrderController::class, 'paySuccess'])
        ->name('paysuccess');

    // ORDER STATUS PAGE
    Route::get('/order-status/{order_id}', function ($order_id) {
        $order = \App\Models\Order::with('details.menu')->findOrFail($order_id);
        return view('customer.orderstatus', compact('order'));
    })->name('order.status');

    // CANCEL ORDER
    Route::post('/order/cancel/{order_id}', [CustomerOrderController::class, 'cancel'])
        ->name('order.cancel');
});



// seller
Route::prefix('seller')->name('seller.')->group(function () {

    Route::get('register', [SellerAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [SellerAuthController::class, 'register'])->name('register.submit');

    Route::get('login', [SellerAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [SellerAuthController::class, 'login'])->name('login.submit');

    Route::post('logout', [SellerAuthController::class, 'logout'])->name('logout');
});

Route::prefix('seller')->name('seller.')->middleware(CheckSellerSession::class)->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('menus', SellerMenuController::class)->except(['edit', 'create', 'show']);

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    Route::get('order-history', [OrderController::class, 'history'])->name('orders.history');

    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
});


