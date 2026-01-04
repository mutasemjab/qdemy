<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
     public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('lang', 'ar'); // Default to 'ar' if 'lang' header is not present
        app()->setLocale($locale);

        return $next($request);
    }
}

