<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultPaymentMethodController extends Controller
{
    public function update(Request $request)
    {
    	auth()->user()->updateDefaultPaymentMethod($request->stripePaymentMethod);
    	auth()->user()->updateDefaultPaymentMethodFromStripe($request->stripePaymentMethod);
    	return redirect()->route('paymentMethods.index');
    }
}
