<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Produk;
use Livewire\Component;
use App\Models\LaporanPendapatan;
use Illuminate\Support\Facades\Auth;


class AdminDashboard extends Component
{
    public $halamanSekarang = 'dashboard';
    public $tampilSidebar = false;
    public $tampilModalTransaksi = false;
    public $transaksiTerpilih = null;
    public $tahunPendapatan = '2025';
    public $bulanProdukTerlaris = 'sekarang';
    public $totalPendapatan = 0;
    public $persen = null;
    protected $listeners = ['tutupModal'];


    public function mount()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // total bulan ini
        $this->totalPendapatan = LaporanPendapatan::where('bulan', $bulanIni)
            ->value('total_penjualan') ?? 0;

        // cari bulan terakhir sebelum bulan ini
        $totalLalu = LaporanPendapatan::where('bulan', '<', $bulanIni)
            ->orderBy('bulan', 'desc')
            ->value('total_penjualan');

        if ($totalLalu && $totalLalu != 0) {
            $this->persen = ($this->totalPendapatan - $totalLalu) / $totalLalu * 100;
        } else {
            $this->persen = 0; // aman kalau nggak ada data
        }
    }

    public function totalProduk()
    {
        return Produk::count();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }

    public function toggleSidebar()
    {
        $this->tampilSidebar = !$this->tampilSidebar;
    }

    public function pindahHalaman($halaman)
    {
        $this->halamanSekarang = $halaman;
        $this->tampilSidebar = false;
    }

    public function lihatTransaksi($idTransaksi)
    {
        $this->transaksiTerpilih = $this->ambilDetailTransaksi($idTransaksi);
        $this->tampilModalTransaksi = true;
    }

    public function tutupModal()
    {
        $this->tampilModalTransaksi = false;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    protected function ambilDetailTransaksi($id)
    {
        // Data contoh, nanti bisa diganti query dari database
        $transaksi = [
            'TRX-20250628-001' => [
                'id' => 'TRX-20250628-001',
                'tanggal' => '28 Juni 2025',
                'pelanggan' => 'Pelanggan 1',
                'status' => 'Selesai',
                'items' => [
                    ['produk' => 'Cappuccino', 'harga' => 'Rp 25.000', 'jumlah' => 2, 'subtotal' => 'Rp 50.000'],
                    ['produk' => 'Teh Tarik', 'harga' => 'Rp 15.000', 'jumlah' => 1, 'subtotal' => 'Rp 15.000'],
                    ['produk' => 'Nasi Goreng Spesial', 'harga' => 'Rp 30.000', 'jumlah' => 2, 'subtotal' => 'Rp 60.000'],
                ],
                'total' => 'Rp 125.000'
            ],
        ];

        return $transaksi[$id] ?? null;
    }

    public function ambilDataPendapatan()
    {
        return LaporanPendapatan::whereYear('bulan', $this->tahunPendapatan)
            ->orderByRaw("STR_TO_DATE(bulan, '%Y-%m')") // biar urut Januari–Desember
            ->pluck('total_penjualan')
            ->toArray();
    }

    public function ambilDataProdukTerlaris()
    {
        return $this->bulanProdukTerlaris === 'lalu'
            ? [38, 32, 25, 18, 12]
            : [45, 38, 28, 22, 18];
    }
}
