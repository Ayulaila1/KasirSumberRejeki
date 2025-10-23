<?php

namespace App\Livewire;

use App\Models\Hold;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\ProdukRacikan;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf; // TAMBAHKAN INI

class HoldComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $products = [];

    public function resume($id)
    {
        $hold = Hold::findOrFail($id);

        // Kirim data ke kasir
        session()->put('kasir.cart', $hold->items);
        session()->put('kasir.customer', $hold->customer);

        $hold->delete();

        return redirect()->route('kasir-component')->with('success', 'Transaksi berhasil dilanjutkan!');
    }

    /**
     * Kembalikan stok bahan dari produk racikan yang dihapus
     */
    protected function restoreStockForItem($item)
    {
        // Ambil ID produk dari item hold
        $produkId = $item['produk_id'] ?? $item['id'] ?? null;
        if (!$produkId)
            return;

        $produk = Produk::find($produkId);
        if (!$produk)
            return;

        // Ambil daftar bahan berdasarkan produk_idproduk
        $racikanItems = ProdukRacikan::where('produk_idproduk', $produk->idproduk ?? $produk->id)->get();

        foreach ($racikanItems as $racikan) {
            $bahan = Bahan::find($racikan->bahan_idbahan);
            if ($bahan) {
                // Hitung jumlah bahan yang dikembalikan
                $jumlahDikembalikan = $racikan->takaran * ($item['qty'] ?? 1);

                // Tambahkan stok bahan
                $bahan->stok = $bahan->stok + $jumlahDikembalikan;
                $bahan->save();
            }
        }
    }

    public function delete($id)
    {
        $hold = Hold::findOrFail($id);

        // Decode JSON items
        $items = is_string($hold->items) ? json_decode($hold->items, true) : $hold->items;

        // Jika items valid, kembalikan stok bahan racikan
        if (is_array($items)) {
            foreach ($items as $item) {
                $this->restoreStockForItem($item);
            }
        }

        // Hapus hold
        $hold->delete();

        // Refresh data produk
        $this->products = Produk::all();

        session()->flash('success', 'Hold berhasil dihapus dan stok bahan racikan dikembalikan.');
    }

    public function render()
    {
        // 1. Ambil data, jangan dipaginasi
        $holdsData = Hold::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('customer', 'like', '%' . $this->search . '%')
            )
            ->orderBy('created_at', 'desc')
            ->get(); // Ubah dari paginate() menjadi get()

        // 2. Kelompokkan berdasarkan customer
        $groupedHolds = $holdsData->groupBy(function ($item) {
            // Kelompokkan customer yang null/kosong ke 'Tanpa Customer'
            return $item->customer ?? 'Tanpa Customer';
        })->sortKeys(); // Urutkan berdasarkan nama customer

        // 3. Kirim data yang sudah dikelompokkan ke view
        return view('livewire.hold-component', [
            'groupedHolds' => $groupedHolds
        ]);
    }

    public function lanjutkan($id)
    {
        return redirect()->route('kasir.resume', ['id' => $id]);
    }

    // TAMBAHKAN FUNGSI BARU UNTUK PRINT PDF
    public function printPdf()
    {
        // 1. Ambil data (logika yang sama dengan render)
        $holdsData = Hold::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('customer', 'like', '%' . $this->search . '%')
            )
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedHolds = $holdsData->groupBy(function ($item) {
            return $item->customer ?? 'Tanpa Customer';
        })->sortKeys();

        // 2. Load view PDF dengan data
        $pdf = Pdf::loadView('pdf.hold-report', [
            'groupedHolds' => $groupedHolds,
            'tanggalCetak' => now()->format('d/m/Y H:i')
        ]);

        // 3. Berikan nama file dan kirim sebagai download
        $namaFile = 'laporan-hold-' . now()->format('Y-m-d') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $namaFile);
    }
}
