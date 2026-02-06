<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user has a role assigned
        $role = $user->role;

        if (!$role) {
            return redirect('/login')->with('error', 'Your account does not have a role assigned. Please contact administrator.');
        }

        // Role-based redirection logic
        $roleSlug = $role->slug ?? 'unknown';

        switch ($roleSlug) {
            case 'system-admin':
                return $this->adminDashboard();
            case 'doctor':
                return view('dashboard.doctor');
            case 'nurse':
                return view('dashboard.nurse');
            case 'lab-technician':
                return view('dashboard.lab');
            case 'patient':
                return view('dashboard.patient');
            default:
                // Unknown/custom role - show the general dashboard
                return view('dashboard.index');
        }
    }

    protected function adminDashboard()
    {
        // Prepare chart data for admin dashboard
        $roles = Role::withCount('users')->get();
        $roleLabels = $roles->pluck('name')->toArray();
        $roleData = $roles->pluck('users_count')->toArray();
        $roleColors = ['#0891b2', '#22c55e', '#f59e0b', '#3b82f6', '#ef4444', '#64748b'];

        return view('dashboard.admin', compact(
            'roleLabels',
            'roleData',
            'roleColors'
        ));
    }
}
