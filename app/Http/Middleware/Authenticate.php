<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Check if user can be authenticated via _user_id parameter (for mobile webview)
        if (!$this->auth->check(...$guards)) {
            $userId = $request->get('_user_id') ?? $request->query('_user_id');

            if ($userId) {
                $user = \App\Models\User::find($userId);
                if ($user && $user->role_name === 'student') {
                    // Authenticate the user for this request
                    $this->auth->guard('user')->login($user);
                    session(['is_mobile_app' => true, 'mobile_user_id' => $userId]);
                }
            }
        }

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                //redirect to admin login
                return route('admin.login');
            } else {
                return route('user.login');
            }
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json(['message' => 'Unauthorized'], 401));
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}
