<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class SellerAuthController extends Controller
{
    // === SHOW LOGIN PAGE ===
    public function showLogin()
    {
        return view('seller.login');
    }

    // === SHOW REGISTER PAGE ===
    public function showRegister()
    {
        return view('seller.register');
    }

    // === REGISTER SELLER ===
    public function register(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|unique:sellers,email',
            'password'  => 'required|min:6|confirmed'
        ]);

        Seller::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('seller.login')->with('success', 'Akun berhasil dibuat, silakan login.');
    }

    // === LOGIN HANDLER ===
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $seller = Seller::where('email', $request->email)->first();

        if (!$seller || !Hash::check($request->password, $seller->password)) {
            return back()->withErrors(['login' => 'Email atau password salah']);
        }

        $request->session()->put('seller_id', $seller->id);

        return redirect()->route('seller.dashboard');
    }

    // === LOGOUT ===
    public function logout(Request $request)
    {
        $request->session()->forget('seller_id');
        return redirect()->route('seller.login');
    }


}
