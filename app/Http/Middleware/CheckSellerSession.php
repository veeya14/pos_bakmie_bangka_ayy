<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSellerSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('seller_id')) {
            return redirect()->route('seller.login');
        }

        return $next($request);
    }
}
