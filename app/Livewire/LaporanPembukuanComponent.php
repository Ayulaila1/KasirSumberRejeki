<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use App\Exports\LaporanPembukuanLiveExport;

class LaporanPembukuanComponent extends Component
{
    public $tanggal, $shift = 1;
    public $showCloseModal = false;
    public $kas_fisik, $keterangan_close;
    public $summary = [];
    public $riwayatShift = [];
    public $tanggalAwal, $tanggalAkhir, $shiftFilter = '';
    public $laporanPenjualan = [];
    public $laporanPengeluaran = [];
    public $stokBarang = [];

    protected $rules = [
        'kas_fisik' => 'required|numeric|min:0',
    ];

    /** 🔹 Tentukan shift & tanggal efektif berdasarkan waktu sekarang (pakai timezone Jakarta)
     *  Note: untuk Shift 3 (00:00-07:59) kita anggap masih bagian dari tanggal hari sebelumnya.
     */
    public function getCurrentShiftAndDate()
    {
        $now = Carbon::now('Asia/Jakarta');
        $hour = (int) $now->format('H');

        if ($hour >= 8 && $hour < 16) {
            // Shift 1 (08:00 - 15:59)
            return [
                'shift' => 1,
                'tanggal' => $now->toDateString(),
            ];
        } elseif ($hour >= 16 && $hour < 24) {
            // Shift 2 (16:00 - 23:59)
            return [
                'shift' => 2,
                'tanggal' => $now->toDateString(),
            ];
        } else {
            // Shift 3 (00:00 - 07:59) => anggap masih bagian dari tanggal kemarin
            return [
                'shift' => 3,
                'tanggal' => $now->copy()->subDay()->toDateString(),
            ];
        }
    }

    /** 🔹 Inisialisasi awal */
    public function mount()
    {
        $current = $this->getCurrentShiftAndDate();
        $this->tanggal = $current['tanggal'];
        $this->shift = $current['shift'];

        $this->tanggalAwal = $this->tanggal;
        $this->tanggalAkhir = $this->tanggal;

        $this->loadSummary();
        $this->loadRiwayatShift();
        $this->loadLaporan();
    }

    /** 🔹 Refresh otomatis saat tanggal atau filter berubah */
    public function updated($field)
    {
        if (in_array($field, ['tanggal', 'shift', 'tanggalAwal', 'tanggalAkhir', 'shiftFilter'])) {
            $this->loadSummary();
            $this->loadRiwayatShift();
            $this->loadLaporan();
        }
    }

    /* ====================== 🔹 RIWAYAT SHIFT ====================== */
    public function loadRiwayatShift()
    {
        $user = Auth::user();

        $query = DB::table('shift_closures as sc')
            ->leftJoin('users as u', 'sc.user_iduser', '=', 'u.id')
            ->select('sc.*', 'u.name as user')
            ->orderByDesc('sc.tanggal')
            ->orderBy('sc.shift', 'asc');

        if (!empty($this->tanggal)) {
            $query->whereDate('sc.tanggal', $this->tanggal);
        }

        if ($user->role !== 'admin') {
            $query->where('sc.shift', $user->shift);
        }

        $this->riwayatShift = $query->get();
    }

    /* ====================== 🔹 SUMMARY ====================== */
    public function loadSummary()
    {
        $tanggal = $this->tanggal;
        $user = Auth::user();
        $shift = $user->role === 'admin' ? $this->shift : $user->shift;

        // ================== TOTAL PENJUALAN ==================
        $totalPenjualan = DB::table('penjualans')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('shift', $shift)->orWhereNull('shift');
            }))
            ->selectRaw('COALESCE(SUM(total),0) as total_penjualan')
            ->value('total_penjualan');

        // ================== TOTAL MODAL ==================
        $totalModal = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('p.tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('p.shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('p.shift', $shift)->orWhereNull('p.shift');
            }))
            ->selectRaw('COALESCE(SUM(d.qty * pr.harga_beli),0) as total_modal')
            ->value('total_modal');

        // ================== JUMLAH TRANSAKSI ==================
        $countTrans = DB::table('penjualans')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('shift', $shift)->orWhereNull('shift');
            }))
            ->count();

        // ================== KAS MASUK / KELUAR ==================
        $kas = DB::table('kas_mutasis')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('shift', $shift)->orWhereNull('shift');
            }))
            ->selectRaw("
            COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END),0) as masuk,
            COALESCE(SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END),0) as keluar
        ")->first();

        $kasMasuk = $kas->masuk ?? 0;
        $kasKeluar = $kas->keluar ?? 0;

        // ================== TOTAL PENGELUARAN ==================
        // 1️⃣ Pembelian biasa → langsung masuk pengeluaran
        $totalPembelianBiasa = DB::table('pembelians as p')
            ->join('pembeliandtls as d', 'p.idpembelian', '=', 'd.pembelian_idpembelian')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('p.tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('p.shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('p.shift', $shift)->orWhereNull('p.shift');
            }))
            ->where('p.jenis_pembelian', 'biasa')
            ->selectRaw('COALESCE(SUM(d.subtotal),0) as total')
            ->value('total');

        // 2️⃣ Pembelian titipan → hanya barang terjual
        $totalTitipanTerjual = DB::table('penjualandtls as pd')
            ->join('penjualans as pj', 'pd.penjualan_idpenjualan', '=', 'pj.idpenjualan')
            ->join('produks as pr', 'pd.produk_idproduk', '=', 'pr.idproduk')
            ->when(!empty($tanggal), fn($q) => $q->whereDate('pj.tanggal', $tanggal))
            ->when($user->role !== 'admin', fn($q) => $q->where('pj.shift', $user->shift))
            ->when($user->role === 'admin', fn($q) => $q->where(function ($qq) use ($shift) {
                $qq->where('pj.shift', $shift)->orWhereNull('pj.shift');
            }))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pembelians as b')
                    ->whereColumn('b.jenis_pembelian', DB::raw("'titipan'"));
            })
            ->selectRaw('COALESCE(SUM(pd.qty * pr.harga_beli),0) as total')
            ->value('total');

        // Gabungkan hasilnya
        $totalPengeluaran = $totalPembelianBiasa + $totalTitipanTerjual;

        // ================== HITUNGAN AKHIR ==================
        $expectedCash = (float) $totalPenjualan + (float) $kasMasuk - (float) $kasKeluar - (float) $totalPengeluaran;

        $this->summary = [
            'total_penjualan' => (float) $totalPenjualan,
            'total_modal' => (float) $totalModal,
            'total_pengeluaran' => (float) $totalPengeluaran,
            'laba' => (float) ($totalPenjualan - $totalModal),
            'count_trans' => (int) $countTrans,
            'kas_masuk' => (float) $kasMasuk,
            'kas_keluar' => (float) $kasKeluar,
            'expected_cash' => (float) $expectedCash,
            'shift' => $shift,
        ];
    }

    /* ====================== 🔹 DETAIL LAPORAN ====================== */
    public function loadLaporan()
    {
        $user = Auth::user();
        $tanggalAwal = $this->tanggalAwal;
        $tanggalAkhir = $this->tanggalAkhir;
        $shiftAktif = $user->role === 'admin' ? ($this->shiftFilter ?: $this->shift) : $user->shift;

        // ================== PENJUALAN BARANG ==================
        $penjualan = DB::table('penjualandtls as d')
            ->join('penjualans as p', 'd.penjualan_idpenjualan', '=', 'p.idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->join('produk_racikans as prc', 'prc.produk_idproduk', '=', 'pr.idproduk') // join ke racikan
            ->join('bahans as b', 'b.idbahan', '=', 'prc.bahan_idbahan')
            ->when(
                $tanggalAwal && $tanggalAkhir,
                fn($q) =>
                $q->whereBetween(DB::raw('DATE(p.tanggal)'), [$tanggalAwal, $tanggalAkhir])
            )
            ->select(
                'pr.idproduk',
                'pr.nama',
                DB::raw('DATE(p.tanggal) as tanggal'),
                DB::raw("
            CASE
                WHEN TIME(p.tanggal) BETWEEN '08:00:00' AND '15:59:59' THEN 1
                WHEN TIME(p.tanggal) BETWEEN '16:00:00' AND '23:59:59' THEN 2
                ELSE 3
            END AS shift
        "),
                DB::raw('SUM(d.qty) as qty'),
                DB::raw('AVG(pr.harga_beli) as harga_beli'),
                DB::raw('AVG(d.harga_jual) as harga_jual'),
                DB::raw('SUM(d.subtotal) as subtotal'),
                DB::raw('SUM((d.harga_jual - pr.harga_beli) * d.qty) as laba_kotor'),

                // Stok awal (GREATEST(0, …) → aman shift pertama)
                DB::raw('GREATEST(0, (
            COALESCE((
                SELECT SUM(pd.jumlah * prc2.takaran)
                FROM pembeliandtls pd
                JOIN pembelians pb ON pd.pembelian_idpembelian = pb.idpembelian
                JOIN produk_racikans prc2 ON prc2.bahan_idbahan = pd.bahan_idbahan
                WHERE prc2.produk_idproduk = pr.idproduk
                AND DATE(pb.tanggal) < DATE(p.tanggal)
            ), 0) -
            COALESCE((
                SELECT SUM(psd.qty * prc3.takaran)
                FROM penjualandtls psd
                JOIN penjualans ps ON psd.penjualan_idpenjualan = ps.idpenjualan
                JOIN produk_racikans prc3 ON prc3.produk_idproduk = psd.produk_idproduk
                WHERE prc3.bahan_idbahan = psd.produk_idproduk
                AND DATE(ps.tanggal) < DATE(p.tanggal)
            ), 0)
        )) AS stok_awal'),

                // Stok sisa
                DB::raw('GREATEST(0, (
            COALESCE((
                SELECT SUM(pd.jumlah * prc2.takaran)
                FROM pembeliandtls pd
                JOIN pembelians pb ON pd.pembelian_idpembelian = pb.idpembelian
                JOIN produk_racikans prc2 ON prc2.bahan_idbahan = pd.bahan_idbahan
                WHERE prc2.produk_idproduk = pr.idproduk
                AND DATE(pb.tanggal) <= DATE(p.tanggal)
            ), 0) -
            COALESCE((
                SELECT SUM(psd.qty * prc3.takaran)
                FROM penjualandtls psd
                JOIN penjualans ps ON psd.penjualan_idpenjualan = ps.idpenjualan
                JOIN produk_racikans prc3 ON prc3.produk_idproduk = psd.produk_idproduk
                WHERE prc3.bahan_idbahan = psd.produk_idproduk
                AND DATE(ps.tanggal) <= DATE(p.tanggal)
            ), 0)
        )) AS stok_sisa'),

                DB::raw("
            CASE
                WHEN TIME(p.tanggal) BETWEEN '08:00:00' AND '15:59:59' THEN 'Terjual pada Shift 1'
                WHEN TIME(p.tanggal) BETWEEN '16:00:00' AND '23:59:59' THEN 'Terjual pada Shift 2'
                ELSE 'Terjual pada Shift 3'
            END AS keterangan
        ")
            )
            ->groupBy('pr.idproduk', 'pr.nama', 'tanggal', 'shift')
            ->orderBy('tanggal', 'asc')
            ->orderBy('shift', 'asc')
            ->get();

        // ================== PEMBELIAN BIASA ==================
        $biasa = DB::table('pembeliandtls as d')
            ->join('pembelians as p', 'd.pembelian_idpembelian', '=', 'p.idpembelian')
            ->leftJoin('bahans as b', 'd.bahan_idbahan', '=', 'b.idbahan')
            ->leftJoin('suppliers as s', 'p.supplier_idsupplier', '=', 's.idsupplier')
            ->when(
                $tanggalAwal && $tanggalAkhir,
                fn($q) =>
                $q->whereBetween(DB::raw('DATE(p.tanggal)'), [$tanggalAwal, $tanggalAkhir])
            )
            ->when(
                $user->role !== 'admin',
                fn($q) =>
                $q->where('p.shift', $user->shift)
            )
            ->when(
                $user->role === 'admin' && $this->shiftFilter,
                fn($q) =>
                $q->where('p.shift', $this->shiftFilter)
            )
            ->where('p.jenis_pembelian', 'biasa')
            ->select(
                'b.nama as bahan_nama',
                'p.tanggal',
                DB::raw('SUM(d.jumlah) as jumlah'),
                DB::raw('AVG(d.harga_beli) as harga'),
                DB::raw('SUM(d.subtotal) as total'),
                's.nama as supplier_nama',
                DB::raw("
                CASE
                    WHEN HOUR(p.tanggal) >= 8 AND HOUR(p.tanggal) < 16 THEN 'Dibeli pada Shift 1'
                    WHEN HOUR(p.tanggal) >= 16 AND HOUR(p.tanggal) < 24 THEN 'Dibeli pada Shift 2'
                    ELSE 'Dibeli pada Shift 3'
                END AS keterangan
            ")
            )
            ->groupBy('b.nama', 'p.tanggal', 's.nama')
            ->get();

        // ================== PEMBELIAN TITIPAN (yang TERJUAL saja) ==================
        $titipan = DB::table('penjualandtls as pd')
            ->join('penjualans as pj', 'pd.penjualan_idpenjualan', '=', 'pj.idpenjualan')
            ->join('produks as pr', 'pd.produk_idproduk', '=', 'pr.idproduk')
            ->join('pembelians as p', function ($join) {
                $join->where('p.jenis_pembelian', 'titipan');
            })
            ->leftJoin('suppliers as s', 'p.supplier_idsupplier', '=', 's.idsupplier')
            ->when(
                $tanggalAwal && $tanggalAkhir,
                fn($q) =>
                $q->whereBetween(DB::raw('DATE(pj.tanggal)'), [$tanggalAwal, $tanggalAkhir])
            )
            ->when(
                $user->role !== 'admin',
                fn($q) =>
                $q->where('pj.shift', $user->shift)
            )
            ->when(
                $user->role === 'admin' && $this->shiftFilter,
                fn($q) =>
                $q->where('pj.shift', $this->shiftFilter)
            )
            ->select(
                'pr.nama as bahan_nama',
                DB::raw('MIN(pj.tanggal) as tanggal'),
                DB::raw('SUM(pd.qty) as jumlah'),
                DB::raw('AVG(pr.harga_beli) as harga'),
                DB::raw('SUM(pd.qty * pr.harga_beli) as total'),
                's.nama as supplier_nama',
                DB::raw("
                CASE
                    WHEN HOUR(MIN(pj.tanggal)) >= 8 AND HOUR(MIN(pj.tanggal)) < 16 THEN 'Dibeli pada Shift 1'
                    WHEN HOUR(MIN(pj.tanggal)) >= 16 AND HOUR(MIN(pj.tanggal)) < 24 THEN 'Dibeli pada Shift 2'
                    ELSE 'Dibeli pada Shift 3'
                END AS keterangan
            ")
            )
            ->groupBy('pr.nama', 's.nama')
            ->get();

        // ================== GABUNGKAN HASIL PEMBELIAN ==================
        $combined = $biasa->merge($titipan)->sortBy('tanggal')->values();

        // 🟢 SIMPAN HASIL KE PROPERTI LIVEWIRE
        $this->laporanPenjualan = $penjualan;
        $this->laporanPengeluaran = $combined;
    }


    /* ====================== 🔹 CLOSE SHIFT ====================== */
    public function openCloseModal()
    {
        $this->kas_fisik = $this->summary['expected_cash'] ?? 0;
        $this->keterangan_close = null;
        $this->showCloseModal = true;
    }

    public function closeShift()
    {
        $this->validate();
        DB::beginTransaction();

        try {
            $this->loadSummary();
            $expected = $this->summary['expected_cash'];
            $selisih = round($this->kas_fisik - $expected, 2);

            DB::table('shift_closures')->insert([
                'tanggal' => $this->tanggal,
                'shift' => $this->shift,
                'user_iduser' => Auth::id(),
                'total_penjualan' => $this->summary['total_penjualan'],
                'total_modal' => $this->summary['total_modal'],
                'total_pengeluaran' => $this->summary['total_pengeluaran'],
                'kas_fisik' => $this->kas_fisik,
                'selisih' => $selisih,
                'keterangan' => $this->keterangan_close,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            $this->showCloseModal = false;
            $this->loadSummary();
            $this->loadRiwayatShift();
            session()->flash('message', '✅ Shift berhasil ditutup. Selisih: ' . number_format($selisih, 0, ',', '.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /* ====================== 🔹 EXPORT ====================== */
    public function exportExcel()
    {
        // pastikan data segar
        $this->loadSummary();
        $this->loadLaporan();

        $data = [
            'tanggal' => $this->tanggal,
            'shift' => $this->shift,
            'summary' => $this->summary,
            'penjualan' => $this->laporanPenjualan,
            'pengeluaran' => $this->laporanPengeluaran,
            'closure' => DB::table('shift_closures')
                ->leftJoin('users', 'shift_closures.user_iduser', '=', 'users.id')
                ->where('shift_closures.tanggal', $this->tanggal)
                ->where('shift_closures.shift', $this->shift)
                ->select('shift_closures.*', 'users.name as user_name')
                ->first(),
        ];

        $fileName = "Laporan-Pembukuan-Shift{$this->shift}-{$this->tanggal}.xlsx";

        return Excel::download(new LaporanPembukuanLiveExport($data), $fileName);
    }

    public function exportPDF()
    {
        // Ambil ulang data agar akurat
        $this->loadSummary();
        $this->loadLaporan();

        $data = [
            'tanggal' => $this->tanggal,
            'shift' => $this->shift,
            'total_penjualan' => $this->summary['total_penjualan'],
            'total_modal' => $this->summary['total_modal'],
            'laba' => $this->summary['laba'],
            'kas_masuk' => $this->summary['kas_masuk'],
            'kas_keluar' => $this->summary['kas_keluar'],
            'expected_cash' => $this->summary['expected_cash'],
            'closure' => DB::table('shift_closures')
                ->leftJoin('users', 'shift_closures.user_iduser', '=', 'users.id')
                ->where('shift_closures.tanggal', $this->tanggal)
                ->where('shift_closures.shift', $this->shift)
                ->select('shift_closures.*', 'users.name as user_name')
                ->first(),
            'penjualan' => $this->laporanPenjualan,
            'pengeluaran' => $this->laporanPengeluaran,
        ];

        $pdf = Pdf::loadView('layouts.laporan-pembukuan', $data)
            ->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "Laporan-Pembukuan-Shift{$this->shift}-{$this->tanggal}.pdf");
    }

    public function render()
    {
        return view('livewire.laporan-pembukuan-component', [
            'summary' => $this->summary,
            'riwayatShift' => $this->riwayatShift,
            'laporanPenjualan' => $this->laporanPenjualan,
            'laporanPengeluaran' => $this->laporanPengeluaran,
        ]);
    }
}
