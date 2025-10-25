<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class LaporanPembukuanExport implements FromView, WithTitle, WithStyles
{
    protected $tanggal, $shift;

    public function __construct($tanggal, $shift)
    {
        $this->tanggal = $tanggal;
        $this->shift = $shift;
    }

    public function view(): View
    {
        // === 🔹 Ambil data utama seperti di controller PDF ===
        $totalPenjualan = DB::table('penjualans')
            ->whereDate('tanggal', $this->tanggal)
            ->where('shift', $this->shift)
            ->sum('total');

        $totalModal = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->whereDate('p.tanggal', $this->tanggal)
            ->where('p.shift', $this->shift)
            ->selectRaw('COALESCE(SUM(d.qty * pr.harga_beli),0) as total_modal')
            ->value('total_modal');

        $kas = DB::table('kas_mutasis')
            ->whereDate('tanggal', $this->tanggal)
            ->where('shift', $this->shift)
            ->selectRaw("
                SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) as masuk,
                SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) as keluar
            ")->first();

        $kasMasuk = $kas->masuk ?? 0;
        $kasKeluar = $kas->keluar ?? 0;
        $expectedCash = $totalPenjualan + $kasMasuk - $kasKeluar;
        $laba = $totalPenjualan - $totalModal;

        $closure = DB::table('shift_closures')
            ->where('tanggal', $this->tanggal)
            ->where('shift', $this->shift)
            ->first();

        // === 🔹 Ambil tabel penjualan ===
        $penjualan = DB::table('penjualans as p')
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
            ->whereDate('p.tanggal', $this->tanggal)
            ->where('p.shift', $this->shift)
            ->groupBy('pr.nama')
            ->get();

        // === 🔹 Ambil tabel pengeluaran ===
        $pengeluaran = DB::table('pengeluarans as pg')
            ->join('bahans as b', 'pg.bahan_idbahan', '=', 'b.idbahan')
            ->select('b.nama', 'pg.tanggal', 'pg.jumlah', 'pg.harga', 'pg.total', 'pg.keterangan')
            ->whereDate('pg.tanggal', $this->tanggal)
            ->where('pg.shift', $this->shift)
            ->get();

        return view('layouts.laporan-pembukuanexcel', [
            'tanggal' => $this->tanggal,
            'shift' => $this->shift,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'laba' => $laba,
            'kas_masuk' => $kasMasuk,
            'kas_keluar' => $kasKeluar,
            'expected_cash' => $expectedCash,
            'closure' => $closure,
            'penjualan' => $penjualan,
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Pembukuan Shift ' . $this->shift;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
