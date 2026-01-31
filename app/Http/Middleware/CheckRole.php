<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        // Load the role relationship if not already loaded
        $user = $request->user()->load('role');

        if (!$user->role) {
            return redirect('/dashboard')->with('error', 'User does not have a role assigned.');
        }

        $userRole = $user->role->slug;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
}
