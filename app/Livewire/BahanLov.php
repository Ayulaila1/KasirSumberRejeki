<?php

namespace App\Livewire;

use App\Models\Bahan;
use Livewire\Component;
use Livewire\WithPagination; // 1. Tambahkan trait pagination


class BahanLov extends Component
{
    use WithPagination; // 2. Gunakan trait
    public $isOpen = false;
    public $search = '';
    public $searchlov = '';

    protected $listeners = ['buka-modal-lov-bahan' => 'buka'];

    // Method ini akan dijalankan setiap kali 'search' di-update
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
        $this->search = ''; // Bersihkan input pencarian
        $this->resetPage(); // Reset halaman
    }

    public function pilih($id)
    {
        $bahan = Bahan::find($id);
        if ($bahan) {
            // Kirim juga nama bahan untuk UX yang lebih baik
            $this->dispatch('bahanDipilih', $bahan->idbahan, $bahan->nama);
        }
        $this->tutup();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        // 3. Perbaiki query agar mencari di beberapa kolom
        $bahans = Bahan::where(function ($query) use ($searchTerm) {
            $query->where('nama', 'like', $searchTerm)
                ->orWhere('idbahan', 'like', $searchTerm)
                ->orWhere('jenis', 'like', $searchTerm)
                ->orWhere('satuan', 'like', $searchTerm);
        })
            ->latest()
            ->paginate(5); // 4. Gunakan pagination

        return view('livewire.bahan-lov', [
            'bahans' => $bahans
        ]);
    }
}
