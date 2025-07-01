<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kasir;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class KasirComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $ikasir, $nama, $created_at, $updated_at;

    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $ikasirToDelete;

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
    public function updatingSelectekasir()
    {
        $this->resetPage();
    }

    public function tambahKasir()
    {
        $this->reset([
            'ikasir',
            'nama'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;


    }

    public function close()
    {
        $this->reset([
            'ikasir',
            'nama',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-kasir-modal');
    }

    public function storeKasir()
    {
        $this->validate([
            'nama' => 'required',
        ]);

        $kasir = new Kasir();
        $kasir->idkasir = $this->idkasir;
        $kasir->nama = $this->nama;
        $kasir->created_at = $this->created_at;
        $kasir->updated_at = $this->updated_at;
        $kasir->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('kasir-disimpan', ['pesan' => 'kasir berhasil disimpan!']);
        $this->dispatch('close-kasir-modal');
    }

    public function editkasir($idkasir)
    {
        $kasir = Kasir::where('idkasir', $idkasir)->first();

        $this->idkasir = $kasir->idkasir;
        $this->nama = $kasir->nama;
        $this->created_at = $kasir->created_at;
        $this->updated_at = $kasir->updated_at;

        $this->isEdit = true;

        $this->dispatch('show-edit-kasir-modal');
    }

    public function updateKasir()
    {
        $this->validate([
            'nama' => 'required',
            //'created_at' => 'required',
            //'updated_at' => 'required',
        ]);

        $kasir = Kasir::where('idkasir', $this->idkasir)->firstOrFail();
        $kasir->nama = $this->nama;
        $kasir->created_at = $this->created_at;
        $kasir->updated_at = $this->updated_at;
        $kasir->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('kasir-disimpan', ['pesan' => 'kasir berhasil diupdate!']);
        $this->dispatch('close-kasir-modal');
    }



    public function deleteConfirmationkasir($idkasir)
    {
        $this->idKasirToDelete = $idkasir;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapuskasir')]
    public function deleteKasir()
    {
        $kasir = Kasir::where('idkasir', $this->idKasirToDelete)->first();

        if (!$kasir) {
            $this->dispatch('kasir-error', ['pesan' => 'kasir tidak ditemukan!']);
            return;
        }

        $kasir->delete();
        $this->reset('idKasirToDelete');

        $this->dispatch('kasir-disimpan', ['pesan' => 'kasir berhasil dihapus!']);
        $this->dispatch('close-kasir-modal');
    }



    public function dataKasir()
    {
        return Kasir::search($this->search)
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.kasir-component', [
            'kasirs' => $this->datakasir()
        ]);
    }
}
