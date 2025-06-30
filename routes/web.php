<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\AdminDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Public Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.attempt');
    Route::get('/register', Register::class)->name('register');
});

// Logout Route (accessible to authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Main Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // Other Dashboard Pages
    Route::get('/produk', function () {
        return view('produk');
    })->name('produk');

    Route::get('/penjualan', function () {
        return view('penjualan');
    })->name('penjualan');

    Route::get('/laporan', function () {
        return view('laporan');
    })->name('laporan');
});

// Optional: Frontend Routes (commented out as per your original)
// Route::get('/', HomeFrontend::class)->name('home.frontend');
// Route::get('/about', AboutFrontend::class)->name('about.frontend');
// Route::get('/features', FeaturesFrontend::class)->name('features.frontend');
// Route::get('/team', TeamFrontend::class)->name('team.frontend');
// Route::get('/pricing', PricingFrontend::class)->name('pricing.frontend');
// Route::get('/contact', ContactFrontend::class)->name('contact.frontend');
