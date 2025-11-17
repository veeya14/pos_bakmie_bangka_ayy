<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Menampilkan semua order
    public function index(Request $request)
    {
        // Optional filter
        $status = $request->status ?? null;
        $search = $request->search ?? null;

        $orders = Order::with(['meja', 'orderDetails.menu'])
            ->when($status, function($query, $status) {
                $query->where('status_order', $status);
            })
            ->when($search, function($query, $search) {
                $query->where('order_id', 'like', "%{$search}%");
            })
            ->orderBy('order_datetime', 'desc')
            ->get();

        return view('seller.orderList', compact('orders', 'status', 'search'));
    }

    // Lihat detail order
    public function show($orderId)
    {
        $order = Order::with(['meja', 'orderDetails.menu'])->findOrFail($orderId);
        return view('seller.orderDetail', compact('order'));
    }

    // Update status order
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $request->validate([
            'status_order' => 'required|in:OPEN,CLOSE',
        ]);

        $order->status_order = $request->status_order;
        $order->save();

        return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function history()
{
    $orders = Order::with('meja', 'orderDetails.menu', 'payment')
                   ->where('status_order', 'CLOSE') 
                   ->orderBy('order_datetime', 'desc')
                   ->get();

    return view('seller.orderHistory', compact('orders'));
}

}
