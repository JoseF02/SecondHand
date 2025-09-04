<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $total = 0;
        foreach ($cart as $id => $qty) {
            $total += ($products[$id]->price ?? 0) * $qty;
        }
        return view('checkout.show', compact('cart', 'products', 'total'));
    }

    public function process(Request $request)
    {
        session()->forget('cart');
        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }
}


