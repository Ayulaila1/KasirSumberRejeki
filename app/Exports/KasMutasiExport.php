<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KasMutasiExport implements FromCollection, WithHeadings
{
    protected $tanggalAwal, $tanggalAkhir, $shift;

    public function __construct($tanggalAwal, $tanggalAkhir, $shift)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->shift = $shift;
    }

    public function collection()
    {
        $query = DB::table('kas_mutasis as k')
            ->join('users as u', 'k.user_id', '=', 'u.id')
            ->select('k.tanggal', 'k.shift', 'k.jenis', 'k.nominal', 'k.keterangan', 'u.name as user')
            ->whereBetween('k.tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
            ->orderBy('k.tanggal', 'desc');

        if ($this->shift !== '') {
            $query->where('k.shift', $this->shift);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Shift', 'Jenis', 'Nominal', 'Keterangan', 'Petugas'];
    }
}
