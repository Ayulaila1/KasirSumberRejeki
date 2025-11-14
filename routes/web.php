<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Models\Penjualan;

// Livewire Components
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\AdminDashboard;
use App\Livewire\Dashboard\Main;
use App\Livewire\ProdukComponent;
use App\Livewire\SupplierComponent;
use App\Livewire\PembelianComponent;
use App\Livewire\PenjualanComponent;
use App\Livewire\KasirComponent;
use App\Livewire\HoldComponent;
use App\Livewire\BahanComponent;
use App\Livewire\PengeluaranComponent;
use App\Livewire\KasMutasiComponent;
use App\Livewire\PenggunaComponent;
use App\Livewire\PengaturanComponent;
use App\Livewire\ReturTitipanComponent;
use App\Livewire\PembeliandtlComponent;
use App\Livewire\ProdukRacikanComponent;
use App\Livewire\LaporanPenjualanComponent;
use App\Livewire\LaporanPendapatanComponent;
use App\Livewire\LaporanPembukuanComponent;

// ============================
// 🔹 ROUTE GUEST / LOGIN
// ============================
Route::get('/', fn() => redirect('/login_1'));

Route::middleware('guest')->group(function () {
    Route::get('/login_1', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.attempt');
    // Route::get('/registermiaw', Register::class)->name('registermiaw');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/roarr', [LoginController::class, 'authenticate']); // sepertinya buat debug login alternatif

// ============================
// 🔹 ROUTE UNTUK ADMIN DAN SHIFT
// ============================
Route::middleware('auth')->group(function () {

    // 📊 Dashboard & Master Data
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/produk', ProdukComponent::class)->name('produk.index');
    Route::get('/supplier', SupplierComponent::class)->name('supplier.index');
    Route::get('/bahan', BahanComponent::class)->name('bahan.index');
    Route::get('/pengguna', PenggunaComponent::class)->name('pengguna.index');
    Route::get('/pengaturan', PengaturanComponent::class)->name('pengaturan.index');

    // 🛒 Transaksi
    Route::get('/pembelian', PembelianComponent::class)->name('pembelian.index');
    Route::get('/pembeliandtl/{id}', PembeliandtlComponent::class)->name('pembeliandtl.index');
    Route::get('/penjualan', PenjualanComponent::class)->name('penjualan.index');
    Route::get('/kasir', KasirComponent::class)->name('kasir.index');
    Route::get('/kasir/resume/{id}', KasirComponent::class)->name('kasir.resume');
    Route::get('/hold', HoldComponent::class)->name('hold.index');
    Route::get('/returtitipan', ReturTitipanComponent::class)->name('returtitipan.index');
    Route::get('/retur-produk/{idproduk}', ReturTitipanComponent::class)->name('retur.produk');
    Route::get('/produkracikan/{id}', ProdukRacikanComponent::class)->name('produkracikan.index');
    Route::get('/pengeluaran', PengeluaranComponent::class)->name('pengeluaran.index');
    Route::get('/kas-mutasi', KasMutasiComponent::class)->name('kas-mutasi');

    // 🧾 Laporan
    Route::get('/laporan-penjualan', LaporanPenjualanComponent::class)->name('laporan.penjualan');
    Route::get('/laporan-pendapatan', LaporanPendapatanComponent::class)->name('laporan.pendapatan');
    Route::get('/laporan/pembukuan', LaporanPembukuanComponent::class)->name('laporan.pembukuan');


    // 📄 Export / Cetak PDF & Excel - Kas Mutasi
    Route::get('/laporan/kasmutasi/pdf', [LaporanController::class, 'cetakKasMutasiPDF'])
        ->name('laporan.kasmutasi.pdf');
    Route::get('/laporan/kasmutasi/excel', [LaporanController::class, 'exportKasMutasiExcel'])
        ->name('laporan.kasmutasi.excel');

    // 📠 Cetak Pembelian
    Route::get('/cetakPembelian/{idPage}', [PembeliandtlComponent::class, 'cetakLaporan'])->name('cetakPembelian');

    // 🧾 Cetak Struk Kasir
    Route::get('/print/struk/{id}', function ($id) {
        $penjualan = Penjualan::with('detail.produk')->findOrFail($id);

        $receiptData = [
            'printerType' => 'bluetooth',
            'storeName' => 'Cafe Sumber Rejeki',
            'storeAddress' => 'Jl. Mawar No. 10, Bandung',
            'storePhone' => '0812-3456-7890',
            'number' => $penjualan->nomor ?? 'TRX-' . $penjualan->id,
            'date' => $penjualan->created_at->format('d/m/Y H:i'),
            'table' => $penjualan->meja ?? '-',
            'customer' => $penjualan->pelanggan ?? '-',
            'items' => $penjualan->detail->map(fn($d) => [
                'name' => $d->produk->nama,
                'quantity' => $d->qty,
                'price' => $d->harga
            ]),
            'total' => $penjualan->total,
            'cash' => $penjualan->tunai,
            'change' => $penjualan->kembalian,
        ];

        return view('layouts.printkasir', $receiptData);
    })->name('print.struk');
});
