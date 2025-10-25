<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKasMutasiExport;
use App\Exports\LaporanPembukuanExport;

class LaporanController extends Controller
{
    /**
     * Cetak laporan pembukuan per shift ke PDF
     */
    public function cetakPembukuanPDF($tanggal, $shift)
    {
        // 🔹 Data utama (penjualan, modal, kas masuk/keluar)
        $totalPenjualan = DB::table('penjualans')
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $shift)
            ->sum('total');

        $totalModal = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->whereDate('p.tanggal', $tanggal)
            ->where('p.shift', $shift)
            ->sum(DB::raw('d.qty * pr.harga_beli'));

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
        $laba = $totalPenjualan - $totalModal;

        // 🔹 Data detail penjualan
        $penjualan = DB::table('penjualans as p')
            ->join('penjualandtls as d', 'p.idpenjualan', '=', 'd.penjualan_idpenjualan')
            ->join('produks as pr', 'd.produk_idproduk', '=', 'pr.idproduk')
            ->whereDate('p.tanggal', $tanggal)
            ->where('p.shift', $shift)
            ->select(
                'pr.nama',
                DB::raw('SUM(d.qty) as qty'),
                DB::raw('AVG(pr.harga_beli) as harga_beli'),
                DB::raw('AVG(pr.harga_jual) as harga_jual'),
                DB::raw('SUM(d.subtotal) as subtotal'),
                DB::raw('SUM((pr.harga_jual - pr.harga_beli) * d.qty) as laba_kotor')
            )
            ->groupBy('pr.nama')
            ->get();

        // 🔹 Data detail pengeluaran
        $pengeluaran = DB::table('pengeluarans as pg')
            ->join('bahans as b', 'pg.bahan_idbahan', '=', 'b.idbahan')
            ->whereDate('pg.tanggal', $tanggal)
            ->where('pg.shift', $shift)
            ->select('b.nama', 'pg.jumlah', 'pg.harga', 'pg.total', 'pg.keterangan')
            ->get();

        // 🔹 Cek apakah shift sudah ditutup
        $closure = DB::table('shift_closures')
            ->leftJoin('users', 'shift_closures.user_iduser', '=', 'users.id')
            ->where('shift_closures.tanggal', $tanggal)
            ->where('shift_closures.shift', $shift)
            ->select('shift_closures.*', 'users.name as user_name')
            ->first();

        // 🔹 Kirim data ke view
        $data = [
            'tanggal' => $tanggal,
            'shift' => $shift,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'laba' => $laba,
            'kas_masuk' => $kasMasuk,
            'kas_keluar' => $kasKeluar,
            'expected_cash' => $expectedCash,
            'closure' => $closure,
            'penjualan' => $penjualan,
            'pengeluaran' => $pengeluaran,
        ];

        // 🔹 Render ke PDF
        $pdf = Pdf::loadView('layouts.laporan-pembukuan', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan-Pembukuan-Shift{$shift}-{$tanggal}.pdf");
    }

    /**
     * Export laporan pembukuan ke Excel
     */
    public function exportPembukuanExcel($tanggal, $shift)
    {
        $fileName = "Laporan-Pembukuan-Shift{$shift}-{$tanggal}.xlsx";
        return Excel::download(new LaporanPembukuanExport($tanggal, $shift), $fileName);
    }

    public function cetakKasMutasiPDF(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $shift = $request->shift;

        $data = DB::table('kas_mutasis as km')
            ->join('users as u', 'km.user_iduser', '=', 'u.id')
            ->select('km.*', 'u.name as user')
            ->whereBetween(DB::raw('DATE(km.tanggal)'), [$tanggalAwal, $tanggalAkhir])
            ->when($shift, function ($q) use ($shift) {
                $q->where('km.shift', $shift);
            })
            ->orderBy('km.tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('layouts.laporan-kas-mutasi', [
            'data' => $data,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'shift' => $shift
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan-KasMutasi-{$tanggalAwal}-sampai-{$tanggalAkhir}.pdf");
    }

    public function exportKasMutasiExcel(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $shift = $request->shift;

        $fileName = "Laporan-KasMutasi-{$tanggalAwal}-sampai-{$tanggalAkhir}.xlsx";
        return Excel::download(
            new LaporanKasMutasiExport($tanggalAwal, $tanggalAkhir, $shift),
            $fileName
        );
    }

}
