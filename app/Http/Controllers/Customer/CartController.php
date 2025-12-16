<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * =========================
     * VIEW CART
     * =========================
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartCount = collect($cart)->sum('qty');

        return view('customer.order', compact('cart', 'cartCount'));
    }

    /**
     * =========================
     * ADD TO CART
     * =========================
     */
    public function add(Request $request)
{
    $request->validate([
        'menu_id' => 'required|integer',
        'qty' => 'nullable|integer|min:1',
    ]);

    $menu = Menu::findOrFail($request->menu_id);

    $cart = session()->get('cart', []);

    // 🔥 PAKAI PK ASLI
    $menuId = $menu->id_menu;

    $cart[$menuId] = [
        'menu_id' => $menu->id_menu, // ← WAJIB ADA
        'name'    => $menu->menu_name,
        'price'   => $menu->menu_price,
        'qty'     => ($cart[$menuId]['qty'] ?? 0) + ($request->qty ?? 1),
        'image'   => $menu->menu_image,
        'note'    => $request->note ?? null,
    ];

    session()->put('cart', $cart);

    return back()->with('added', true);
}


    /**
     * =========================
     * UPDATE QTY
     * =========================
     */
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        $menuId = (int) $request->menu_id;

        if (!isset($cart[$menuId])) {
            return back();
        }

        if ($request->qty <= 0) {
            unset($cart[$menuId]);
        } else {
            $cart[$menuId]['qty'] = $request->qty;
        }

        session()->put('cart', $cart);

        return back();
    }

    /**
     * =========================
     * REMOVE ITEM
     * =========================
     */
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        unset($cart[$request->menu_id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Item removed');
    }

    /**
     * =========================
     * CLEAR CART
     * =========================
     */
    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared!');
    }

    /**
     * =========================
     * ADD / UPDATE NOTE
     * =========================
     */
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
