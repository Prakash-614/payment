<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the admin login view.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.websites.index');
        }

        return view('admin.auth.login');
    }

    /**
     * Process the admin login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.websites.index'))
                ->with('status', 'Welcome back, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    /**
     * Log the admin out and invalidate session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('status', 'You have been successfully logged out.');
    }
}