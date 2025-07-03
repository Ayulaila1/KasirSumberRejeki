<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bahan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class BahanComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $idbahan, $nama, $stok = 0, $satuan, $jenis, $created_at, $updated_at;
    public $bahanName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $idbahanToDelete;

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
    public function updatingSelectedBahan()
    {
        $this->resetPage();
    }

    public function tambahBahan()
    {
        $this->reset([
            'idbahan',
            'nama',
            'stok',
            'satuan',
            'jenis',
        ]);
        $this->isOpen = true;
        $this->isEdit = false;


    }

    public function close()
    {
        $this->reset([
            'idbahan',
            'nama',
            'stok',
            'satuan',
            'jenis',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-bahan-modal');
    }

    public function storeBahan()
    {
        $this->validate([
            'nama' => 'required',
        ]);

        $bahan = new Bahan();
        $bahan->idbahan = $this->idbahan;
        $bahan->nama = $this->nama;
        $bahan->stok = 0;
        $bahan->satuan = $this->satuan;
        $bahan->jenis = $this->jenis;
        $bahan->created_at = $this->created_at;
        $bahan->updated_at = $this->updated_at;
        $bahan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('bahan-disimpan', ['pesan' => 'Bahan berhasil disimpan!']);
        $this->dispatch('close-bahan-modal');
    }

    public function editBahan($idbahan)
    {
        $bahan = Bahan::where('idbahan', $idbahan)->first();

        $this->idbahan = $bahan->idbahan;
        $this->nama = $bahan->nama;
        $this->stok = $bahan->stok;
        $this->satuan = $bahan->satuan;
        $this->jenis = $bahan->jenis;
        $this->created_at = $bahan->created_at;
        $this->updated_at = $bahan->updated_at;

        $this->isEdit = true;

        $this->dispatch('show-edit-bahan-modal');
    }

    public function updateBahan()
    {
        $this->validate([
            'nama' => 'required',
            // 'stok' => 'required|numeric',
            //'satuan' => 'required',
            //'jenis' => 'required',
            //'created_at' => 'required',
            //'updated_at' => 'required',
        ]);

        $bahan = Bahan::where('idbahan', $this->idbahan)->firstOrFail();
        $bahan->nama = $this->nama;
        $bahan->stok = $this->stok;
        $bahan->satuan = $this->satuan;
        $bahan->jenis = $this->jenis;
        $bahan->created_at = $this->created_at;
        $bahan->updated_at = $this->updated_at;
        $bahan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('bahan-disimpan', ['pesan' => 'Bahan berhasil diupdate!']);
        $this->dispatch('close-bahan-modal');
    }



    public function deleteConfirmationBahan($idbahan)
    {
        $this->idbahanToDelete = $idbahan;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusBahan')]
    public function deleteBahan()
    {
        $bahan = Bahan::where('idbahan', $this->idbahanToDelete)->first();

        if (!$bahan) {
            $this->dispatch('bahan-error', ['pesan' => 'Bahan tidak ditemukan!']);
            return;
        }

        $bahan->delete();
        $this->reset('idbahanToDelete');

        $this->dispatch('bahan-disimpan', ['pesan' => 'Bahan berhasil dihapus!']);
        $this->dispatch('close-bahan-modal');
    }



    public function dataBahan()
    {
        return Bahan::search($this->search)
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.bahan-component', [
            'bahans' => $this->dataBahan()
        ]);
    }
}
