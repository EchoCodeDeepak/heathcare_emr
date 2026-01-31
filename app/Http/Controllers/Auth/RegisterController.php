<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Default validation for all users
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // If user is admin, allow role selection
        if (Auth::check() && Auth::user()->isAdmin()) {
            $validationRules['role_id'] = ['required', 'exists:roles,id'];
        }

        $request->validate($validationRules);

        // Determine role_id
        $role_id = Auth::check() && Auth::user()->isAdmin() 
            ? $request->role_id 
            : 5; // Default to patient for public registration

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role_id,
        ]);

        event(new Registered($user));

        // Only auto-login if registering for self (not admin creating user)
        if (!Auth::check()) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } else {
            // Admin creating user
            return redirect()->route('users.index')->with('success', 'User created successfully!');
        }
    }
}