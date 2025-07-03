<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class SupplierComponent extends Component
{
    use WithPagination, WithFileUploads;
    // use LivewireAlert;

    public $idsupplier, $nama, $kontak, $alamat, $created_at, $updated_at;
    public $supplierName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $idsupplierToDelete;

    public function mount()
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSearchlov()
    {
        $this->resetPage();
        $this->resetPage('pageLOV');
    }
    public function updatingPerPage()
    {
        $this->resetPage();
        $this->resetPage('pageLOV');
    }
    public function updatingTglstart()
    {
        $this->resetPage();
    }
    public function updatingTglend()
    {
        $this->resetPage();
    }
    public function updatingSelectedSupplier()
    {
        $this->resetPage();
    }

    public function tambahSupplier()
    {
        $this->reset([
            'idsupplier',
            'nama',
            'kontak',
            'alamat'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;


    }

    public function close()
    {
        $this->reset([
            'idsupplier',
            'nama',
            'kontak',
            'alamat',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-supplier-modal');
    }

    public function storeSupplier()
    {
        $this->validate([
            'nama' => 'required',
            // 'harga_jual' => 'required',
            // 'harga_beli' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->idsupplier = $this->idsupplier;
        $supplier->nama = $this->nama;
        $supplier->kontak = $this->kontak;
        $supplier->alamat = $this->alamat;
        $supplier->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('supplier-disimpan', ['pesan' => 'Supplier berhasil disimpan!']);
        $this->dispatch('close-supplier-modal');
    }

    public function editSupplier($idsupplier)
    {
        $supplier = Supplier::where('idsupplier', $idsupplier)->first();

        $this->idsupplier = $supplier->idsupplier;
        $this->nama = $supplier->nama;
        $this->kontak = $supplier->kontak;
        $this->alamat = $supplier->alamat;

        $this->isEdit = true;

        $this->dispatch('show-edit-supplier-modal');
    }

    public function updateSupplier()
    {
        $this->validate([
            'nama' => 'required',
            // 'kontak' => 'required',
            // 'alamat' => 'required',
        ]);

        $supplier = Supplier::where('idsupplier', $this->idsupplier)->firstOrFail();
        $supplier->nama = $this->nama;

        $supplier->kontak = $this->kontak;
        $supplier->alamat = $this->alamat;
        $supplier->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('supplier-disimpan', ['pesan' => 'Supplier berhasil diupdate!']);
        $this->dispatch('close-supplier-modal');
    }



    public function deleteConfirmationSupplier($idsupplier)
    {
        $this->idsupplierToDelete = $idsupplier;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusSupplier')]
    public function deleteSupplier()
    {
        $supplier = Supplier::where('idsupplier', $this->idsupplierToDelete)->first();

        if (!$supplier) {
            $this->dispatch('supplier-error', ['pesan' => 'Supplier tidak ditemukan!']);
            return;
        }

        $supplier->delete();
        $this->reset('idsupplierToDelete');

        $this->dispatch('supplier-disimpan', ['pesan' => 'Supplier berhasil dihapus!']);
        $this->dispatch('close-supplier-modal');
    }

    public function supplierLOV($id)
    {
        $supplier = Supplier::where('idsupplier', $id)->first();
        // $this->nama_relasi = $supplier ->idsupplier;  
        $this->supplierName = $supplier->supplier;
        $this->alert('success', 'Terpilih : ' . $supplier->supplier);
        $this->resetPage('pageLOV');

        $this->dispatch('close-modal-lov');
    }



    public function dataSupplier()
    {
        return Supplier::search($this->search)
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.supplier-component', [
            'suppliers' => $this->dataSupplier()
        ]);
    }
}
