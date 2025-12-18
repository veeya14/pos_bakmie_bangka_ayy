<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    Category::create([
        'name' => $request->name,
    ]);

    return redirect()->route('seller.menus.index')
        ->with('success', 'Category added');
}

}
