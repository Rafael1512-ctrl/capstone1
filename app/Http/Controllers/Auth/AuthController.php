<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mapping: input 'email' to column 'name', input 'password' to column 'pass'
        if (Auth::attempt(['name' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'Admin') {
                return redirect()->intended('/starter-template');
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Generate simple ID (T-0000X)
        $count = User::count() + 1;
        $user_id = 'T-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'user_id' => $user_id,
            'name' => $validated['email'], // Column 'name' stores email login
            'email' => $validated['name'], // Column 'email' stores full name
            'pass' => Hash::make($validated['password']),
            'role_id' => 3, // Default role_id for 'User'
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
