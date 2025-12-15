<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * =========================
     * CREATE ORDER
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50'
        ], [
            'customer_name.required' => 'Nama customer wajib diisi.'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart')
                ->with('error', 'Cart empty!');
        }

        // CREATE ORDER
        $order = Order::create([
            'customer_name'  => $request->customer_name,
            'status_order'   => 'OPEN',
            'status_bayar'   => 'UNPAID',
            'order_datetime' => now(),
        ]);

        $cartTotal = 0;

        foreach ($cart as $item) {
            $subtotal = $item['qty'] * $item['price'];

            OrderDetail::create([
                'order_id' => $order->order_id,
                'menu_id'  => $item['id'],
                'quantity' => $item['qty'],
                'subtotal' => $subtotal,
                'notes'    => $item['note'] ?? null,
            ]);

            $cartTotal += $subtotal;
        }

        session()->forget('cart');

        return redirect()->route('customer.qris', [
            'order_id' => $order->order_id,
            'total'    => $cartTotal
        ]);
    }


    /**
     * =========================
     * PAYMENT SUCCESS
     * =========================
     */
    public function paySuccess($order_id)
    {
        $order = Order::with(['details', 'payment'])->findOrFail($order_id);

        $order->status_order = 'OPEN';
        $order->status_bayar = 'PAID';
        $order->save();

        if ($order->payment) {
            $order->payment->payment_method   = 'qris';
            $order->payment->amount           = $order->details->sum('subtotal');
            $order->payment->payment_datetime = now();
            $order->payment->save();
        }

        return view('customer.paysucess', compact('order'));
    }


    /**
     * =========================
     * CANCEL ORDER
     * =========================
     */
    public function cancel($order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->status_order = 'CLOSE';
        $order->save();

        return redirect()->route('customer.menuCustomer')
            ->with('success', 'Order has been cancelled.');
    }


    /**
     * =========================
     * VIEW ORDER (FINAL)
     * - Langsung tampil list
     * - Search optional
     * =========================
     */
   public function viewOrder()
    {
        $orders = Order::where('status_bayar', 'PAID')
            ->whereIn('status_order', ['OPEN', 'CLOSE'])
            ->orderBy('order_datetime', 'desc')
            ->get();

        return view('customer.viewOrder', compact('orders'));
    }

    /**
     * SEARCH ORDER (ENTER / BUTTON)
     */
    public function searchOrderView(Request $request)
    {
        $keyword = trim($request->keyword);

        $orders = Order::where('status_bayar', 'PAID')
            ->whereIn('status_order', ['OPEN', 'CLOSE'])
            ->where(function ($q) use ($keyword) {
                $q->where('order_id', $keyword)
                  ->orWhere('customer_name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('order_datetime', 'desc')
            ->get();

        return view('customer.viewOrder', compact('orders'));
    }
}
