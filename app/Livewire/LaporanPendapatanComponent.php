<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPendapatan;

class LaporanPendapatanComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $tglStart;
    public $tglEnd;
    public $perPage = 10;

    public function mount()
    {
        $this->tglStart = now()->startOfMonth()->format('Y-m-d');
        $this->tglEnd = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $start = date('Y-m', strtotime($this->tglStart));
        $end = date('Y-m', strtotime($this->tglEnd));

        $laporan = LaporanPendapatan::query()
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('bulan', [$start, $end]))
            ->when($this->search, fn($q) =>
                $q->where('bulan', 'like', "%{$this->search}%"))
            ->orderByDesc('bulan')
            ->paginate($this->perPage);

        return view('livewire.laporan-pendapatan-component', compact('laporan'));
    }

    public function exportToPdf()
    {
        $start = date('Y-m', strtotime($this->tglStart));
        $end = date('Y-m', strtotime($this->tglEnd));

        $headers = ['Bulan', 'Total Pendapatan', 'Total Penjualan', 'Total Modal', 'Keuntungan', 'Kerugian'];
        $title = 'Laporan Pendapatan Bulanan';

        $queryResult = LaporanPendapatan::whereBetween('bulan', [$start, $end])
            ->orderBy('bulan', 'desc')
            ->get();

        $data = [];
        foreach ($queryResult as $item) {
            $data[] = [
                $item->bulan,
                number_format($item->total_pendapatan, 0, ',', '.'),
                number_format($item->total_penjualan, 0, ',', '.'),
                number_format($item->total_modal, 0, ',', '.'),
                number_format($item->keuntungan, 0, ',', '.'),
                number_format($item->kerugian, 0, ',', '.'),
            ];
        }

        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'))
            ->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-pendapatan-bulanan.pdf');
    }
}
