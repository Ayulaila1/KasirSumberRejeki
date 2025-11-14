<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKasMutasiExport;

class LaporanController extends Controller
{
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
