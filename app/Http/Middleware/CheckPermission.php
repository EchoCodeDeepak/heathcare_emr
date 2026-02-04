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

        // Permission denied - redirect to dashboard with error
        return redirect('/dashboard')->with('error', 'You do not have permission to access this page. Please contact administrator.');
    }
}
