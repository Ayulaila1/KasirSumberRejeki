<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;

class ProdukLov extends Component
{
    public $isOpen = false;
    public $search = '';

    protected $listeners = ['buka-modal-lov-produk' => 'buka'];

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
        $produk = Produk::find($id);
        $this->dispatch('produkDipilih', $produk->idproduk);
        $this->isOpen = false;
    }

    public function render()
    {
        $produks = Produk::where('nama', 'like', '%' . $this->search . '%')->get();

        return view('livewire.produk-lov', [
            'produks' => $produks
        ]);
    }
}
