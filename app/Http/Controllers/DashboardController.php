<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Role-based redirection logic
        switch ($user->role->slug) {
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
                return view('dashboard');
        }
    }
}