<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('menu');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Admin users should always go to admin dashboard
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            
            // Regular users always go to menu
            return redirect()->route('menu');
        }

        return back()->withErrors([
            'phone_number' => 'Invalid credentials.',
        ])->onlyInput('phone_number');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('menu');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false, // Regular users can only register as customers
        ]);

        Auth::login($user);
        return redirect()->route('menu'); // Always redirect to menu after registration
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
