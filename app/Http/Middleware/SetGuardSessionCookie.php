<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SetGuardSessionCookie
{
    /**
     * Handle an incoming request.
     *
     * Detects which guard is being used based on route/request
     * and sets the appropriate session cookie name
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = $this->detectGuard($request);
        $guardCookies = config('session.guard_cookies', []);

        if ($guard && isset($guardCookies[$guard])) {
            // Set the session cookie name for this guard
            config([
                'session.cookie' => $guardCookies[$guard],
            ]);
        }

        return $next($request);
    }

    /**
     * Detect which guard should be used for this request
     */
    private function detectGuard(Request $request)
    {
        // Check admin routes
        if ($request->is('admin') || $request->is('admin/*')) {
            return 'admin';
        }

        // Check panel teacher routes
        if ($request->is('*/panel/teacher*')) {
            return 'user';
        }

        // Check panel parent routes
        if ($request->is('*/panel/parent*')) {
            return 'user';
        }

        // Check panel student routes
        if ($request->is('*/panel/student*')) {
            return 'user';
        }

        // Check API routes
        if ($request->is('api/*')) {
            return 'user-api';
        }

        // Default to web
        return 'web';
    }
}
