<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Bahan;
use Livewire\Component;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Pembeliandtl;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\LaporanPenjualanExport;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanPenjualanComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $tglStart;
    public $tglEnd, $perPage = 10;
    public $shift;
    public $page = 1;

    public function mount()
    {
        $this->tglStart = now()->startOfMonth()->format('Y-m-d');
        $this->tglEnd = now()->endOfMonth()->format('Y-m-d');
    }

    // ================= EXPORT EXCEL =================
    public function exportToExcel()
    {
        $penjualans = Penjualan::with(['penjualanDtl.produk', 'user'])
            ->whereBetween('tanggal', [$this->tglStart, $this->tglEnd])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Header
        $headers = ['Tanggal', 'Kode Penjualan', 'Customer', 'User', 'Produk', 'Qty', 'Harga Jual', 'Subtotal'];
        $sheet->fromArray($headers, null, 'A1');

        // Style header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $row = 2;
        $no = 1;
        foreach ($penjualans as $p) {
            foreach ($p->penjualanDtl as $d) {
                $sheet->fromArray([
                    $p->tanggal->format('Y-m-d'),
                    $p->kode_penjualan,
                    $p->customer_name,
                    $p->user->name ?? '-',
                    $d->produk->nama ?? '-',
                    $d->qty,
                    $d->harga_jual,
                    $d->subtotal
                ], null, "A{$row}");
                $row++;
            }
        }

        // Border dan format angka
        $sheet->getStyle("A1:I" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle("H2:I" . ($row - 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Auto width
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-penjualan-' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path($fileName);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    // ================= EXPORT PDF =================
    public function exportToPdf()
    {
        $penjualans = Penjualan::with(['penjualanDtl.produk', 'user'])
            ->whereBetween('tanggal', [$this->tglStart, $this->tglEnd])
            ->get();

        $data = [];
        $no = 1;
        foreach ($penjualans as $p) {
            foreach ($p->penjualanDtl as $d) {
                $data[] = [
                    $p->tanggal->format('Y-m-d'),
                    $p->kode_penjualan,
                    $p->customer_name,
                    $p->user->name ?? '-',
                    $d->produk->nama ?? '-',
                    $d->qty,
                    $d->harga_jual,
                    $d->subtotal
                ];
            }
        }

        $headers = ['Tanggal', 'Kode Penjualan', 'Customer', 'User', 'Produk', 'Qty', 'Harga Jual', 'Subtotal'];
        $title = 'Laporan Penjualan';

        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));
        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-penjualan.pdf');
    }

    public function render()
    {
        $laporanRaw = Penjualan::query()
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$this->tglStart, $this->tglEnd]))
            ->when($this->search, fn($q) =>
                $q->where(function ($q2) {
                    $q2->where('kode_penjualan', 'like', "%{$this->search}%")
                        ->orWhere('customer_name', 'like', "%{$this->search}%")
                        ->orWhereHas('penjualanDtl.produk', fn($q3) =>
                            $q3->where('nama', 'like', "%{$this->search}%"));
                }))
            ->with(['penjualanDtl.produk'])
            ->orderByDesc('tanggal')
            ->paginate($this->perPage);

        return view('livewire.laporan-penjualan-component', [
            'laporan' => $laporanRaw
        ]);
    }


}