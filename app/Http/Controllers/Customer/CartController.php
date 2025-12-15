<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartCount = collect($cart)->sum('qty');
        return view('customer.order', compact('cart', 'cartCount'));
    }

   public function add(Request $request)
{
    $menu = Menu::findOrFail($request->menu_id);

    $cart = session()->get('cart', []);

    if (isset($cart[$menu->id_menu])) {
        $cart[$menu->id_menu]['qty'] += 1;
    } else {
        $cart[$menu->id_menu] = [
            'id' => $menu->id_menu,
            'name' => $menu->menu_name,
            'price' => $menu->menu_price,
            'qty' => 1,
            'image' => $menu->menu_image,
        ];
    }

    session()->put('cart', $cart);
    return back()->with('success', 'Item added to cart!');
}


    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->menu_id])) {
            $cart[$request->menu_id]['qty'] = $request->qty;
        }

        session()->put('cart', $cart);

        return back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        unset($cart[$request->menu_id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Item removed');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared!');
    }

    public function note(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->menu_id])) {
        $cart[$request->menu_id]['note'] = $request->note;
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Note saved!');
}

}
