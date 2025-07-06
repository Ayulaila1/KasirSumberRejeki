<?php

namespace App\Livewire;

use App\Models\Bahan;
use Livewire\Component;
use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ProdukRacikan;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class ProdukRacikanComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $idproduk_racikan, $produk_idproduk, $bahan_idbahan, $takaran, $satuan, $created_at, $updated_at;
    public $bahanName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $idproduk_racikanToDelete;
    public $idPage;

    public function mount($id)
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->idPage = $id;
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
    public function updatingSelectedProdukRacikan()
    {
        $this->resetPage();
    }

    public function tambahProdukRacikan()
    {
        $this->reset([
            'idproduk_racikan',
            'produk_idproduk',
            'bahan_idbahan',
            'takaran',
            'satuan'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;


    }

    public function close()
    {
        $this->reset([
            'idproduk_racikan',
            'produk_idproduk',
            'bahan_idbahan',
            'takaran',
            'satuan',
            'isOpen',
            'isEdit'
        ]);
        $this->resetExcept(['idPage']);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-produk-racikan-modal');
    }

    public function storeProdukRacikan()
    {
        $this->validate([
            // 'produk_idproduk' => 'required',
            'bahan_idbahan' => 'required',
            // 'takaran' => 'required',
            // 'satuan' => 'required',
        ]);

        $produkracikan = new ProdukRacikan();
        $produkracikan->idproduk_racikan = $this->idproduk_racikan;
        $produkracikan->produk_idproduk = $this->idPage;
        $produkracikan->bahan_idbahan = $this->bahan_idbahan;
        $produkracikan->takaran = $this->takaran;
        $produkracikan->satuan = $this->satuan;
        $produkracikan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('produk-racikan-disimpan', ['pesan' => 'ProdukRacikan berhasil disimpan!']);
        $this->dispatch('close-produk-racikan-modal');
    }

    public function editProdukRacikan($idproduk_racikan)
    {
        $produkracikan = ProdukRacikan::where('idproduk_racikan', $idproduk_racikan)->first();

        $this->idproduk_racikan = $produkracikan->idproduk_racikan;
        $this->produk_idproduk = $produkracikan->produk_idproduk;
        $this->bahan_idbahan = $produkracikan->bahan_idbahan;
        $this->takaran = $produkracikan->takaran;
        $this->satuan = $produkracikan->satuan;

        $this->isEdit = true;

        $this->dispatch('show-edit-produk-racikan-modal');
    }

    public function updateProdukRacikan()
    {
        $this->validate([
            // 'produk_idproduk' => 'required',
            'bahan_idbahan' => 'required',
            // 'takaran' => 'required',
        ]);

        $produkracikan = ProdukRacikan::where('idproduk_racikan', $this->idproduk_racikan)->firstOrFail();
        $produkracikan->produk_idproduk = $this->produk_idproduk;
        $produkracikan->bahan_idbahan = $this->bahan_idbahan;
        $produkracikan->takaran = $this->takaran;
        $produkracikan->satuan = $this->satuan;
        $produkracikan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('produk-racikan-disimpan', ['pesan' => 'Produk Racikan berhasil diupdate!']);
        $this->dispatch('close-produk-racikan-modal');
    }

    public function deleteConfirmationProdukRacikan($idproduk_racikan)
    {
        $this->idproduk_racikanToDelete = $idproduk_racikan;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusProdukRacikan')]
    public function deleteProdukRacikan()
    {
        $produkracikan = ProdukRacikan::where('idproduk_racikan', $this->idproduk_racikanToDelete)->first();

        if (!$produkracikan) {
            $this->dispatch('produk-racikan-error', ['pesan' => 'Produk Racikan tidak ditemukan!']);
            return;
        }

        $produkracikan->delete();
        $this->reset('idproduk_racikanToDelete');

        $this->dispatch('produk-racikan-disimpan', ['pesan' => 'Produk Racikan berhasil dihapus!']);
        $this->dispatch('close-produk-racikan-modal');
    }

    #[On('buka-modal-lov-bahan')]
    public function bukaModal()
    {
        $this->isOpen = true;
    }

    #[On('bahanDipilih')]
    public function bahanLov($id)
    {
        $bahan = Bahan::find($id);
        $this->bahan_idbahan = $bahan->idbahan;
        $this->bahanName = $bahan->nama;
    }

    public function exportToPdf()
    {
        $headers = ['Bahan', 'Takaran', 'Satuan'];
        $title = 'Export Data Produk Racikan';
        $queryResult = $this->dataProdukRacikan();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->bahan->nama, $result->takaran, $result->satuan];
        }
        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));

        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'ProdukRacikan.pdf');
    }

    public function dataProdukRacikan()
    {
        return ProdukRacikan::search($this->search)
            ->with(['bahan'])
            ->where('produk_idproduk', $this->idPage)
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        // $produks = ProdukRacikan::where('idproduk_racikan', $this->idPage)->first();
        return view('livewire.produk-racikan-component', [
            'produkracikans' => $this->dataProdukRacikan(),
            // 'produks' => $produks,
        ]);
    }
}
