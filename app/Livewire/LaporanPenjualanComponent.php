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
    public $page = 1;

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
                $q->where(function ($q2) {
                    $q2->where('kode_penjualan', 'like', "%{$this->search}%")
                        ->orWhere('customer_name', 'like', "%{$this->search}%")
                        ->orWhereHas('penjualanDtl.produk', fn($q3) =>
                            $q3->where('nama', 'like', "%{$this->search}%"));
                }))
            ->orderByDesc('tanggal')
            ->rangeTanggal($this->tglStart, $this->tglEnd)
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

        $headers = ['Tanggal', 'Kode', 'Produk', 'Qty', 'Harga Jual', 'Subtotal', 'Total Penjualan', 'Bayar', 'Kembalian', 'User'];
        $title = 'Laporan Penjualan (Halaman ' . $this->page . ')';

        // ambil data sesuai page yg sedang aktif
        $penjualans = Penjualan::with('penjualanDtl.produk', 'user')
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$this->tglStart, $this->tglEnd]))
            ->when($this->search, fn($q) =>
                $q->where(function ($q2) {
                    $q2->where('kode_penjualan', 'like', "%{$this->search}%")
                        ->orWhere('customer_name', 'like', "%{$this->search}%")
                        ->orWhereHas('penjualanDtl.produk', fn($q3) =>
                            $q3->where('nama', 'like', "%{$this->search}%"));
                }))
            ->orderByDesc('tanggal')
            ->paginate($this->perPage, ['*'], 'page', $this->page); // <= penting

        $data = [];
        $grandTotal = 0;

        foreach ($penjualans as $p) {
            foreach ($p->penjualanDtl as $item) {
                $data[] = [
                    Carbon::parse($p->tanggal)->format('d M Y'),
                    $p->kode_penjualan,
                    $item->produk->nama,
                    $item->qty,
                    number_format($item->harga_jual, 0, ',', '.'),
                    number_format($item->subtotal, 0, ',', '.'),
                    number_format($p->total, 0, ',', '.'),
                    number_format($p->bayar, 0, ',', '.'),
                    number_format($p->kembalian, 0, ',', '.'),
                    $p->user->name ?? '-',
                ];
            }

            $grandTotal += $p->total;
        }

        $pdf = Pdf::loadView('layouts.pdf_layout', [
            'data' => $data,
            'headers' => $headers,
            'title' => $title,
            'showTotal' => true,
            'grandTotal' => $grandTotal,
        ])->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-penjualan-halaman-' . $this->page . '.pdf');
    }



}
