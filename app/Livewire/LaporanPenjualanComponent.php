<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenjualan;
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
        $laporanRaw = LaporanPenjualan::query()
            ->when($this->tglStart && $this->tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$this->tglStart, $this->tglEnd]))
            ->when($this->search, fn($q) =>
                $q->where('nama_produk', 'like', "%{$this->search}%"))
            ->orderByDesc('tanggal')
            ->get();

        // Gabung produk berdasarkan kode_penjualan
        $grouped = $laporanRaw->groupBy('kode_penjualan')->map(function ($items) {
            $first = $items->first();
            return (object) [
                'tanggal' => $first->tanggal,
                'kode_penjualan' => $first->kode_penjualan,
                // Produk ditampilkan per baris
                'produk' => $items->pluck('nama_produk')->implode(', '),
                'qty' => $items->sum('qty'),
                'harga_jual' => $items->avg('harga_jual'),
                'subtotal' => $items->sum('subtotal'),
                'total' => $first->total,
                'bayar' => $first->bayar,
                'kembalian' => $first->kembalian,
                'user_id' => $first->user_id,
            ];
        })->values();

        // Biar tetap bisa pakai firstItem(), lastItem(), dll → manual paginate Collection
        $page = request('page', 1);
        $perPage = $this->perPage;
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($page, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.laporan-penjualan-component', [
            'laporan' => $laporan
        ]);
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
