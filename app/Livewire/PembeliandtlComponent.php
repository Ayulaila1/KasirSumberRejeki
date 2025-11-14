<?php

namespace App\Livewire;

use Exception;
use Carbon\Carbon;
use App\Models\Bahan;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Pembelian;
use Livewire\Attributes\On;
use App\Models\Pembeliandtl;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use Jantinnerezo\LivewireAlert\Traits\LivewireAlert;


class PembeliandtlComponent extends Component
{
    use WithPagination, WithFileUploads;
    // use LivewireAlert;

    public $idpembeliandtl, $pembelian_idpembelian, $bahan_idbahan, $jumlah, $isi_per_satuan, $harga_beli, $subtotal, $created_at, $updated_at;
    public $pembeliandtlName, $bahanName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $selectedSupplier;
    public $isOpen = false;
    public $isEdit = false;
    public $idpembeliandtlToDelete;
    public $idPage;

    public function mount($id)
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->idPage = $id;
        // $this->listSupplier = Supplier::all();
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

    public function tambahPembeliandtl()
    {
        $this->reset([
            'idpembeliandtl',
            'pembelian_idpembelian',
            'bahan_idbahan',
            'jumlah',
            'isi_per_satuan',
            'harga_beli',
            'subtotal'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;

    }

    public function close()
    {
        $this->reset([
            'idpembeliandtl',
            'pembelian_idpembelian',
            'bahan_idbahan',
            'jumlah',
            'isi_per_satuan',
            'harga_beli',
            'subtotal',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-pembeliandtl-modal');
    }

    public function storePembeliandtl()
    {
        $this->validate([
            // 'pembelian_idpembelian' => 'required',
            'bahan_idbahan' => 'required',
            // 'jumlah' => 'required',
        ]);

        $pembeliandtl = new Pembeliandtl();
        $pembeliandtl->idpembeliandtl = $this->idpembeliandtl;
        $pembeliandtl->pembelian_idpembelian = $this->idPage;
        $pembeliandtl->bahan_idbahan = $this->bahan_idbahan;
        $pembeliandtl->jumlah = $this->jumlah;
        $pembeliandtl->isi_per_satuan = $this->isi_per_satuan;
        $pembeliandtl->harga_beli = $this->harga_beli;
        $pembeliandtl->subtotal = $this->subtotal;
        $pembeliandtl->created_at = $this->created_at;
        $pembeliandtl->updated_at = $this->updated_at;
        $pembeliandtl->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('pembeliandtl-disimpan', ['pesan' => 'Pembeliandtl berhasil disimpan!']);
        $this->dispatch('close-pembeliandtl-modal');
    }

    public function editPembeliandtl($idpembeliandtl)
    {
        $pembeliandtl = Pembeliandtl::where('idpembeliandtl', $idpembeliandtl)->first();

        $this->idpembeliandtl = $pembeliandtl->idpembeliandtl;
        $this->pembelian_idpembelian = $pembeliandtl->pembelian_idpembelian;
        $this->bahan_idbahan = $pembeliandtl->bahan_idbahan;
        $this->bahanName = $pembeliandtl->bahan ? $pembeliandtl->bahan->nama : '';
        $this->jumlah = $pembeliandtl->jumlah;
        $this->isi_per_satuan = $pembeliandtl->isi_per_satuan;
        $this->harga_beli = $pembeliandtl->harga_beli;
        $this->subtotal = $pembeliandtl->subtotal;
        $this->created_at = $pembeliandtl->created_at;
        $this->updated_at = $pembeliandtl->updated_at;

        $this->isEdit = true;

        $this->dispatch('show-edit-pembeliandtl-modal');
    }

    public function updatePembeliandtl()
    {
        $this->validate([
            // 'pembelian_idpembelian' => 'required',
            'bahan_idbahan' => 'required',
            // 'pembeliandtl_idpembeliandtl' => 'required',
            // 'jumlah' => 'required',
            // 'harga_beli' => 'required',
            //'subtotal' => 'required',
            // 'created_at' => 'required|date',
            // 'updated_at' => 'required|date',
        ]);

        $pembeliandtl = Pembeliandtl::where('idpembeliandtl', $this->idpembeliandtl)->firstOrFail();
        $pembeliandtl->pembelian_idpembelian = $this->pembelian_idpembelian;
        $pembeliandtl->bahan_idbahan = $this->bahan_idbahan;
        $pembeliandtl->jumlah = $this->jumlah;
        $pembeliandtl->isi_per_satuan = $this->isi_per_satuan;
        $pembeliandtl->harga_beli = $this->harga_beli;
        $pembeliandtl->subtotal = $this->subtotal;
        $pembeliandtl->created_at = $this->created_at;
        $pembeliandtl->updated_at = $this->updated_at;
        $pembeliandtl->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('pembeliandtl-disimpan', ['pesan' => 'Pembeliandtl berhasil diupdate!']);
        $this->dispatch('close-pembeliandtl-modal');
    }

    public function deleteConfirmationPembeliandtl($idpembeliandtl)
    {
        $this->idpembeliandtlToDelete = $idpembeliandtl;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusPembeliandtl')]
    public function deletePembeliandtl()
    {
        $pembeliandtl = Pembeliandtl::where('idpembeliandtl', $this->idpembeliandtlToDelete)->first();

        if (!$pembeliandtl) {
            $this->dispatch('pembeliandtl-error', ['pesan' => 'Pembeliandtl tidak ditemukan!']);
            return;
        }

        $pembeliandtl->delete();
        $this->reset('idpembeliandtlToDelete');

        $this->dispatch('pembeliandtl-disimpan', ['pesan' => 'Pembeliandtl berhasil dihapus!']);
        $this->dispatch('close-pembeliandtl-modal');
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

    public function simpanPembeliandtl()
    {
        // Update status pembelian
        $pembelian = Pembelian::where('idpembelian', $this->idPage)->first();
        $pembelian->status = 'saved';
        $pembelian->save();

        // Ambil semua detail pembelian berdasarkan id
        $details = Pembeliandtl::where('pembelian_idpembelian', $this->idPage)->get();

        foreach ($details as $detail) {

            // 🔹 Hitung subtotal otomatis kalau belum ada
            if (is_null($detail->subtotal) || $detail->subtotal == 0) {
                $detail->subtotal = $detail->jumlah * $detail->harga_beli;
                $detail->save();
            }

            // Tambahkan stok bahan (berdasarkan jumlah * isi_per_satuan)
            if ($detail->bahan_idbahan) {
                $bahan = Bahan::find($detail->bahan_idbahan);
                if ($bahan) {
                    $stokMasuk = $detail->jumlah * $detail->isi_per_satuan;
                    $bahan->stok += $stokMasuk;
                    $bahan->save();
                }
            }
        }

        $this->dispatch('stok-disimpan', [
            'title' => 'Berhasil!',
            'text' => 'Stok produk & bahan berhasil diperbarui.',
            'icon' => 'success',
        ]);

    }


    public function unsavedPembeliandtl()
    {
        // Update status pembelian
        $pembelian = Pembelian::where('idpembelian', $this->idPage)->first();
        $pembelian->status = 'unsaved';
        $pembelian->save();

        // Ambil semua detail pembelian berdasarkan id
        $details = Pembeliandtl::where('pembelian_idpembelian', $this->idPage)->get();

        foreach ($details as $detail) {

            // Tambahkan stok bahan (berdasarkan jumlah * isi_per_satuan)
            if ($detail->bahan_idbahan) {
                $bahan = Bahan::find($detail->bahan_idbahan);
                if ($bahan) {
                    $stokMasuk = $detail->jumlah * $detail->isi_per_satuan;
                    $bahan->stok -= $stokMasuk;
                    $bahan->save();
                }
            }
        }

        $this->dispatch('stok-dibatalkan', [
            'title' => 'Berhasil!',
            'text' => 'Stok produk & bahan berhasil diperbarui.',
            'icon' => 'success',
        ]);
    }

    public function exportToPdf()
    {
        $headers = ['Bahan', 'Jumlah', 'Isi per Satuan', 'Harga Beli', 'Subtotal'];
        $title = 'Export Data Pembeliandtl';
        $queryResult = $this->dataPembeliandtl();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->bahan->nama ?? '-', $result->jumlah, $result->isi_per_satuan, $result->harga_beli, $result->subtotal];
        }
        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));

        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Pembeliandtl.pdf');
    }

    public function dataPembeliandtl()
    {
        return Pembeliandtl::search($this->search)
            ->where('pembelian_idpembelian', $this->idPage)
            ->with(['bahan', 'pembelian']) // Eager load bahan and pembelian relationships
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        $pembelian = Pembelian::where('idpembelian', $this->idPage)->first();

        return view('livewire.pembeliandtl-component', [
            'pembeliandtls' => $this->dataPembeliandtl(),
            'pembelian' => $pembelian,
        ]);
    }
}