<?php

namespace App\Http\Middleware;

use App\Membership;
use Closure;
use Session;

class CheckSuscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( $request->user()->hasRole('superadmin') ) {
            return $next($request);
        }

        $memberships = Membership::select('stripe_id')->where('status', 1)->pluck('stripe_id')->toArray();

        $user = $request->user();
        if ( $user->hasPaymentMethod() ) {
            if ( $user->subscribedToPlan($memberships, 'main') ) {
                return $next($request);
            }

            Session::flash('flash_message', __('- Activa tu membresia para poder disfrutar de los beneficios de TelcoGes.'));
            Session::flash('flash_type', 'alert-danger');
            return redirect()->route('home');
        }

        Session::flash('flash_message', __('- Debes agrear un método de pago.'));
        Session::flash('flash_type', 'alert-danger');
        return redirect()->route('home');
    }
}
