<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check if this is an admin request and user is authenticated as admin
                if (($request->is('admin') || $request->is('admin/*')) && $guard === 'admin') {
                    return redirect(RouteServiceProvider::Admin);
                }
                // For non-admin guards, redirect to home
                elseif ($guard !== 'admin') {
                    return redirect(RouteServiceProvider::Home);
                }
            }
        }

        return $next($request);
    }
}
