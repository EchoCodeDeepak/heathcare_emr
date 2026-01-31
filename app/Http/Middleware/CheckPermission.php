<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        // If system admin, allow all
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        // Allow doctors to perform common medical-record actions without explicit
        // permission entries: they should be able to create/edit/delete records.
        // This keeps admin-as-view-only behavior while enabling doctors' workflow.
        $doctorAllowed = ['create-medical-records', 'edit-medical-records', 'delete-medical-records', 'export-data'];
        if ($request->user()->isDoctor()) {
            foreach ($permissions as $permission) {
                if (in_array($permission, $doctorAllowed)) {
                    return $next($request);
                }
            }
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        // Avoid redirecting back to dashboard (which may itself require this permission)
        // Redirect to home with error or abort with 403 depending on context.
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
