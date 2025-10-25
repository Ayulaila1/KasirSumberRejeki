<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class LaporanKasMutasiExport implements FromView
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $shift;

    public function __construct($tanggal_awal, $tanggal_akhir, $shift)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->shift = $shift;
    }

    public function view(): View
    {
        $query = DB::table('kas_mutasis as km')
            ->join('users as u', 'km.user_id', '=', 'u.id')
            ->select('km.*', 'u.name as user')
            ->whereBetween(DB::raw('DATE(km.tanggal)'), [$this->tanggal_awal, $this->tanggal_akhir]);

        if (!empty($this->shift)) {
            $query->where('km.shift', $this->shift);
        }

        $kasMutasi = $query->orderBy('km.tanggal')->get();

        return view('layouts.laporan-kas-mutasiexcel', [
            'kasMutasi' => $kasMutasi,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'shift' => $this->shift,
        ]);
    }
}
