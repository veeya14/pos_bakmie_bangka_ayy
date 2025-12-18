<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
public function index(Request $request)
{
    $query = Order::with(['details.menu', 'payment']);

    // =========================
    // FILTER STATUS
    // =========================
    if ($request->status === 'IN_PROGRESS') {
        $query->where('status_order', 'OPEN')
              ->where('status_bayar', 'PAID');

    } elseif ($request->status === 'FINISHED') {
        $query->where('status_order', 'CLOSE')
              ->where('status_bayar', 'PAID');

    } else {
        // DEFAULT (ALL STATUS)
        $query->where(function ($q) {
            $q->where(function ($q) {
                $q->where('status_order', 'OPEN')
                  ->where('status_bayar', 'PAID');
            })
            ->orWhere(function ($q) {
                $q->where('status_order', 'CLOSE')
                  ->where('status_bayar', 'PAID');
            });
        });
    }

    // =========================
    // SEARCH ORDER ID
    // =========================
    if ($request->search) {
        $query->where('order_id', 'like', '%' . $request->search . '%');
    }

    $orders = $query
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
        'status_ui' => 'required|in:IN_PROGRESS,FINISHED,CANCEL',
    ]);

    $order = Order::findOrFail($orderId);
    if ($order->status_order === 'CLOSE') {
    return back()->with('error', 'Order sudah final dan tidak bisa diubah.');
}


    switch ($request->status_ui) {
        case 'IN_PROGRESS':
            $order->update([
                'status_order' => 'OPEN',
                'status_bayar' => 'PAID',
            ]);
            break;

        case 'FINISHED':
            $order->update([
                'status_order' => 'CLOSE',
                'status_bayar' => 'PAID',
            ]);
            break;

case 'CANCEL':
    $order->update([
        'status_order' => 'CLOSE',
        'status_bayar' => 'UNPAID',
    ]);
    break;


    }

    return redirect()
        ->route('seller.orders.index')
        ->with('success', 'Order status updated.');
}

    /**
     * =========================
     * ORDER HISTORY (ARSIP)
     * =========================
     */
public function history(Request $request)
{
    $query = Order::with(['details.menu', 'payment'])
        ->where('status_order', 'CLOSE');

    // TAMBAHAN FILTER STATUS
    if ($request->status === 'FINISH') {
        $query->where('status_bayar', 'PAID');
    }

    if ($request->status === 'CANCEL') {
        $query->where('status_bayar', 'UNPAID');
    }

    // (opsional) SEARCH ORDER ID
    if ($request->search) {
        $query->where('order_id', 'like', '%' . $request->search . '%');
    }

    $orders = $query
        ->orderByDesc('order_datetime')
        ->get();

    return view('seller.orderHistory', compact('orders'));
}

}
