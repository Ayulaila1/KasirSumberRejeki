<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Penjualan;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;

class LaporanPenjualanComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $tglStart;
    public $tglEnd, $perPage = 10;

    public function mount()
    {
        $this->tglStart = now()->startOfMonth()->format('Y-m-d');
        $this->tglEnd = now()->endOfMonth()->format('Y-m-d');
    }
    public function render()
    {
        $laporan = Penjualan::with('penjualanDtl.produk')
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$this->tglStart, $this->tglEnd]))
            ->when($this->search, fn($q) =>
                $q->whereHas('penjualanDtl.produk', fn($q2) =>
                    $q2->where('nama', 'like', "%{$this->search}%")))
            ->orderByDesc('tanggal')
            ->paginate($this->perPage);

        return view('livewire.laporan-penjualan-component', compact('laporan'));
    }


    public function exportToPdf()
    {
        if (!$this->tglStart || !$this->tglEnd) {
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Tanggal belum dipilih!',
                'text' => 'Silakan pilih rentang tanggal terlebih dahulu.'
            ]);
            return;
        }

        $headers = ['Tanggal', 'Kode', 'Produk', 'Qty', 'Harga Jual', 'Subtotal', 'Total', 'Bayar', 'Kembalian', 'User'];
        $title = 'Laporan Penjualan';

        $queryResult = LaporanPenjualan::query()
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$this->tglStart, $this->tglEnd]))
            ->when($this->search, fn($q) =>
                $q->where('nama_produk', 'like', "%{$this->search}%"))
            ->orderByDesc('tanggal')
            ->paginate($this->perPage);

        $data = [];
        foreach ($queryResult as $item) {
            $data[] = [
                Carbon::parse($item->tanggal)->format('d M Y'),
                $item->kode_penjualan,
                $item->nama_produk,
                $item->qty,
                $item->harga_jual,
                $item->subtotal,
                $item->total,
                $item->bayar,
                $item->kembalian,
                $item->user_id,
            ];
        }

        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-penjualan.pdf');
    }


}
