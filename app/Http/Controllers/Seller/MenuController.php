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
        $promoCategory = Category::where('name', 'Promo')->first();
        $selectedCategory = $promoCategory ? $promoCategory->id_category : null;
    }


    $menu = $selectedCategory ? Menu::where('id_category', $selectedCategory)->get() : collect();

    return view('seller.menu', compact('menu', 'categories', 'selectedCategory'));
}



    // Simpan menu baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'menu_price' => 'required|numeric',
            'menu_status' => 'required|in:available,sold_out',
            'menu_description' => 'nullable|string',
            'menu_image' => 'nullable|image|max:2048',
        ]);

        // Simpan foto langsung ke public/image
        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['menu_image'] = 'image/'.$filename;
        }

        $menu = Menu::create($data);

        // redirect ke kategori menu yang sama
        return redirect()->route('seller.menus.index', ['category' => $data['id_category']])
                         ->with('success', 'Menu berhasil ditambahkan.');
    }

    // Update menu
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'menu_name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'menu_price' => 'required|numeric',
            'menu_status' => 'required|in:available,sold_out',
            'menu_description' => 'nullable|string',
            'menu_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['menu_image'] = 'image/'.$filename;
        }

        $menu->update($data);

        // redirect ke kategori menu yang sama
        return redirect()->route('seller.menus.index', ['category' => $data['id_category']])
                         ->with('success', 'Menu berhasil diperbarui.');
    }

    // Hapus menu
    public function destroy(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $categoryId = $menu->id_category; // simpan dulu kategori sebelum hapus
        $menu->delete();

        // redirect ke kategori menu yang sama
        return redirect()->route('seller.menus.index', ['category' => $categoryId])
                         ->with('success', 'Menu berhasil dihapus.');
    }
}
