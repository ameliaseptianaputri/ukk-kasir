<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login'); // Pastikan ada file 'login.blade.php'
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Log::info('User berhasil login:', ['user' => Auth::user()]);
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
            }

            // Role tidak dikenali
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Role tidak dikenali.']);
        }

        Log::warning('Login gagal', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}
