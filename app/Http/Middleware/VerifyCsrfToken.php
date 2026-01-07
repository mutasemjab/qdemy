<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add specific localized routes if needed
        'en/register',
        'ar/register',
        'en/login',
        'ar/login',
        'en/password/reset',
        'ar/password/reset',
        'api/*',
        // Exam routes for mobile WebView (they handle their own authentication via _user_id)
        '*/exam/*/save-answer-ajax',
        '*/exam/*/finish',
        '*/exam/*/start',
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        // Get the current path
        $path = $request->path();
        
        // Check if path matches localized patterns
        $localizedPatterns = [
            '*/register',
            '*/login', 
            '*/password/reset',
        ];
        
        foreach ($localizedPatterns as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }
        
        return parent::inExceptArray($request);
    }
}
