<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanPembukuanLiveExport implements FromView, WithTitle, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('layouts.laporan-pembukuanexcel', [
            'tanggal' => $this->data['tanggal'],
            'shift' => $this->data['shift'],
            'total_penjualan' => $this->data['summary']['total_penjualan'],
            'total_modal' => $this->data['summary']['total_modal'],
            'laba' => $this->data['summary']['laba'],
            'kas_masuk' => $this->data['summary']['kas_masuk'],
            'kas_keluar' => $this->data['summary']['kas_keluar'],
            'expected_cash' => $this->data['summary']['expected_cash'],
            'penjualan' => $this->data['penjualan'],
            'pengeluaran' => $this->data['pengeluaran'],
            'closure' => null,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Pembukuan Shift ' . $this->data['shift'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ===================== 🔹 UMUM =====================
                $sheet->getDefaultRowDimension()->setRowHeight(20);
                $sheet->getDefaultColumnDimension()->setWidth(18);
                $sheet->getStyle('A:Z')->getFont()->setName('Calibri')->setSize(11);

                // ===================== 🔹 HEADER =====================
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN PEMBUKUAN KEUANGAN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');

                // ===================== 🔹 TABEL RINGKASAN =====================
                $summaryRange = 'A3:B10';
                $sheet->getStyle($summaryRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle($summaryRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A3:A10')->getFont()->setBold(true);

                // Warna header “Keterangan / Jumlah”
                $sheet->getStyle('A4:B4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
                $sheet->getStyle('A4:B4')->getFont()->setBold(true);

                // Warna Kas Seharusnya di Laci
                $sheet->getStyle('A10:B10')->getFont()->setBold(true);
                $sheet->getStyle('A10:B10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');

                // ===================== 🔹 DETAIL PENJUALAN =====================
                $penjualanStart = 12;
                $sheet->setCellValue('A' . $penjualanStart, '📦 Detail Barang Terjual');
                $sheet->mergeCells('A' . $penjualanStart . ':G' . $penjualanStart);
                $sheet->getStyle('A' . $penjualanStart)->getFont()->setBold(true);
                $sheet->getStyle('A' . $penjualanStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');

                $headerPenjualan = $penjualanStart + 1;
                $sheet->getStyle('A' . $headerPenjualan . ':G' . $headerPenjualan)->getFont()->setBold(true);
                $sheet->getStyle('A' . $headerPenjualan . ':G' . $headerPenjualan)->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F3F6FB');
                $sheet->getStyle('A' . $headerPenjualan . ':G' . ($headerPenjualan + 20))
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // ===================== 🔹 DETAIL PENGELUARAN =====================
                $pengeluaranStart = $headerPenjualan + 25;
                $sheet->setCellValue('A' . $pengeluaranStart, '💰 Detail Pengeluaran');
                $sheet->mergeCells('A' . $pengeluaranStart . ':F' . $pengeluaranStart);
                $sheet->getStyle('A' . $pengeluaranStart)->getFont()->setBold(true);
                $sheet->getStyle('A' . $pengeluaranStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FCE4D6');

                $headerPengeluaran = $pengeluaranStart + 1;
                $sheet->getStyle('A' . $headerPengeluaran . ':F' . $headerPengeluaran)->getFont()->setBold(true);
                $sheet->getStyle('A' . $headerPengeluaran . ':F' . $headerPengeluaran)->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8CBAD');
                $sheet->getStyle('A' . $headerPengeluaran . ':F' . ($headerPengeluaran + 20))
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        ];
    }
}
