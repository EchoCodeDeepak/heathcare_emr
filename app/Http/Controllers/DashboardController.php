<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return view('dashboard.admin');
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
}
