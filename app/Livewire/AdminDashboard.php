<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Produk;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Penjualan;
use App\Models\LaporanPendapatan;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    public $halamanSekarang = 'dashboard';
    public $tampilSidebar = false;
    public $tampilModalTransaksi = false;
    public $transaksiTerpilih = null;

    public $tahunPendapatan;
    public $daftarTahun = [];
    public $bulanProdukTerlaris = 'sekarang';
    public $produkMenipis = [];
    public $totalPendapatan = 0;
    public $persen = null;
    public $dataPendapatan = [];
    public $dataTabel = [];
    public $judulTabel = '';

    public $transaksiTerakhir = [];
    protected $listeners = ['tutupModal'];

    public function mount()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        $this->daftarTahun = LaporanPendapatan::selectRaw("LEFT(bulan, 4) as tahun")
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun')
            ->toArray();

        $this->tahunPendapatan = end($this->daftarTahun) ?: date('Y');

        $this->totalPendapatan = LaporanPendapatan::where('bulan', $bulanIni)
            ->value('total_penjualan') ?? 0;

        $totalLalu = LaporanPendapatan::where('bulan', '<', $bulanIni)
            ->orderBy('bulan', 'desc')
            ->value('total_penjualan');

        $this->persen = ($totalLalu && $totalLalu != 0)
            ? ($this->totalPendapatan - $totalLalu) / $totalLalu * 100
            : 0;

        $this->dataPendapatan = $this->ambilDataPendapatan();
        $this->dispatch('renderChartPendapatan', $this->dataPendapatan);

        $this->cekStokMenipis();
        $this->ambilTransaksiTerakhir();
    }

    public function kembaliDashboard()
    {
        $this->halamanSekarang = 'dashboard';
    }

    public function ambilTransaksiTerakhir($limit = 5)
    {
        $this->transaksiTerakhir = Penjualan::with(['penjualandtl', 'hold'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                if ($item->penjualanDtl()->count() > 0) {
                    $item->status = 'lunas';
                    $item->sisa_pembayaran = 0;
                } elseif ($item->holds->count() > 0) {
                    $item->status = 'bon';
                    $item->sisa_pembayaran = $item->total;
                } else {
                    $item->status = 'pending';
                    $item->sisa_pembayaran = $item->total;
                }
                $item->nama_pelanggan = $item->customer_name;
                return $item;
            });
    }

    public function updatedTahunPendapatan()
    {
        $this->dataPendapatan = $this->ambilDataPendapatan();
        $this->dispatch('renderChartPendapatan', $this->dataPendapatan);
    }

    public function totalProduk()
    {
        return Produk::count();
    }

    public function totalSupplier()
    {
        return Supplier::count();
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
        $item = Penjualan::with('penjualandtls', 'holds')->find($id);
        if (!$item)
            return null;

        if ($item->penjualandtls->count() > 0) {
            $item->status = 'lunas';
            $item->sisa_pembayaran = 0;
        } elseif ($item->holds->count() > 0) {
            $item->status = 'bon';
            $item->sisa_pembayaran = $item->total;
        } else {
            $item->status = 'pending';
            $item->sisa_pembayaran = $item->total;
        }
        $item->nama_pelanggan = $item->customer_name;
        return $item;
    }


    public function tampilkanData($tipe)
    {
        $this->halamanSekarang = 'detail';
        $this->judulTabel = ucfirst(str_replace('-', ' ', $tipe));

        switch ($tipe) {
            case 'total-pendapatan':
                $this->dataTabel = LaporanPendapatan::orderBy('bulan', 'desc')->get();
                break;

            case 'total-produk':
                $this->dataTabel = Produk::orderBy('nama', 'asc')->get();
                break;

            case 'stok-menipis':
                $this->dataTabel = $this->produkMenipis;
                break;

            case 'total-supplier':
                $this->dataTabel = Supplier::orderBy('nama', 'asc')->get();
                break;

            case 'transaksi-terakhir':
                $this->dataTabel = Penjualan::with(['penjualandtls', 'holds'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($item) {
                        if ($item->penjualandtls->count() > 0) {
                            $item->status = 'lunas';
                            $item->sisa_pembayaran = 0;
                        } elseif ($item->holds->count() > 0) {
                            $item->status = 'bon';
                            $item->sisa_pembayaran = $item->total;
                        } else {
                            $item->status = 'pending';
                            $item->sisa_pembayaran = $item->total;
                        }
                        $item->nama_pelanggan = $item->customer_name;
                        return $item;
                    });
                break;
        }
    }

    public function ambilDataPendapatan()
    {
        $data = LaporanPendapatan::where('bulan', 'like', $this->tahunPendapatan . '%')
            ->orderBy('bulan')
            ->pluck('total_penjualan', 'bulan')
            ->toArray();

        $hasil = [];
        for ($i = 1; $i <= 12; $i++) {
            $key = sprintf('%s-%02d', $this->tahunPendapatan, $i);
            $hasil[] = $data[$key] ?? 0;
        }

        return $hasil;
    }

    public function ambilDataProdukTerlaris()
    {
        return $this->bulanProdukTerlaris === 'lalu'
            ? [38, 32, 25, 18, 12]
            : [45, 38, 28, 22, 18];
    }

    public function cekStokMenipis($batas = 3)
    {
        $this->produkMenipis = Produk::with('produkDetails.bahan')
            ->get()
            ->filter(fn($produk) => $produk->stok_tersedia <= $batas)
            ->sortBy('stok_tersedia');
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
