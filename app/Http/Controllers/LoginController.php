<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function index()
    {
        return view('livewire.auth.login');
    }

    // 🔹 Tambahkan fungsi ini agar throttle tahu kolom yang digunakan
    public function username()
    {
        return 'email';
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 🔒 Batas percobaan login: 5x per menit
        $key = 'login-attempts:' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam $seconds detik.",
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key); // ✅ Reset hitungan jika berhasil login
            $request->session()->regenerate();

            $user = Auth::user();

            // 🔹 Admin masuk dashboard
            if (is_null($user->shift)) {
                return redirect()->route('admin.dashboard');
            }

            // 🔹 Kasir sesuai shift
            session(['shift' => $user->shift]);
            return redirect()->route('kasir.index')
                ->with('message', 'Selamat datang di Shift ' . $user->shift);
        }

        // ❌ Jika gagal login
        RateLimiter::hit($key, 60); // timeout 60 detik
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login_1')->with('message', 'Berhasil logout.');
    }
}
