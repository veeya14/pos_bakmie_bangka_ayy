<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * ORDER LIST
     * Hanya tampilkan:
     * - IN PROGRESS  => status_order = OPEN  & status_bayar = PAID
     * - SENT         => status_order = CLOSE & status_bayar = PAID
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
                // SENT
                $q->where('status_order', 'CLOSE')
                  ->where('status_bayar', 'PAID');
            })
            ->orderBy('order_datetime', 'desc')
            ->get();

        return view('seller.orderList', compact('orders'));
    }

    /**
     * DETAIL ORDER (kalau nanti kamu mau pakai halaman detail terpisah)
     */
    public function show($orderId)
    {
        $order = Order::with(['details.menu', 'payment'])
            ->findOrFail($orderId);

        return view('seller.orderDetail', compact('order'));
    }

    /**
     * UPDATE STATUS PESANAN DARI SELLER
     *
     * Dari UI:
     * - IN_PROGRESS => OPEN  + PAID  (In Progress)
     * - SENT        => CLOSE + PAID  (Sent)
     */
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status_ui' => 'required|in:IN_PROGRESS,SENT',
        ]);

        $order = Order::findOrFail($orderId);

        if ($request->status_ui === 'IN_PROGRESS') {
            // Pesanan sedang diproses
            $order->status_order = 'OPEN';
            $order->status_bayar = 'PAID';
        }

        if ($request->status_ui === 'SENT') {
            // Pesanan sudah dikirim / selesai
            $order->status_order = 'CLOSE';
            $order->status_bayar = 'PAID';
        }

        $order->save();

        return back()->with('success', 'Order status updated.');
    }

    /**
     * ORDER HISTORY
     * Menampilkan semua order yang sudah CLOSE:
     * - FINISH = CLOSE + PAID
     * - CANCEL = CLOSE + UNPAID
     * (status ditentukan di Blade: seller/orderHistory.blade.php)
     */
    public function history()
    {
        $orders = Order::with(['details.menu', 'payment'])
            ->where('status_order', 'CLOSE')
            ->orderBy('order_datetime', 'desc')
            ->get();

        return view('seller.orderHistory', compact('orders'));
    }
}
