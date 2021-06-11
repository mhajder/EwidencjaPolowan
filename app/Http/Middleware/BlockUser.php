<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class BlockUser
 * @package App\Http\Middleware
 */
class BlockUser
{
    /**
     * Handle an incoming request and check if user is blocked.
     * Logout and redirect to the login page if blocked.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->disabled == 1) {
            auth()->logout();

            return redirect()->route('login')->with('blocked', __('auth.account_blocked'));
        }

        return $next($request);
    }
}
