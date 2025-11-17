<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Tampilkan semua pembayaran.
     */
    public function index()
    {

        $payments = Payment::with('cart')->latest()->get();

        return view('Seller.payments.index', compact('payments'));
    }

    /**
     * Tampilkan detail pembayaran tertentu.
     */
    public function show($id)
    {

        $payment = Payment::with('cart')->findOrFail($id);

        return view('Seller.payments.show', compact('payment'));
    }
}
