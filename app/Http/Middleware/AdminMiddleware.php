<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // If the user is logged in and is an admin, allow access
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // Redirect non-admin users to the access-denied page or another custom page
        return redirect('/admin.login');
    }
}
