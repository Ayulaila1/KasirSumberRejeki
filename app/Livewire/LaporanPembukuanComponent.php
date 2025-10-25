<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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

    protected $rules = [
        'kas_fisik' => 'required|numeric|min:0',
    ];

    /** SHIFT DETECTOR */
    public function getShiftFromTime($time)
    {
        $hour = Carbon::parse($time)->hour;
        if ($hour >= 8 && $hour < 16) {
            return 1; // 08.00 - 15.59
        } elseif ($hour >= 16 && $hour < 24) {
            return 2; // 16.00 - 23.59
        }
        return 3; // 00.00 - 07.59
    }

    /** INIT */
    public function mount()
    {
        $this->tanggal = now()->toDateString();
        $this->tanggalAwal = now()->toDateString();
        $this->tanggalAkhir = now()->toDateString();
        $this->shift = Auth::user()->shift ?? 1;

        $this->loadSummary();
        $this->loadRiwayatShift();
        $this->loadLaporan();
    }

    /** AUTO REFRESH */
    public function updated($field)
    {
        if (in_array($field, ['tanggal', 'shift', 'tanggalAwal', 'tanggalAkhir', 'shiftFilter'])) {
            $this->loadSummary();
            $this->loadRiwayatShift();
            $this->loadLaporan();
        }
    }

    /** ====================== LOAD RIWAYAT SHIFT ====================== */
    public function loadRiwayatShift()
    {
        $user = Auth::user();

        $query = DB::table('shift_closures as sc')
            ->leftJoin('users as u', 'sc.user_iduser', '=', 'u.id')
            ->select(
                'sc.id',
                'sc.tanggal',
                'sc.shift',
                'sc.total_penjualan',
                'sc.kas_fisik',
                'sc.selisih',
                'sc.keterangan',
                'u.name as user'
            )
            ->orderBy('sc.tanggal', 'desc')
            ->orderBy('sc.shift', 'asc');

        // 🔹 Filter tanggal jika dipilih
        if (!empty($this->tanggal)) {
            $query->whereDate('sc.tanggal', $this->tanggal);
        }

        // 🔹 Kalau bukan admin → tampilkan hanya data shift user
        if ($user->role !== 'admin') {
            $query->where('sc.shift', $user->shift);
        }

        $this->riwayatShift = $query->get();
    }


    /** ====================== LOAD SUMMARY ====================== */
    public function loadSummary()
    {
        $tanggal = $this->tanggal;
        $user = Auth::user();
        $shift = $user->role === 'admin' ? $this->shift : $user->shift;

        // Total Penjualan
        $totalPenjualan = DB::table('penjualans')
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $shift)
            ->sum('total');

        // Total Modal
        $totalModal = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->whereDate('p.tanggal', $tanggal)
            ->where('p.shift', $shift)
            ->selectRaw('COALESCE(SUM(d.qty * pr.harga_beli),0) as total_modal')
            ->value('total_modal');

        // Jumlah Transaksi
        $countTrans = DB::table('penjualans')
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $shift)
            ->count();

        // Kas Masuk & Keluar
        $kas = DB::table('kas_mutasis')
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $shift)
            ->selectRaw("
            SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) as masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) as keluar
        ")->first();

        $kasMasuk = $kas->masuk ?? 0;
        $kasKeluar = $kas->keluar ?? 0;
        $expectedCash = $totalPenjualan + $kasMasuk - $kasKeluar;

        $this->summary = [
            'total_penjualan' => (float) $totalPenjualan,
            'total_modal' => (float) $totalModal,
            'laba' => (float) ($totalPenjualan - $totalModal),
            'count_trans' => (int) $countTrans,
            'kas_masuk' => (float) $kasMasuk,
            'kas_keluar' => (float) $kasKeluar,
            'expected_cash' => (float) $expectedCash,
            'shift' => $shift
        ];
    }


    /** ====================== LOAD LAPORAN DETAIL ====================== */
    public function loadLaporan()
    {
        // Detail Penjualan
        $this->laporanPenjualan = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->select(
                'pr.nama',
                DB::raw('SUM(d.qty) as qty'),
                DB::raw('AVG(pr.harga_beli) as harga_beli'),
                DB::raw('AVG(pr.harga_jual) as harga_jual'),
                DB::raw('SUM(d.subtotal) as subtotal'),
                DB::raw('SUM((pr.harga_jual - pr.harga_beli) * d.qty) as laba_kotor')
            )
            ->whereBetween(DB::raw('DATE(p.tanggal)'), [$this->tanggalAwal, $this->tanggalAkhir])
            ->when($this->shiftFilter, fn($q) => $q->where('p.shift', $this->shiftFilter))
            ->groupBy('pr.nama')
            ->get();

        // Detail Pembelian (restok)
        $purchaseQuery = DB::table('pembeliandtls as d')
            ->join('pembelians as p', 'd.pembelian_idpembelian', '=', 'p.idpembelian')
            ->leftJoin('bahans as b', 'd.bahan_idbahan', '=', 'b.idbahan')
            ->select(
                'b.nama as bahan_nama',
                'p.tanggal',
                'd.jumlah',
                DB::raw('d.harga_beli as harga'),
                DB::raw('d.subtotal as total'),
                // cek kolom keterangan sebelum select
                DB::raw(Schema::hasColumn('pembelians', 'keterangan') ? 'p.keterangan' : "'' as keterangan"),
                'p.supplier_idsupplier as supplier_id'
            )
            ->whereBetween(DB::raw('DATE(p.tanggal)'), [$this->tanggalAwal, $this->tanggalAkhir]);

        if (Schema::hasColumn('pembelians', 'shift')) {
            $purchaseQuery->when($this->shiftFilter, fn($q) => $q->where('p.shift', $this->shiftFilter));
        } elseif (!empty($this->shiftFilter)) {
            $sf = (int) $this->shiftFilter;
            if ($sf == 1) {
                $purchaseQuery->whereTime('p.created_at', '>=', '08:00:00')->whereTime('p.created_at', '<=', '15:59:59');
            } elseif ($sf == 2) {
                $purchaseQuery->whereTime('p.created_at', '>=', '16:00:00')->whereTime('p.created_at', '<=', '23:59:59');
            } else {
                $purchaseQuery->whereTime('p.created_at', '>=', '00:00:00')->whereTime('p.created_at', '<=', '07:59:59');
            }
        }

        $this->laporanPengeluaran = $purchaseQuery->orderBy('p.tanggal', 'asc')->get();
    }

    /** ====================== CLOSE SHIFT ====================== */
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

            $closureId = DB::table('shift_closures')->insertGetId([
                'tanggal' => $this->tanggal,
                'shift' => $this->shift,
                'user_iduser' => Auth::id(),
                'total_penjualan' => $this->summary['total_penjualan'],
                'total_modal' => $this->summary['total_modal'],
                'total_pengeluaran' => $this->summary['total_pengeluaran'] ?? 0,
                'kas_fisik' => $this->kas_fisik,
                'selisih' => $selisih,
                'keterangan' => $this->keterangan_close,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($selisih != 0) {
                DB::table('kas_mutasis')->insert([
                    'tanggal' => $this->tanggal,
                    'shift' => $this->shift,
                    'jenis' => $selisih > 0 ? 'masuk' : 'keluar',
                    'nominal' => abs($selisih),
                    'keterangan' => "Penyesuaian selisih tutup shift (ID: {$closureId})",
                    'user_iduser' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('penjualans')
                ->whereDate('tanggal', $this->tanggal)
                ->where('shift', $this->shift)
                ->update(['closed_by' => Auth::id(), 'updated_at' => now()]);

            DB::commit();

            $this->showCloseModal = false;
            $this->loadSummary();
            $this->loadRiwayatShift();

            session()->flash('message', '✅ Shift berhasil ditutup. Selisih: ' . number_format($selisih, 0, ',', '.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
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
