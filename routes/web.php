<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Main;
use App\Http\Controllers\Loginpage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
// use App\Livewire\Dashboard\Dashboardlayout;

Route::get('/login_1', [LoginController::class, 'index'])->name('login');


Route::get('/registermiaw', Register::class)->name('registermiaw');

// Route::get('/', HomeFrontend::class)->name('home.frontend');
// Route::get('/about', AboutFrontend::class)->name('about.frontend');
// Route::get('/features', FeaturesFrontend::class)->name('features.frontend');
// Route::get('/team', TeamFrontend::class)->name('team.frontend');
// Route::get('/pricing', PricingFrontend::class)->name('pricing.frontend');
// Route::get('/contact', ContactFrontend::class)->name('contact.frontend');



Route::post('/roarr', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware([
    'auth'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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


