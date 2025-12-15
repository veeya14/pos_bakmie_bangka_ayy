<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $selectedCategory = $request->category;

        if (!$selectedCategory) {
            $firstCategory = Category::orderBy('id_category', 'asc')->first();
            $selectedCategory = $firstCategory ? $firstCategory->id_category : null;
        }

        $menu = $selectedCategory
            ? Menu::where('id_category', $selectedCategory)->get()
            : collect();

        return view('seller.menu', compact('menu', 'categories', 'selectedCategory'));
    }


    /** =====================
     *  STORE MENU
     *===================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'menu_price' => 'required|numeric',
            'menu_status' => 'required|in:available,sold_out',
            'menu_description' => 'nullable|string',
            'menu_image' => 'nullable|image|max:2048',
            'display_status' => 'required|in:active,inactive',
        ]);

        $data['menu_active'] = 1;

        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['menu_image'] = 'image/' . $filename;
        }

        Menu::create($data);

        return redirect()
            ->route('seller.menus.index', ['category' => $data['id_category']])
            ->with('success', 'Menu berhasil ditambahkan.');
    }



    /** =====================
     *  UPDATE MENU
     *===================== */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'menu_name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'menu_price' => 'required|numeric',
            'menu_status' => 'required|in:available,Unavailable',
            'menu_description' => 'nullable|string',
            'menu_image' => 'nullable|image|max:2048',
            'display_status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['menu_image'] = 'image/' . $filename;
        }

        $menu->update($data);

        return redirect()
            ->route('seller.menus.index', ['category' => $data['id_category']])
            ->with('success', 'Menu berhasil diperbarui.');
    }


    /** =====================
     *  DELETE MENU
     *===================== */
    public function destroy(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $categoryId = $menu->id_category;

        $menu->delete();

        return redirect()
            ->route('seller.menus.index', ['category' => $categoryId])
            ->with('success', 'Menu berhasil dihapus.');
    }
}
