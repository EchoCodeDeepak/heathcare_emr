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
        $user = $request->user();
        $role = $user->role;

        if (!$role) {
            return redirect('/login')->with('error', 'User does not have a role assigned. Please contact administrator.');
        }

        $userRole = $role->slug ?? 'unknown';

        // Check if user's role is in the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // For unknown roles not in the allowed list, check if they're custom roles
        // that might have permissions for other routes - deny access
        return redirect('/dashboard')->with('error', 'Unauthorized access. Your role does not have permission to access this resource.');
    }
}
