<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Meja;

class MenuController extends Controller
{
    /**
     * =========================
     * MENU LIST
     * =========================
     */
    public function index(Request $request)
{
    // SESSION INIT (HANYA SEKALI)
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

    return view('customer.menuCustomer', compact(
        'categories',
        'menus',
        'selectedCategory'
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
