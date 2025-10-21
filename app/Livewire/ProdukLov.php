<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;
use Livewire\WithPagination; // 1. Tambahkan ini


class ProdukLov extends Component
{
    use WithPagination; // 2. Gunakan trait ini

    public $isOpen = false;
    public $search = '';
    // public $searchlov = '';

    // Lindungi properti agar tidak mudah diubah dari luar
    // protected $queryString = ['search'];
    protected $listeners = ['buka-modal-lov-produk' => 'buka'];

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatingSearchlov()
    {
        $this->resetPage();
        $this->resetPage('pageLOV');
    }

    public function buka()
    {
        $this->isOpen = true;
    }

    public function tutup()
    {
        $this->isOpen = false;
        $this->search = ''; // Bersihkan pencarian saat modal ditutup
        $this->resetPage();
    }

    public function pilih($id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            // Kirim juga nama produk agar bisa langsung ditampilkan tanpa query tambahan
            $this->dispatch('produkDipilih', $produk->idproduk, $produk->nama);
        }
        $this->tutup(); // Panggil fungsi tutup agar search juga bersih
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        // 1. Perbaiki query pencarian dan view yang dituju
        $produks = Produk::with('supplier') // 2. Tambahkan Eager Loading untuk efisiensi
            ->where(function ($query) use ($searchTerm) {
                // Sesuaikan kolom pencarian dengan yang ada di tabel produk
                $query->where('nama', 'like', $searchTerm)
                    ->orWhere('idproduk', 'like', $searchTerm)
                    ->orWhereHas('supplier', function ($q) use ($searchTerm) { // Cari juga berdasarkan nama supplier
                    $q->where('nama', 'like', $searchTerm);
                });
            })
            ->latest()
            ->paginate(5);

        // 3. Pastikan me-return view yang benar
        return view('livewire.produk-lov', [
            'produks' => $produks,
        ]);
    }
}
