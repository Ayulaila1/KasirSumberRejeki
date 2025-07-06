<?php

namespace App\Livewire;

use id;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Pembelian;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PembelianComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $idpembelian, $tanggal, $supplier_idsupplier, $user_iduser, $created_at, $updated_at;
    public $supplierName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $idpembelianToDelete;

    public function mount()
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->tanggal = now()->format('Y-m-d');
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
    public function updatingSelectedPembelian()
    {
        $this->resetPage();
    }

    public function tambahPembelian()
    {
        $this->reset([
            'idpembelian',
            'tanggal',
            'supplier_idsupplier',
            'user_iduser',
            'created_at',
            'updated_at'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;

    }

    public function close()
    {
        $this->reset([
            'idpembelian',
            'tanggal',
            'supplier_idsupplier',
            'user_iduser',
            'created_at',
            'updated_at',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->tanggal = now()->format('Y-m-d');
        $this->dispatch('close-pembelian-modal');
    }

    public function storePembelian()
    {
        $this->validate([
            'tanggal' => 'required',
        ]);

        $pembelian = new Pembelian();
        $pembelian->idpembelian = $this->idpembelian;
        $pembelian->tanggal = $this->tanggal;
        $pembelian->supplier_idsupplier = $this->supplier_idsupplier;
        $pembelian->user_iduser = Auth::user()->id;
        $pembelian->status = 'unsaved';
        $pembelian->created_at = $this->created_at;
        $pembelian->updated_at = $this->updated_at;
        $pembelian->save();

        return redirect('/pembeliandtl/' . $pembelian->idpembelian);


        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('pembelian-disimpan', ['pesan' => 'Pembelian berhasil disimpan!']);
        $this->dispatch('close-pembelian-modal');
    }

    public function editPembelian($idpembelian)
    {
        $pembelian = Pembelian::where('idpembelian', $idpembelian)->first();

        $this->idpembelian = $pembelian->idpembelian;
        $this->tanggal = $pembelian->tanggal;
        $this->supplier_idsupplier = $pembelian->supplier_idsupplier;
        $this->supplierName = $pembelian->supplier->nama ?? '-';
        $this->user_iduser = $pembelian->user_iduser;
        $this->created_at = $pembelian->created_at;
        $this->updated_at = $pembelian->updated_at;

        $this->isEdit = true;

        $this->dispatch('show-edit-pembelian-modal');
    }

    public function updatePembelian()
    {
        $this->validate([
            'tanggal' => 'required',
            'supplier_idsupplier' => 'required',
            'user_iduser' => 'required',
            //'created_at' => 'required',
            //'updated_at' => 'required',
        ]);

        $pembelian = Pembelian::where('idpembelian', $this->idpembelian)->firstOrFail();
        $pembelian->tanggal = $this->tanggal;
        $pembelian->supplier_idsupplier = $this->supplier_idsupplier;
        $pembelian->user_iduser = $this->user_iduser;
        $pembelian->created_at = $this->created_at;
        $pembelian->updated_at = $this->updated_at;
        $pembelian->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('pembelian-disimpan', ['pesan' => 'Pembelian berhasil diupdate!']);
        $this->dispatch('close-pembelian-modal');
    }



    public function deleteConfirmationPembelian($idpembelian)
    {
        $this->idpembelianToDelete = $idpembelian;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusPembelian')]
    public function deletePembelian()
    {
        $pembelian = Pembelian::where('idpembelian', $this->idpembelianToDelete)->first();

        if (!$pembelian) {
            $this->dispatch('pembelian-error', ['pesan' => 'Pembelian tidak ditemukan!']);
            return;
        }

        $pembelian->delete();
        $this->reset('idpembelianToDelete');

        $this->dispatch('pembelian-disimpan', ['pesan' => 'Pembelian berhasil dihapus!']);
        $this->dispatch('close-pembelian-modal');
    }

    #[On('buka-modal-lov-supplier')]
    public function bukaModal()
    {
        $this->isOpen = true;
    }

    #[On('supplierDipilih')]
    public function supplierLov($id)
    {
        $supplier = Supplier::find($id);
        $this->supplier_idsupplier = $supplier->idsupplier;
        $this->supplierName = $supplier->nama;
    }

    public function exportToPdf()
    {
        $headers = ['Tanggal', 'Supplier', 'User', 'Total Item', 'Total Harga Beli'];
        $title = 'Export Data Pembelian';
        $queryResult = $this->dataPembelian();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->tanggal, $result->supplier->nama ?? '-', $result->user->name ?? '-', $result->total_item, $result->total_hargabeli];
        }
        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));

        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Pembelian.pdf');
    }

    public function dataPembelian()
    {
        return Pembelian::search($this->search)
            ->with(['supplier', 'user']) // Eager load supplier and user relationships
            ->rangeTanggal($this->tglstart, $this->tglend) //aktifkan kalau pake filter tanggal
            ->simplePaginate($this->perPage);
    }


    public function render()
    {
        return view('livewire.pembelian-component', [
            'pembelians' => $this->dataPembelian()
        ]);
    }
}
