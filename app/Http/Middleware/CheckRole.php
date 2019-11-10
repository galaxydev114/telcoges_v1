<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $r = explode('|', $roles);
        if (! $request->user()->hasAnyRole($r)) {
            return redirect('home');
        }
        
        return $next($request);
    }
}
