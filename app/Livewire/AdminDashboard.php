<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Produk;
use Livewire\Component;
use App\Models\Supplier;
// 🔽 1. Tambahkan model Penjualan
use App\Models\Penjualan;
// 🔽 1.1 Tambahkan model Pembayaran untuk cek bon
use App\Models\Pembayaran; // Asumsi ada model Pembayaran atau sejenisnya
use App\Models\LaporanPendapatan;
use Illuminate\Support\Facades\Auth;
// 🔽 1.2 Import DB untuk query lanjutan
// use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $halamanSekarang = 'dashboard';
    public $tampilSidebar = false;
    public $tampilModalTransaksi = false;
    public $transaksiTerpilih = null;

    public $tahunPendapatan;
    public $daftarTahun = []; // daftar tahun otomatis dari database
    public $bulanProdukTerlaris = 'sekarang';
    public $produkMenipis = [];
    public $totalPendapatan = 0;
    public $persen = null;
    public $dataPendapatan = [];
    public $dataTabel = [];
    public $judulTabel = '';

    // 🔽 2. Tambahkan properti untuk menyimpan data transaksi
    public $transaksiTerakhir = [];
    protected $listeners = ['tutupModal'];

    public function mount()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // 🔹 Ambil semua tahun yang ada di tabel laporan_pendapatan
        $this->daftarTahun = LaporanPendapatan::selectRaw("LEFT(bulan, 4) as tahun")
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun')
            ->toArray();

        // 🔹 Kalau ada data, ambil tahun terakhir (terbaru) sebagai default
        $this->tahunPendapatan = end($this->daftarTahun) ?: date('Y');

        // 🔹 Hitung total pendapatan bulan ini
        $this->totalPendapatan = LaporanPendapatan::where('bulan', $bulanIni)
            ->value('total_penjualan') ?? 0;

        // 🔹 Bandingkan dengan bulan sebelumnya
        $totalLalu = LaporanPendapatan::where('bulan', '<', $bulanIni)
            ->orderBy('bulan', 'desc')
            ->value('total_penjualan');

        $this->persen = ($totalLalu && $totalLalu != 0)
            ? ($this->totalPendapatan - $totalLalu) / $totalLalu * 100
            : 0;

        $this->dataPendapatan = $this->ambilDataPendapatan();
        $this->dispatch('renderChartPendapatan', $this->dataPendapatan);

        // 🔹 (opsional) cek stok menipis juga
        $this->cekStokMenipis();

        // 🔽 3. Panggil fungsi untuk mengambil 5 transaksi terbaru
        $this->ambilTransaksiTerakhir();
    }

    public function kembaliDashboard()
    {
        $this->halamanSekarang = 'dashboard';
    }

    public function ambilTransaksiTerakhir($limit = 5)
    {
        // Query disederhanakan, hanya mengambil dari tabel 'penjualans'
        $this->transaksiTerakhir = Penjualan::select('penjualans.*')
            // 🔽 Ganti nama kolom pelanggan sesuai dengan yang ada di tabel 'penjualans'
            ->selectRaw('customer_name as nama_pelanggan')
            // 🔽 Tambahkan kolom dummy/virtual 'sisa_pembayaran'
            // untuk kompatibilitas di Blade, tapi kita tidak menghitungnya
            ->selectRaw('CASE WHEN status = "bon" THEN 1 ELSE 0 END AS sisa_pembayaran')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        // Catatan: Asumsi kolom 'status' di tabel 'penjualans' berisi 'lunas' atau 'bon'.
    }

    public function updatedTahunPendapatan()
    {
        $this->dataPendapatan = $this->ambilDataPendapatan();
        $this->dispatch('renderChartPendapatan', $this->dataPendapatan);
    }


    // 🔹 Ambil total produk
    public function totalProduk()
    {
        return Produk::count();
    }

    // 🔹 Ambil total supplier
    public function totalSupplier()
    {
        return Supplier::count();
    }

    // 🔹 Fungsi buka/tutup sidebar
    public function toggleSidebar()
    {
        $this->tampilSidebar = !$this->tampilSidebar;
    }

    // 🔹 Pindah antar halaman dashboard
    public function pindahHalaman($halaman)
    {
        $this->halamanSekarang = $halaman;
        $this->tampilSidebar = false;
    }

    // 🔹 Lihat detail transaksi
    public function lihatTransaksi($idTransaksi)
    {
        $this->transaksiTerpilih = $this->ambilDetailTransaksi($idTransaksi);
        $this->tampilModalTransaksi = true;
    }

    public function tutupModal()
    {
        $this->tampilModalTransaksi = false;
    }

    // 🔹 Logout user
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    // 🔹 Data dummy transaksi (bisa diganti query real)
    protected function ambilDetailTransaksi($id)
    {
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
                // Ambil semua data penjualan tanpa join ke 'pembayarans'
                $this->dataTabel = Penjualan::select('penjualans.*')
                    ->selectRaw('customer_name as nama_pelanggan')
                    ->selectRaw('CASE WHEN status = "bon" THEN 1 ELSE 0 END AS sisa_pembayaran')
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }
    }

    // 🔹 Ambil data pendapatan bulanan untuk grafik
    public function ambilDataPendapatan()
    {
        $data = LaporanPendapatan::where('bulan', 'like', $this->tahunPendapatan . '%')
            ->orderBy('bulan')
            ->pluck('total_penjualan', 'bulan')
            ->toArray();

        // Buat array 12 bulan
        $hasil = [];
        for ($i = 1; $i <= 12; $i++) {
            $key = sprintf('%s-%02d', $this->tahunPendapatan, $i);
            $hasil[] = $data[$key] ?? 0;
        }

        return $hasil;
    }


    // 🔹 Data produk terlaris (dummy)
    public function ambilDataProdukTerlaris()
    {
        return $this->bulanProdukTerlaris === 'lalu'
            ? [38, 32, 25, 18, 12]
            : [45, 38, 28, 22, 18];
    }

    // 🔹 Cek stok produk yang menipis
    public function cekStokMenipis($batas = 3)
    {
        $this->produkMenipis = Produk::with('produkDetails.bahan')->get()->filter(function ($produk) use ($batas) {
            return $produk->stok_tersedia <= $batas;
        })->sortBy('stok_tersedia');
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
