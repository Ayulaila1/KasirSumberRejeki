<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class KasMutasiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
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
            ->join('users as u', 'k.user_iduser', '=', 'u.id')
            ->select(
                'k.tanggal',
                'k.shift',
                'k.jenis',
                'k.nominal',
                'k.keterangan',
                'u.name as user'
            )
            ->whereBetween('k.tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
            ->orderBy('k.tanggal', 'desc');

        if (!empty($this->shift)) {
            $query->where('k.shift', $this->shift);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Shift',
            'Jenis',
            'Nominal',
            'Keterangan',
            'Petugas'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 🔹 Header Style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '4A6FA5'], // biru lembut
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // 🔹 Border untuk seluruh data
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '808080'],
                ],
            ],
        ]);

        // 🔹 Rata kanan untuk kolom nominal
        $sheet->getStyle('D2:D' . $highestRow)
            ->getAlignment()
            ->setHorizontal('right');

        // 🔹 Tambahkan sedikit tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // format nominal
        ];
    }
}
