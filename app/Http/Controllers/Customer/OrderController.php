<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'customer_name' => 'required|string|max:50',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart')
                ->with('error', 'Cart masih kosong.');
        }

        DB::beginTransaction();

        try {
            $mejaNumber = random_int(1, 15);

            $order = Order::create([
                'customer_name'  => $request->customer_name,
                'status_order'   => 'OPEN',
                'status_bayar'   => 'UNPAID',
                'order_datetime' => now(),
                'meja_number'    => $mejaNumber,
            ]);

            $total = 0;

            foreach ($cart as $item) {
                if (!isset($item['menu_id'], $item['qty'], $item['price'])) {
                    continue;
                }

                $subtotal = $item['qty'] * $item['price'];

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'menu_id'  => $item['menu_id'],
                    'quantity' => $item['qty'],
                    'subtotal' => $subtotal,
                    'notes'    => $item['note'] ?? null,
                ]);

                $total += $subtotal;
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('customer.qris', [
                'order_id' => $order->order_id,
                'total'    => $total,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('customer.cart')
                ->with('error', 'Gagal memproses order.');
        }
    }

    /**
     * =========================
     * PAYMENT SUCCESS
     * =========================
     */
    public function paySuccess($order_id)
    {
        $order = Order::with(['details', 'payment'])->findOrFail($order_id);

        $order->update([
            'status_order' => 'OPEN',
            'status_bayar' => 'PAID',
        ]);

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

        $order->update([
            'status_order' => 'CLOSE',
            'status_bayar' => 'UNPAID',
        ]);

        return redirect()
            ->route('customer.menuCustomer')
            ->with('success', 'Order berhasil dibatalkan.');
    }

    /**
     * =========================
     * VIEW ORDER
     * =========================
     */
    public function viewOrder()
    {
        $orders = Order::where('status_bayar', 'PAID')
            ->whereIn('status_order', ['OPEN', 'CLOSE'])
            ->orderByDesc('order_datetime')
            ->get();

        return view('customer.viewOrder', compact('orders'));
    }
}
