<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MenuController extends Controller
{

    public function index(Request $request)
{
    if (!session()->has('entered_restaurant')) {
        session()->invalidate();
        session()->regenerate(true);

        session([
            'entered_restaurant' => true,
            'meja_number'        => random_int(1, 15),
        ]);

        session()->forget('cart');
    }

    $selectedCategory = $request->category ?? 'Menu Paket';
    
    $categories = Category::all();

    $category = Category::where('name', $selectedCategory)->first();

    $menus = Menu::query()
        ->when($category, function ($query) use ($category) {
            $query->where('id_category', $category->id_category);
        })
        ->where('menu_active', 1)
        ->where('menu_status', 'available')
        ->get();

    $oneMonthAgo = Carbon::now()->subMonth();

$bestSellerIds = OrderDetail::select(
        'order_details.menu_id',
        DB::raw('SUM(order_details.quantity) as total_qty')
    )
    ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
    ->where('orders.status_bayar', 'PAID')
    ->where('orders.created_at', '>=', $oneMonthAgo)
    ->groupBy('order_details.menu_id')
    ->orderByDesc('total_qty')
    ->limit(5) 
    ->pluck('menu_id')
    ->toArray();

return view('customer.menuCustomer', compact(
    'categories',
    'menus',
    'selectedCategory',
    'bestSellerIds'
));
}

    public function detail($id)
    {
        $menu = Menu::findOrFail($id);

        return view('customer.menu-detail', compact('menu'));
    }

    public function menuByMeja(Meja $meja)
    {
        session()->invalidate();
        session()->regenerate(true);

        session([
            'entered_restaurant' => true,
            'meja_number'        => $meja->nomor_meja,
        ]);

        session()->forget('cart');

        return redirect()->route('customer.menuCustomer');
    }
}
