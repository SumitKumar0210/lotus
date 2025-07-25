<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                if (auth()->user()->type == 'ADMIN') {
                    return redirect(RouteServiceProvider::HOME);
                } elseif (auth()->user()->type == 'BRANCH') {
                    return redirect(RouteServiceProvider::BRANCH);
                } else if (auth()->user()->type == 'WAREHOUSE') {
                    return redirect(RouteServiceProvider::WAREHOUSE);
                }
            }
        }
        return $next($request);
    }
}
