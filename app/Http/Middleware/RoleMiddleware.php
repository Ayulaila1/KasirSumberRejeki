<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->email, ['admin12@gmail.com', 'kasir56@gmail.com'])) {
            abort(403, 'Tidak diizinkan');
        }

        // Contoh: admin akses semua, kasir cuma penjualan
        if ($user->email === 'kasir56@gmail.com' && !in_array('kasir', $roles)) {
            abort(403, 'Akses hanya untuk kasir');
        }

        return $next($request);
    }
}
