<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function loginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists first
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->is_banned) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda telah dibanned oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    // Show register form
    public function registerForm()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat dan Anda telah login.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 