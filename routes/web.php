<?php

use App\Livewire\Kasir;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\AdminDashboard;
use App\Livewire\BahanComponent;
use App\Livewire\Dashboard\Main;
<<<<<<< Updated upstream
use App\Livewire\KasirComponent;
use App\Livewire\ProdukComponent;
use App\Livewire\PenggunaComponent;
use App\Livewire\SupplierComponent;
use App\Livewire\PembelianComponent;
use App\Livewire\PenjualanComponent;
use App\Livewire\PengaturanComponent;
// use App\Http\Livewire\ProfilComponent;
=======
use App\Livewire\ProdukComponent;
use App\Http\Controllers\Loginpage;
>>>>>>> Stashed changes
use Illuminate\Support\Facades\Route;
use App\Livewire\PembeliandtlComponent;
use App\Livewire\ReturTitipanComponent;
use App\Livewire\ProdukRacikanComponent;
use App\Http\Controllers\LoginController;
<<<<<<< Updated upstream
use App\Http\Livewire\ProfilComponent;
use App\Livewire\LaporanPenjualanComponent;
use App\Livewire\LaporanPendapatanComponent;

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
    Route::get('/pembelian', PembelianComponent::class)->name('pembelian.index');
    Route::get('/penjualan', PenjualanComponent::class)->name('penjualan.index');
    Route::get('/kasir', KasirComponent::class)->name('kasir.index');
    Route::get('/bahan', BahanComponent::class)->name('bahan.index');
    Route::get('/returtitipan', ReturTitipanComponent::class)->name('returtitipan.index');
    Route::get('/laporan-penjualan', LaporanPenjualanComponent::class)->name('laporan.penjualan');
    Route::get('/laporan-pendapatan', LaporanPendapatanComponent::class)->name('laporan.pendapatan');
    Route::get('/produkracikan/{id}', ProdukRacikanComponent::class)->name('produkracikan.index');
    Route::get('/pembeliandtl/{id}', PembeliandtlComponent::class)->name('pembeliandtl.index');
    Route::get('/retur-produk/{idproduk}', ReturTitipanComponent::class)->name('retur.produk');
    Route::get('/cetakPembelian/{idPage}', [PembeliandtlComponent::class, 'cetakLaporan'])->name('cetakPembelian');
    Route::get('/pengguna', PenggunaComponent::class)->name('pengguna.index');
    Route::get('/pengaturan', PengaturanComponent::class)->name('pengaturan.index');
    // Route::get('/profil', ProfilComponent::class)->name('profil.index');
=======
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
// use App\Livewire\Dashboard\Dashboardlayout;

Route::get('/', function () {
    return redirect('/login_1');
});

Route::get('/login_1', [LoginController::class, 'index'])->name('login');


Route::get('/registermiaw', Register::class)->name('registermiaw');

// Route::get('/', HomeFrontend::class)->name('home.frontend');
// Route::get('/about', AboutFrontend::class)->name('about.frontend');
// Route::get('/features', FeaturesFrontend::class)->name('features.frontend');
// Route::get('/team', TeamFrontend::class)->name('team.frontend')rontend');
// Route::get('/contact', ContactFrontend::class)->name('contact.frontend');


;
// Route::get('/pricing', PricingFrontend::class)->name('pricing.f
Route::post('/roarr', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    // Route::get('/dashboard', Main::class)->name('dashboard.index');
    Route::get('/dashboard', Main::class)->name('dashboard');
    Route::get('/produk', ProdukComponent::class)->name('produk.index');

    // dll
});

// Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
//     Route::get('/penjualan', PenjualanComponent::class)->name('penjualan.index');
// });


>>>>>>> Stashed changes





    // dll
});

// Optional: Frontend Routes (commented out as per your original)
// Route::get('/', HomeFrontend::class)->name('home.frontend');
// Route::get('/about', AboutFrontend::class)->name('about.frontend');
// Route::get('/features', FeaturesFrontend::class)->name('features.frontend');
// Route::get('/team', TeamFrontend::class)->name('team.frontend');
// Route::get('/pricing', PricingFrontend::class)->name('pricing.frontend');
// Route::get('/contact', ContactFrontend::class)->name('contact.frontend');
