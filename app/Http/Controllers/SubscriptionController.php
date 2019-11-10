<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membership;
use Session;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $memberships = Membership::where('status', 1)->get();

        return view('subscriptions.index', ['memberships' => $memberships]);
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->hasDefaultPaymentMethod() ) {
            Session::flash('flash_message', __('- Debe configurar un método de pago primero.'));
            Session::flash('flash_type', 'alert-danger');
            return redirect()->route('paymentMethods.index');
        }

		auth()->user()->newSubscription('main', $request->stripe_id)->add();
		return redirect()->route('subscriptions');
    }
}
