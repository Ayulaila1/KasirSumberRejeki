<?php

namespace App\Livewire;

use App\Models\Hold;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\ProdukRacikan;
use Livewire\Component;
use Livewire\WithPagination;

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
        $holds = Hold::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('customer', 'like', '%' . $this->search . '%')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.hold-component', compact('holds'));
    }

    public function lanjutkan($id)
    {
        return redirect()->route('kasir.resume', ['id' => $id]);
    }
}
