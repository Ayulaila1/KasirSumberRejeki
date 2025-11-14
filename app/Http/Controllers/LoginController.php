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

        $key = 'login-attempts:' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam $seconds detik.",
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            // 🔒 Hanya role admin atau kasir shift 1-3 yang boleh login
            if (
                !in_array($user->email, [
                    'admin12@gmail.com',
                    'shift1@gmail.com',
                    'shift2@gmail.com',
                    'shift3@gmail.com'
                ])
            ) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki izin untuk login ke sistem kasir.'
                ]);
            }

            // ✅ Arahkan ke dashboard sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Kasir berdasarkan shift
            session(['shift' => $user->shift]);
            return redirect()->route('kasir.index')
                ->with('message', 'Selamat datang, Kasir Shift ' . $user->shift);
        }

        RateLimiter::hit($key, 60);
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
