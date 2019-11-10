<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
    	$paymentMethods = auth()->user()->paymentMethods();
    	return view('payment_methods.index', ['paymentMethods' => $paymentMethods]);
    }

    public function create(Request $request)
    {
    	return view('payment_methods.create', [ 'intent' => auth()->user()->createSetupIntent() ]);
    }

    public function store(Request $request)
    {
    	auth()->user()->addPaymentMethod($request->stripePaymentMethod);
    	return auth()->user()->updateDefaultPaymentMethodFromStripe($request->stripePaymentMethod);
    }
}
