<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;

class SupplierLov extends Component
{
    public $isOpen = false;
    public $search = '';

    protected $listeners = ['buka-modal-lov-supplier' => 'buka'];

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
        $supplier = Supplier::find($id);
        $this->dispatch('supplierDipilih', $supplier->idsupplier);
        $this->isOpen = false;
    }

    public function render()
    {
        $suppliers = Supplier::where('nama', 'like', '%' . $this->search . '%')->get();

        return view('livewire.supplier-lov', [
            'suppliers' => $suppliers
        ]);
    }
}
