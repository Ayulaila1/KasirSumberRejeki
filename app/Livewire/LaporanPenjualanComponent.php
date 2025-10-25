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

    public function exportToExcel()
    {
        $date = Carbon::parse($this->tglStart ?? now());

        // 🔹 Tentukan waktu shift
        switch ($this->shift) {
            case '1':
                $start = $date->copy()->setTime(8, 0, 0);
                $end = $date->copy()->setTime(16, 0, 0);
                $title = 'Laporan Shift Pagi';
                break;
            case '2':
                $start = $date->copy()->setTime(16, 0, 0);
                $end = $date->copy()->setTime(23, 59, 59);
                $title = 'Laporan Shift Sore';
                break;
            default:
                $start = $date->copy()->setTime(0, 0, 0);
                $end = $date->copy()->setTime(23, 59, 59);
                $title = 'Laporan Harian';
        }

        // 🔹 Ambil data
        $penjualans = Penjualan::with(['penjualanDtl.produk', 'user'])
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        $pembelianHariIni = Pembeliandtl::with(['bahan', 'pembelian'])
            ->whereHas('pembelian', fn($q) => $q->whereBetween('tanggal', [$start, $end]))
            ->get();

        $bahanWajib = ['gas', 'air', 'es'];
        $bahanWajibIds = Bahan::whereIn('nama', $bahanWajib)->pluck('idbahan')->toArray();

        $pengeluaranWajib = Pengeluaran::with('bahan')
            ->whereIn('bahan_idbahan', $bahanWajibIds)
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        $bahanPengeluaranIds = $pengeluaranWajib->pluck('bahan_idbahan')->toArray();
        $pembelianNonWajib = $pembelianHariIni->filter(fn($p) => !in_array($p->bahan_idbahan, $bahanPengeluaranIds));

        $totalPenjualan = $penjualans->sum('total');
        $totalPembelian = $pembelianNonWajib->sum(fn($d) => ($d->jumlah ?? 0) * ($d->harga_beli ?? 0));
        $totalPengeluaranWajib = $pengeluaranWajib->sum('total');
        $totalPengeluaran = $totalPembelian + $totalPengeluaranWajib;
        $profit = $totalPenjualan - $totalPengeluaran;

        // ======================== EXCEL ========================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // 🔹 Judul
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', 'Periode: ' . $start->format('d/m/Y H:i') . ' - ' . $end->format('d/m/Y H:i'));

        $row = 4;

        // 🔹 Bagian 1: Penjualan
        $sheet->setCellValue("A{$row}", 'Detail Penjualan');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $headers = ['No', 'Customer', 'Status', 'Barang', 'Qty', 'Stok Akhir', 'Harga Beli', 'Harga Jual'];
        $sheet->fromArray($headers, null, "A{$row}");
        $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $row++;
        $no = 1;
        foreach ($penjualans as $p) {
            foreach ($p->penjualanDtl as $d) {
                $sheet->fromArray([
                    $no++,
                    $p->customer_name ?? '-',
                    $p->status ?? '-',
                    $d->produk->nama ?? '-',
                    $d->qty ?? 0,
                    $d->produk->stok ?? 0,
                    $d->produk->harga_beli ?? 0,
                    $d->harga ?? 0
                ], null, "A{$row}");
                $row++;
            }
        }

        // Border data penjualan
        $sheet->getStyle("A5:H" . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // 🔹 Bagian 2: Pengeluaran
        $row += 2;
        $sheet->setCellValue("A{$row}", 'Detail Pengeluaran (Gas, Air, Es + Restocking Barang)');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $sheet->fromArray(['Nama Barang', 'Jumlah', 'Harga Beli', 'Total'], null, "A{$row}");
        $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '9BBB59']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        foreach ($pembelianNonWajib as $pd) {
            $sheet->fromArray([
                $pd->bahan->nama ?? '-',
                $pd->jumlah ?? 0,
                $pd->harga_beli ?? 0,
                ($pd->jumlah ?? 0) * ($pd->harga_beli ?? 0)
            ], null, "A{$row}");
            $row++;
        }

        foreach ($pengeluaranWajib as $pe) {
            $sheet->fromArray([
                $pe->bahan->nama ?? '-',
                $pe->jumlah ?? 0,
                $pe->harga ?? 0,
                $pe->total ?? 0
            ], null, "A{$row}");
            $row++;
        }

        $sheet->getStyle("A" . ($row - count($pembelianNonWajib) - count($pengeluaranWajib)) . ":D" . ($row - 1))
            ->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]]);

        // 🔹 Bagian 3: Ringkasan
        $row += 2;
        $sheet->setCellValue("A{$row}", 'Total Penjualan');
        $sheet->setCellValue("B{$row}", $totalPenjualan);
        $row++;
        $sheet->setCellValue("A{$row}", 'Total Pengeluaran');
        $sheet->setCellValue("B{$row}", $totalPengeluaran);
        $row++;
        $sheet->setCellValue("A{$row}", 'Profit');
        $sheet->setCellValue("B{$row}", $profit);

        $sheet->getStyle("A" . ($row - 2) . ":B{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // 🔹 Format angka jadi Rupiah & rata kanan
        $sheet->getStyle('F:H')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('B' . ($row - 2) . ':B' . $row)
            ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('B' . ($row - 2) . ':B' . $row)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Auto width
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 🔹 Simpan & download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-shift-' . $this->shift . '-' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path($fileName);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function exportToPdf()
    {
        // 1️⃣ Validasi tanggal
        if (!$this->tglStart) {
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Tanggal belum dipilih!',
                'text' => 'Silakan pilih tanggal terlebih dahulu.'
            ]);
            return;
        }

        // parse date (tglStart dipakai sebagai acuan shift)
        $date = Carbon::parse($this->tglStart);

        // 2️⃣ Tentukan range berdasarkan shift
        switch ($this->shift) {
            case '1': // pagi 08:00-16:00
                $start = $date->copy()->setTime(8, 0, 0);
                $end = $date->copy()->setTime(16, 0, 0);
                $title = 'Laporan Shift Pagi (' . $start->format('d M Y H:i') . ' - ' . $end->format('d M Y H:i') . ')';
                break;
            case '2': // sore 16:00-24:00
                $start = $date->copy()->setTime(16, 0, 0);
                $end = $date->copy()->setTime(23, 59, 59);
                $title = 'Laporan Shift Sore (' . $start->format('d M Y H:i') . ' - ' . $end->format('d M Y H:i') . ')';
                break;
            case '3': // malam 00:00 - 13:00 (besok)
                $start = $date->copy()->setTime(0, 0, 0);
                $end = $date->copy()->addDay()->setTime(13, 0, 0);
                $title = 'Laporan Shift Malam (' . $start->format('d M Y H:i') . ' - ' . $end->format('d M Y H:i') . ')';
                break;
            default:
                $start = Carbon::parse($this->tglStart)->startOfDay();
                $end = Carbon::parse($this->tglEnd ?? $this->tglStart)->endOfDay();
                $title = 'Laporan (' . $start->format('d M Y') . ' - ' . $end->format('d M Y') . ')';
        }

        // 3️⃣ Hitung stok awal per bahan (stok sebelum range start)
        $stok_awal = [];
        $bahanAll = Bahan::all();
        foreach ($bahanAll as $bahan) {
            // total pembelian (jumlah units) sebelum start
            $totalPembelian = Pembeliandtl::where('bahan_idbahan', $bahan->idbahan)
                ->whereHas('pembelian', fn($q) => $q->whereDate('tanggal', '<', $start->toDateString()))
                ->sum('jumlah');

            // total pengeluaran (jumlah units) sebelum start
            $totalPengeluaran = Pengeluaran::where('bahan_idbahan', $bahan->idbahan)
                ->whereDate('tanggal', '<', $start->toDateString())
                ->sum('jumlah');

            // formula: stokAwal = stok_saat_ini - pembelian_sampai_kemarin + pengeluaran_sampai_kemarin
            $stok_awal[$bahan->idbahan] = ($bahan->stok ?? 0) - $totalPembelian + $totalPengeluaran;
        }

        // 4️⃣ Ambil pembelian di range (hari/shift)
        $pembelianHariIni = Pembeliandtl::with(['bahan', 'pembelian'])
            ->whereHas('pembelian', fn($q) => $q->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]))
            ->get();

        // 5️⃣ Ambil pengeluaran wajib (gas, air, es) di range
        $bahanWajib = ['gas', 'air', 'es'];
        $bahanWajibIds = Bahan::whereIn('nama', $bahanWajib)->pluck('idbahan')->toArray();
        $pengeluaranWajib = Pengeluaran::with('bahan')
            ->whereIn('bahan_idbahan', $bahanWajibIds)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->get();

        // 6️⃣ Pembelian excluding bahan yang sudah tercatat di pengeluaran wajib (hindari double)
        $bahanPengeluaranIds = $pengeluaranWajib->pluck('bahan_idbahan')->toArray();
        $pembelianNonWajib = $pembelianHariIni->filter(fn($p) => !in_array($p->bahan_idbahan, $bahanPengeluaranIds));

        // 7️⃣ Ambil penjualan di range
        $penjualans = Penjualan::with(['penjualanDtl.produk', 'user'])
            ->whereBetween('tanggal', [$start->toDateTimeString(), $end->toDateTimeString()])
            ->get();

        // hitung total penjualan
        $totalPenjualan = $penjualans->sum('total');

        // 8️⃣ Hitung total pengeluaran (pembelianNonWajib + pengeluaranWajib)
        $totalPembelian = $pembelianNonWajib->sum(fn($d) => ($d->jumlah ?? 0) * ($d->harga_beli ?? 0));
        $totalPengeluaranWajib = $pengeluaranWajib->sum('total'); // asumsi kolom total ada
        $totalPengeluaran = $totalPembelian + $totalPengeluaranWajib;

        // 9️⃣ Profit
        $profit = $totalPenjualan - $totalPengeluaran;

        // 🔟 Headers tabel (sesuaikan jika perlu)
        $headers = ['No', 'Kode', 'Customer', 'Tanggal', 'Total', 'Bayar', 'Kembalian'];

        // 1️⃣1️⃣ Siapkan data untuk view
        $pdfData = [
            'stok_awal' => $stok_awal,
            'pembelian' => $pembelianNonWajib,         // koleksi pembelian tanpa bahan wajib
            'pengeluaran_wajib' => $pengeluaranWajib, // koleksi pengeluaran wajib
            'penjualan' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_pengeluaran' => $totalPengeluaran,
            'profit' => $profit,
        ];

        // 1️⃣2️⃣ Rincian pengeluaran untuk ringkasan (gabungkan pembelian & pengeluaran wajib bila perlu)
        $rincian_pengeluaran = collect();
        foreach ($pembelianNonWajib as $pd) {
            $rincian_pengeluaran->push([
                'sumber' => 'Pembelian',
                'bahan' => $pd->bahan->nama ?? '-',
                'jumlah' => $pd->jumlah,
                'harga' => $pd->harga_beli,
                'total' => ($pd->jumlah ?? 0) * ($pd->harga_beli ?? 0),
            ]);
        }
        foreach ($pengeluaranWajib as $pe) {
            $rincian_pengeluaran->push([
                'sumber' => 'Pengeluaran Wajib',
                'bahan' => $pe->bahan->nama ?? '-',
                'jumlah' => $pe->jumlah,
                'harga' => $pe->harga,
                'total' => $pe->total,
            ]);
        }

        // 1️⃣3️⃣ Render PDF (kirim variabel yg dipakai di Blade)
        $pdf = Pdf::loadView('layouts.pdf_laporan_penjualan_pengeluaran', [
            'title' => $title,
            'periode_start' => $start,
            'periode_end' => $end,
            'headers' => $headers,
            'data' => $pdfData,
            'stok_awal' => $stok_awal,
            'ringkasan' => [
                'total_penjualan' => $totalPenjualan,
                'total_pengeluaran' => $totalPengeluaran,
                'selisih' => $profit,
                'rincian_pengeluaran' => $rincian_pengeluaran->toArray(),
            ],
        ])->setPaper('A4', 'landscape');

        return response()->streamDownload(fn() => print ($pdf->stream()), 'laporan-shift-' . $this->shift . '-' . now()->format('Ymd_His') . '.pdf');
    }
}