<?php

namespace App\Livewire;

use App\Models\Bahan;
use App\Models\Produk;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ReturTitipan;
use Livewire\WithPagination;
use App\Models\ProdukRacikan;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReturTitipanComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $idretur_titipan, $tanggal, $supplier_idsupplier, $produk_idproduk, $qty, $keterangan, $created_at, $updated_at;
    public $produkName;
    public $tanggalBaru, $produk_idBaru, $produkNameBaru, $qtyBaru, $keteranganBaru;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;
    public $idretur_titipanToDelete;

    public function mount($idproduk = null)
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');

        if ($idproduk) {
            $produk = Produk::find($idproduk);
            if ($produk) {
                $this->produk_idBaru = $produk->idproduk;
                $this->produkNameBaru = $produk->nama;
            }
        }
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
    public function updatingSelectedReturTitipan()
    {
        $this->resetPage();
    }

    public function tambahReturTitipan()
    {
        $this->reset([
            'idretur_titipan',
            'tanggal',
            'supplier_idsupplier',
            'produk_idproduk',
            'qty',
            'keterangan'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;

    }

    public function close()
    {
        $this->reset([
            'idretur_titipan',
            'tanggal',
            'supplier_idsupplier',
            'produk_idproduk',
            'qty',
            'keterangan',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-retur-titipan-modal');
    }

    public function storeReturTitipan()
    {
        $this->validate([
            //'idretur_titipan' => 'required',
            'tanggal' => 'required',
            // 'supplier_idsupplier' => 'required',
            // 'produk_idproduk' => 'required',
            // 'qty' => 'required',
            // 'keterangan' => 'required',
        ]);

        $user = Auth::user();

        $returtitipan = new ReturTitipan();
        $returtitipan->idretur_titipan = $this->idretur_titipan;
        $returtitipan->tanggal = $this->tanggal;
        $returtitipan->supplier_idsupplier = $this->supplier_idsupplier;
        $returtitipan->produk_idproduk = $this->produk_idproduk;
        $returtitipan->qty = $this->qty;
        $returtitipan->keterangan = $this->keterangan;
        $returtitipan->user_iduser = $user->id;
        $returtitipan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('retur-titipan-disimpan', ['pesan' => 'ReturTitipan berhasil disimpan!']);
        $this->dispatch('close-retur-titipan-modal');
    }

    public function editReturTitipan($idretur_titipan)
    {
        $returtitipan = ReturTitipan::where('idretur_titipan', $idretur_titipan)->first();
        $this->tanggal = $returtitipan->tanggal;
        $this->supplier_idsupplier = $returtitipan->supplier_idsupplier;
        $this->produk_idproduk = $returtitipan->produk_idproduk;
        $this->qty = $returtitipan->qty;
        $this->keterangan = $returtitipan->keterangan;

        $this->isEdit = true;

        $this->dispatch('show-edit-retur-titipan-modal');
    }

    public function updateReturTitipan()
    {
        $this->validate([
            //'idretur_titipan' => 'required',
            'tanggal' => 'required',
            // 'supplier_idsupplier' => 'required',
            // 'produk_idproduk' => 'required',
            // 'qty' => 'required',
            // 'keterangan' => 'required',
        ]);

        $returtitipan = ReturTitipan::where('idretur_titipan', $this->idretur_titipan)->firstOrFail();
        $returtitipan->idretur_titipan = $this->idretur_titipan;
        $returtitipan->tanggal = $this->tanggal;
        $returtitipan->supplier_idsupplier = $this->supplier_idsupplier;
        $returtitipan->produk_idproduk = $this->produk_idproduk;
        $returtitipan->qty = $this->qty;
        $returtitipan->keterangan = $this->keterangan;
        $returtitipan->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('retur-titipan-disimpan', ['pesan' => 'Produk Racikan berhasil diupdate!']);
        $this->dispatch('close-retur-titipan-modal');
    }

    public function deleteConfirmationReturTitipan($idretur_titipan)
    {
        $this->idretur_titipanToDelete = $idretur_titipan;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusReturTitipan')]
    public function deleteReturTitipan()
    {
        $returtitipan = ReturTitipan::with('produk.produkDetails.bahan')->find($this->idretur_titipanToDelete);

        if (!$returtitipan) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Data retur tidak ditemukan!',
                'icon' => 'error'
            ]);
            return;
        }

        $produk = $returtitipan->produk;

        // Jika Produk Titipan (tanpa detail racikan)
        if ($produk && $produk->produkDetails->isEmpty()) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Produk titipan tidak memiliki stok bahan yang tercatat.',
                'icon' => 'error'
            ]);
            return;
        }

        // Jika Produk Racikan (punya detail racikan)
        if ($produk) {
            foreach ($produk->produkDetails as $detail) {
                if ($detail->bahan) {
                    $bahan = $detail->bahan;
                    $bahan->stok += ($detail->takaran * $returtitipan->qty);
                    $bahan->save();
                }
            }
        }

        $returtitipan->delete();
        $this->reset('idretur_titipanToDelete');

        // ✅ Berhasil hapus dan kembalikan stok → SweetAlert sukses
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Retur berhasil dihapus dan stok dikembalikan.',
            'icon' => 'success'
        ]);

        $this->dispatch('close-retur-titipan-modal');
    }



    #[On('buka-modal-lov-produk')]
    public function bukaModal()
    {
        $this->isOpen = true;
    }

    #[On('produkDipilih')]
    public function produkLov($id)
    {
        $produk = Produk::find($id);
        $this->produk_idproduk = $produk->idproduk;
        $this->produkName = $produk->nama;
    }

    public function dataReturTitipan()
    {
        return ReturTitipan::with(['supplier', 'produk'])->search($this->search)
            ->simplePaginate($this->perPage);
    }

    public function simpanReturBaru()
    {
        // dd('test');
        $this->validate([
            'tanggalBaru' => 'required|date',
            'produk_idBaru' => 'required|exists:produks,idproduk',
            'qtyBaru' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        $produk = Produk::with('produkDetails.bahan')->find($this->produk_idBaru);
        // dd($produk);
        if (!$produk) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }
        if ($produk->produkDetails->isEmpty()) {
            session()->flash('error', "Gagal menyimpan retur. Produk titipan tidak memiliki stok bahan yang tercatat.");
            return;
        } else {
            // dd('ini ada racikan');
            foreach ($produk->produkDetails as $detail) {
                $bahan = $detail->bahan;

                if (!$bahan) {
                    session()->flash('error', 'Ada bahan dalam racikan yang belum terdaftar.');
                    return;
                }

                $totalPengurangan = $detail->takaran * $this->qtyBaru;

                if ($bahan->stok < $totalPengurangan) {
                    session()->flash('error', "Stok bahan '{$bahan->nama}' tidak cukup untuk retur.");
                    return;
                }
            }

            // Semua bahan cukup → simpan retur
            ReturTitipan::create([
                'tanggal' => $this->tanggalBaru,
                'produk_idproduk' => $this->produk_idBaru,
                'supplier_idsupplier' => $produk->supplier_idsupplier,
                'qty' => $this->qtyBaru,
                'keterangan' => $this->keteranganBaru,
                'user_iduser' => $user->id,
            ]);

            // Kurangi stok semua bahan racikan
            foreach ($produk->produkDetails as $detail) {
                $bahan = $detail->bahan;
                $bahan->stok -= $detail->takaran * $this->qtyBaru;
                $bahan->save();
            }
        }

        // Reset input form
        $this->reset(['tanggalBaru', 'produk_idBaru', 'produkNameBaru', 'qtyBaru', 'keteranganBaru']);

        session()->flash('success', 'Retur berhasil disimpan!');
    }

    public function exportToPdf()
    {
        $headers = ['Tanggal', 'Produk', 'Supplier', 'Jumlah', 'Keterangan'];
        $title = 'Export Data ReturTitipan';
        $queryResult = $this->dataReturTitipan();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->tanggal, $result->produk->nama ?? '-', $result->supplier->nama ?? '-', $result->qty, $result->keterangan];
        }
        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));

        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'ReturTitipan.pdf');
    }

    public function render()
    {
        return view('livewire.retur-titipan-component', [
            'returtitipans' => $this->dataReturTitipan()
        ]);
    }
}
