<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Check if admin exists
            $admin = Admin::where('email', $credentials['email'])->first();
            if (!$admin) {
                Log::warning('Admin login attempt with non-existent email: ' . $credentials['email']);
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'These credentials do not match our records.',
                    ]);
            }

            // Attempt to authenticate
            if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                
                Log::info('Admin logged in successfully: ' . $admin->email);
                return redirect()->intended(route('admin.dashboard'));
            }

            Log::warning('Failed admin login attempt for email: ' . $credentials['email']);
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'These credentials do not match our records.',
                ]);
        } catch (\Exception $e) {
            Log::error('Admin login error: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'An error occurred during login. Please try again.',
                ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->forget('admin_session');
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.admin-forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
