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
     * and sets the appropriate session cookie name BEFORE session starts
     */
    public function handle(Request $request, Closure $next)
    {
        // هذا الـ middleware يجب أن يعمل قبل StartSession
        // لكن يتم استدعاؤه في global، لذا نحتاج نهجاً مختلفاً

        // بدلاً من تغيير config هنا، سنخزن معلومة الـ guard في request
        // ثم يتم استخدامها في StartSession middleware
        $guard = $this->detectGuard($request);
        $request->attributes->set('auth_guard', $guard);

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
