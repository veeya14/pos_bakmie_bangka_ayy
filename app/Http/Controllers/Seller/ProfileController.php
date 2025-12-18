<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $seller = Seller::find(session('seller_id'));

        if (!$seller) {
            return redirect()->route('seller.login');
        }

        return view('seller.profile', compact('seller'));
    }

    public function updateProfile(Request $request)
{
    $seller = Seller::find(session('seller_id'));
    
if ($request->hasFile('photo') && !$request->has('name')) {

    if ($seller->photo && file_exists(storage_path('app/public/' . $seller->photo))) {
        unlink(storage_path('app/public/' . $seller->photo));
    }

    $path = $request->file('photo')->store('profile', 'public');
    $seller->photo = $path;
    $seller->save();

    return back()->with('success', 'Photo updated');
}


    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:sellers,email,' . $seller->id,
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // upload photo kalau ada
    if ($request->hasFile('photo')) {

        // hapus foto lama (optional tapi rapi)
        if ($seller->photo && file_exists(storage_path('app/public/' . $seller->photo))) {
            unlink(storage_path('app/public/' . $seller->photo));
        }

        $path = $request->file('photo')->store('profile', 'public');
        $seller->photo = $path;
    }

    $seller->name  = $request->name;
    $seller->email = $request->email;
    $seller->save();

    return back()->with('success', 'Profile updated successfully.');
}

    public function updatePassword(Request $request)
    {
        $seller = Seller::find(session('seller_id'));

        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);

        if (!Hash::check($request->current_password, $seller->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $seller->password = Hash::make($request->password);
        $seller->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
