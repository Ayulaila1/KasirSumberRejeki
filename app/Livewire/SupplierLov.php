<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination; // 1. Tambahkan ini

class SupplierLov extends Component
{
    use WithPagination; // 2. Gunakan trait ini
    public $isOpen = false;
    public $search = '';
    public $searchlov = '';

    // Lindungi properti agar tidak mudah diubah dari luar
    protected $queryString = ['search'];
    protected $listeners = ['buka-modal-lov-supplier' => 'buka'];

    // Method ini akan dijalankan setiap kali properti 'search' di-update
    public function updatedSearch()
    {
        // Reset halaman ke 1 setiap kali melakukan pencarian baru
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
        $supplier = Supplier::find($id);
        if ($supplier) {
            $this->dispatch('supplierDipilih', $supplier->idsupplier, $supplier->nama);
        }
        $this->tutup(); // Panggil fungsi tutup agar search juga bersih
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        // 3. Ubah query agar lebih optimal dan bisa mencari di beberapa kolom
        $suppliers = Supplier::where(function ($query) use ($searchTerm) {
            $query->where('nama', 'like', $searchTerm)
                ->orWhere('kontak', 'like', $searchTerm)
                ->orWhere('idsupplier', 'like', $searchTerm);
        })
            ->latest() // Urutkan berdasarkan data terbaru
            ->paginate(5); // 4. Gunakan pagination agar lebih ringan

        return view('livewire.supplier-lov', [
            'suppliers' => $suppliers
        ]);
    }
}
