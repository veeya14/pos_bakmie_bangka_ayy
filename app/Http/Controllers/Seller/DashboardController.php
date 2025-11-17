<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlah menu yang aktif & tersedia
        $availableDish = Menu::where('menu_active', 1)
                             ->where('menu_status', 'available')
                             ->count();

        // Total order hari ini
        $totalOrder = Order::whereDate('order_datetime', Carbon::today())->count();

        // Total penjualan bulan ini (gunakan join agar SQLite enum aman)
        $totalSalesMonth = Payment::join('orders', 'payments.order_id', '=', 'orders.order_id')
            ->where('orders.status_order', '=', 'CLOSE')
            ->where('orders.status_bayar', '=', 'PAID')
            ->whereMonth('payments.payment_datetime', Carbon::now()->month)
            ->sum('payments.amount');

        // Jumlah customer yang menunggu (order OPEN)
        $waitingCustomers = Order::where('status_order', '=', 'OPEN')->count();

        // Total penjualan per kategori
        $categorySales = Category::with(['menus.orderDetails.order.payment'])
            ->get()
            ->map(function($category) {
                $total = $category->menus->sum(function($menu) {
                    return $menu->orderDetails->sum(function($detail) {
                        return optional($detail->order->payment)->amount ?? 0;
                    });
                });

                return [
                    'name' => $category->name,
                    'total_sales' => $total,
                ];
            });

        // 5 order terakhir
        $recentOrders = Order::with(['meja', 'orderDetails.menu', 'payment'])
                             ->latest()
                             ->take(5)
                             ->get();

        // Penjualan per hari dalam minggu ini (join manual)
        $salesWeekRaw = Payment::join('orders', 'payments.order_id', '=', 'orders.order_id')
            ->where('orders.status_order', '=', 'CLOSE')
            ->where('orders.status_bayar', '=', 'PAID')
            ->whereBetween('payments.payment_datetime', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get()
            ->groupBy(function($payment) {
                return Carbon::parse($payment->payment_datetime)->format('D'); // Sun, Mon, ...
            });

        $daysOfWeek = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $salesChart = [
            'labels' => $daysOfWeek,
            'data' => collect($daysOfWeek)->map(fn($day) => 
                $salesWeekRaw->has($day) ? $salesWeekRaw[$day]->sum('amount') : 0
            ),
        ];

        return view('seller.dashboard', compact(
            'availableDish',
            'totalOrder',
            'totalSalesMonth',
            'waitingCustomers',
            'categorySales',
            'recentOrders',
            'salesChart'
        ));
    }
}
