<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Stripe\Customer;
use App\Membership;
use Stripe\Charge;
use Stripe\Stripe;
use App\Payment;
use Session;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
    	return view('memberships.index', [ 'memberships' => Membership::all() ]);
    }

    public function create(Request $request)
    {
    	return view('memberships.create');
    }

    public function edit(Request $request)
    {
        return view('memberships.edit', ['membership' => Membership::find($request->membership_id)]);
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40', 'unique:memberships,name'],
            'frequency' => ['required', 'string', 'in:monthly,anual'],
            'price' => ['required', 'numeric'],
            'tax_rate' => ['required', 'numeric']
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $iva = $request->price * ($request->tax_rate / 100);

        $membership = Membership::create([
        	'name' => $request->name,
        	'frequency' => $request->frequency,
        	'price' => $request->price,
        	'iva' => $iva,
            'tax_rate' => $request->tax_rate
        ]);

        if ($membership) {
        	Session::flash('flash_message', __('+ Membresia creada.'));
        	Session::flash('flash_type', 'alert-success');
        	return redirect()->route('memberships.index');
        }

        Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo 2.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40', 'unique:memberships,name,'.$request->membership_id],
            'frequency' => ['required', 'string', 'in:monthly,anual'],
            'price' => ['required', 'numeric'],
            'tax_rate' => ['required', 'numeric']
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $iva = $request->price * ($request->tax_rate / 100);

        $membership = Membership::find($request->membership_id);

        $flag = $membership->update([
            'name' => $request->name,
            'frequency' => $request->frequency,
            'price' => $request->price,
            'iva' => $iva,
            'tax_rate' => $request->tax_rate,
            'stripe_id' => $request->stripe_id,
        ]);

        if ($flag) {
            Session::flash('flash_message', __('+ Membresia actualizada.'));
            Session::flash('flash_type', 'alert-success');
            return redirect()->route('memberships.index');
        }

        Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo 2.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }

    public function delete(Request $request)
    {
    	if ( Membership::where('id', $request->membership_id)->delete() ) {
    		Session::flash('flash_message', __('+ Membresia eliminada.'));
        	Session::flash('flash_type', 'alert-success');
        	return redirect()->route('memberships.index');
    	}

    	Session::flash('flash_message', __('- Error, intente de más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return back();
    }

    public function status(Request $request)
    {
        Membership::where('id', $request->membership_id)->update(['status' => !$request->status]);

        return response()->json([
            'success' => 'success'
        ], 200);
    }

    public function pay(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source'  => $request->stripeToken
            ));

            $payment = Payment::find($request->payment_id);

            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount'   => (($payment->sub_total + $payment->tax) * 100),
                'currency' => 'eur'
            ));

            $payment->status = 'payed';
            $payment->save();
            return redirect()->route('organizations.payments');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function paymentInvoice(Request $request)
    {
        return view('memberships.payment_invoice', ['payment' => Payment::find($request->payment_id)]);
    }
}
