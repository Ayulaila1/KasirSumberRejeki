<?php

namespace App\Livewire;

use App\Models\Bahan;
use Livewire\Component;

class BahanLov extends Component
{
    public $isOpen = false;
    public $search = '';

    protected $listeners = ['buka-modal-lov-bahan' => 'buka'];

    public function buka()
    {
        $this->isOpen = true;
    }

    public function tutup()
    {
        $this->isOpen = false;
    }

    public function pilih($id)
    {
        $bahan = Bahan::find($id);
        $this->dispatch('bahanDipilih', $bahan->idbahan);
        $this->isOpen = false;
    }

    public function render()
    {
        $bahans = Bahan::where('nama', 'like', '%' . $this->search . '%')->get();

        return view('livewire.bahan-lov', [
            'bahans' => $bahans
        ]);
    }
}
