<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
   public function index(Request $request)
{
    $selectedCategory = $request->category ?? 'Menu Paket';

    $categories = Category::all();

    $category = Category::where('name', $selectedCategory)->first();

    $menus = Menu::query()
        ->when($category, function ($query) use ($category) {
            return $query->where('id_category', $category->id_category);
        })
        ->where('menu_active', 1)
        ->where('menu_status', 'available')
        ->get();

    return view('customer.menuCustomer', compact('categories', 'menus', 'selectedCategory'));
}

public function detail($id)
{
    $menu = Menu::findOrFail($id);

    return view('customer.menu-detail', compact('menu'));
}


}
