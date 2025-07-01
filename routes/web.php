<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\AdminDashboard;
use App\Livewire\BahanComponent;
use App\Livewire\ProdukComponent;
use App\Livewire\SupplierComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return redirect('/login_1');
});
// Public Routes
Route::middleware('guest')->group(function () {
    Route::get('/login_1', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.attempt');
    Route::get('/registermiaw', Register::class)->name('registermiaw');
});

// Logout Route (accessible to authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/roarr', [LoginController::class, 'authenticate']);


// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Main Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');

    // Other Dashboard Pages
    // Route::get('/produk', ProdukComponent::class)->name('produk.index');


    Route::get('/penjualan', function () {
        return view('penjualan');
    })->name('penjualan');

    Route::get('/laporan', function () {
        return view('laporan');
    })->name('laporan');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    // Route::get('/dashboard', Main::class)->name('dashboard.index');
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/produk', ProdukComponent::class)->name('produk.index');
    Route::get('/supplier', SupplierComponent::class)->name('supplier.index');
    Route::get('/bahan', BahanComponent::class)->name('bahan.index');
    // Route::get('produkracikan/{id}', ProdukRacikanComponent::class);


    // dll
});

// Optional: Frontend Routes (commented out as per your original)
// Route::get('/', HomeFrontend::class)->name('home.frontend');
// Route::get('/about', AboutFrontend::class)->name('about.frontend');
// Route::get('/features', FeaturesFrontend::class)->name('features.frontend');
// Route::get('/team', TeamFrontend::class)->name('team.frontend');
// Route::get('/pricing', PricingFrontend::class)->name('pricing.frontend');
// Route::get('/contact', ContactFrontend::class)->name('contact.frontend');
