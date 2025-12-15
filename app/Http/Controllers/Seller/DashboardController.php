<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // TOTAL MENU
        $totalMenu = Menu::count();

        // ============================
        // BEST SELLER (TOP 3 - PAID)
        // ============================
        $bestSellers = OrderDetail::select(
                'order_details.menu_id',
                DB::raw('SUM(order_details.quantity) as total_qty')
            )
            ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->where('orders.status_bayar', 'PAID')
            ->groupBy('order_details.menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->limit(3)
            ->get();

        // ============================
        // PENDING ORDERS → IN PROGRESS
        // ============================
        $pendingOrders = Order::where('status_order', 'OPEN')
            ->where('status_bayar', 'PAID')
            ->count();

        // ============================
        // WEEKLY SALES CHART
        // ============================
        $weekDays = collect();
        $salesData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $day  = Carbon::today()->subDays($i)->format('D');
            $date = Carbon::today()->subDays($i)->toDateString();

            $totalSales = Order::whereDate('created_at', $date)
                ->where('status_bayar', 'PAID')
                ->count();

            $weekDays->push($day);
            $salesData->push($totalSales);
        }

        return view('seller.dashboard', compact(
            'totalMenu',
            'bestSellers',
            'pendingOrders',
            'weekDays',
            'salesData'
        ));
    }
}
