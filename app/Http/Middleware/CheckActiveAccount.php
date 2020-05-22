<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActiveAccount
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
        if (Auth::check()) {
            if (!Auth::user()->active) {
                Auth::logout();
                return redirect(RouteServiceProvider::HOME);
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
        return $next($request);
    }
}
