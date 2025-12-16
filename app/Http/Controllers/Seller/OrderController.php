<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * =========================
     * ORDER LIST (DAPUR / AKTIF)
     * =========================
     */
    public function index()
{
    $orders = Order::with(['details.menu', 'payment'])
        ->where(function ($q) {
            // IN PROGRESS
            $q->where('status_order', 'OPEN')
              ->where('status_bayar', 'PAID');
        })
        ->orWhere(function ($q) {
            // FINISH
            $q->where('status_order', 'CLOSE')
              ->where('status_bayar', 'PAID');
        })
        ->orderByDesc('order_datetime')
        ->get();

    return view('seller.orderList', compact('orders'));
}


    /**
     * =========================
     * UPDATE STATUS DARI SELLER
     * =========================
     */
    public function updateStatus(Request $request, $orderId)
{
    $request->validate([
        'status_ui' => 'required|in:IN_PROGRESS,FINISHED',
    ]);

    $order = Order::findOrFail($orderId);

    if ($request->status_ui === 'IN_PROGRESS') {
        $order->update([
            'status_order' => 'OPEN',
            'status_bayar' => 'PAID',
        ]);
    }

    if ($request->status_ui === 'FINISHED') {
        $order->update([
            'status_order' => 'CLOSE',
            'status_bayar' => 'PAID',
        ]);
    }

    return back()->with('success', 'Order status updated.');
}

    /**
     * =========================
     * ORDER HISTORY (ARSIP)
     * =========================
     */
public function history()
{
    $orders = Order::with(['details.menu', 'payment'])
        ->where('status_order', 'CLOSE') 
        ->orderByDesc('order_datetime')
        ->get();

    return view('seller.orderHistory', compact('orders'));
}


}
