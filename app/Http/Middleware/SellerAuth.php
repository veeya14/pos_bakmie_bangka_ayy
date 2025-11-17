<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('seller_id')) {
            return redirect('seller/login'); 
        }

        return $next($request);
    }
}
