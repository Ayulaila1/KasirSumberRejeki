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
use Illuminate\Support\Facades\DB;

class ReturTitipanComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $idretur_titipan, $tanggal, $supplier_idsupplier, $produk_idproduk, $qty, $harga_beli, $subtotal, $keterangan;
    public $produkName;
    public $tanggalBaru, $produk_idBaru, $produkNameBaru, $qtyBaru, $harga_beliBaru, $subtotalBaru, $keteranganBaru;
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

    public function tambahReturTitipan()
    {
        $this->reset([
            'idretur_titipan',
            'tanggal',
            'supplier_idsupplier',
            'produk_idproduk',
            'qty',
            'harga_beli',
            'subtotal',
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
            'harga_beli',
            'subtotal',
            'keterangan',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-retur-titipan-modal');
    }

    /** 🔹 Simpan retur baru */
    public function simpanReturBaru()
    {
        $this->validate([
            'tanggalBaru' => 'required|date',
            'produk_idBaru' => 'required|exists:produks,idproduk',
            'qtyBaru' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $produk = Produk::with('produkDetails.bahan')->find($this->produk_idBaru);

        if (!$produk) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }

        // cek bahan cukup
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

        /** 🧾 ambil harga beli produk */
        $hargaBeli = $produk->harga_beli ?? 0;
        $subtotal = $hargaBeli * $this->qtyBaru;

        /** 💾 simpan retur */
        ReturTitipan::create([
            'tanggal' => $this->tanggalBaru,
            'produk_idproduk' => $this->produk_idBaru,
            'supplier_idsupplier' => $produk->supplier_idsupplier,
            'qty' => $this->qtyBaru,
            'harga_beli' => $hargaBeli,
            'subtotal' => $subtotal,
            'keterangan' => $this->keteranganBaru,
            'user_iduser' => $user->id,
        ]);

        /** 📉 kurangi stok bahan */
        foreach ($produk->produkDetails as $detail) {
            $bahan = $detail->bahan;
            $bahan->stok -= $detail->takaran * $this->qtyBaru;
            $bahan->save();
        }

        $this->reset(['tanggalBaru', 'produk_idBaru', 'produkNameBaru', 'qtyBaru', 'harga_beliBaru', 'subtotalBaru', 'keteranganBaru']);
        session()->flash('success', 'Retur berhasil disimpan!');
    }

    /** 🔹 Update retur (edit mode) */
    public function updateReturTitipan()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'qty' => 'required|numeric|min:1',
        ]);

        $retur = ReturTitipan::findOrFail($this->idretur_titipan);
        $retur->tanggal = $this->tanggal;
        $retur->qty = $this->qty;
        $retur->keterangan = $this->keterangan;

        // hitung ulang subtotal kalau harga beli ada
        $retur->subtotal = $retur->harga_beli ? $retur->harga_beli * $this->qty : $retur->subtotal;
        $retur->save();

        $this->close();
        session()->flash('success', 'Retur berhasil diupdate!');
    }

    /** 🔹 Hapus retur + kembalikan stok bahan */
    public function deleteReturTitipan($id)
    {
        $retur = ReturTitipan::with('produk.produkDetails.bahan')->find($id);
        if (!$retur) {
            $this->dispatch('retur-titipan-error', [
                'pesan' => 'Data retur tidak ditemukan!',
            ]);
            return;
        }

        // ✅ Kembalikan stok bahan
        if ($retur->produk) {
            foreach ($retur->produk->produkDetails as $detail) {
                if ($detail->bahan) {
                    $detail->bahan->increment('stok', $detail->takaran * $retur->qty);
                }
            }
        }

        $retur->delete();

        $this->dispatch('retur-titipan-disimpan', [
            'pesan' => 'Retur berhasil dihapus dan stok bahan dikembalikan.',
        ]);
    }

    /** 🔹 Query utama data retur */
    public function dataReturTitipan()
    {
        return ReturTitipan::with(['supplier', 'produk'])
            ->when($this->tglstart, fn($q) => $q->whereDate('tanggal', '>=', $this->tglstart))
            ->when($this->tglend, fn($q) => $q->whereDate('tanggal', '<=', $this->tglend))
            ->search($this->search)
            ->simplePaginate($this->perPage);
    }

    /** 🔹 Export PDF */
    public function exportToPdf()
    {
        $headers = ['Tanggal', 'Produk', 'Supplier', 'Jumlah', 'Harga Beli', 'Subtotal', 'Keterangan'];
        $title = 'Export Data Retur Titipan';
        $queryResult = $this->dataReturTitipan();
        $data = [];

        foreach ($queryResult as $result) {
            $data[] = [
                $result->tanggal,
                $result->produk->nama ?? '-',
                $result->supplier->nama ?? '-',
                $result->qty,
                number_format($result->harga_beli ?? 0, 0, ',', '.'),
                number_format($result->subtotal ?? 0, 0, ',', '.'),
                $result->keterangan
            ];
        }

        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'))
            ->setPaper('A4', 'portrait');

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
