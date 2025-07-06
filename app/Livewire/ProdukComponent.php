<?php

namespace App\Livewire;

use Exception;
use Carbon\Carbon;
use App\Models\Produk;
use Livewire\Component;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use Jantinnerezo\LivewireAlert\Traits\LivewireAlert;


class ProdukComponent extends Component
{
    use WithPagination, WithFileUploads;
    // use LivewireAlert;

    public $idproduk, $nama, $image, $jenisproduk, $kategori, $supplier_idsupplier, $harga_jual, $harga_beli, $tanggal_kedaluwarsa, $stok_minimum, $is_titipan = false, $created_at, $updated_at;
    public $supplierName;
    public $search = '';
    public $searchlov = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $selectedSupplier;
    public $isOpen = false;
    public $isEdit = false;
    public $idprodukToDelete;
    public $selectedJenisProduk;
    public $selectedKategori;

    public function updatingSelectedJenisProduk()
    {
        $this->resetPage();
    }

    public function updatingSelectedKategori()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
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

    public function tambahProduk()
    {
        $this->reset([
            'idproduk',
            'nama',
            'image',
            'jenisproduk',
            'kategori',
            'supplier_idsupplier',
            'harga_jual',
            'harga_beli',
            'tanggal_kedaluwarsa',
            'stok_minimum',
            'is_titipan'
        ]);
        $this->isOpen = true;
        $this->isEdit = false;

    }

    public function close()
    {
        $this->reset([
            'idproduk',
            'nama',
            'image',
            'jenisproduk',
            'kategori',
            'supplier_idsupplier',
            'harga_jual',
            'harga_beli',
            'tanggal_kedaluwarsa',
            'stok_minimum',
            'is_titipan',
            'isOpen',
            'isEdit'
        ]);
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-produk-modal');
    }

    public function storeProduk()
    {
        $this->validate([
            'nama' => 'required',
            // 'harga_jual' => 'required',
            // 'harga_beli' => 'required',
        ]);

        $produk = new Produk();
        $produk->idproduk = $this->idproduk;
        $produk->nama = $this->nama;

        if ($this->image) {
            $namaFile = 'produk' . now()->format('YmdHis') . '.' . $this->image->extension();
            $this->image->storeAs('image-website', $namaFile);
            $produk->image = $namaFile;
        }

        $produk->jenisproduk = $this->jenisproduk;
        $produk->kategori = $this->kategori;
        $produk->supplier_idsupplier = $this->supplier_idsupplier;
        $produk->harga_jual = $this->harga_jual;
        $produk->harga_beli = $this->harga_beli;
        $produk->tanggal_kedaluwarsa = $this->tanggal_kedaluwarsa;
        $produk->stok_minimum = $this->stok_minimum;
        // $produk->is_titipan = $this->is_titipan;
        $produk->is_titipan = strtolower($this->jenisproduk) === 'titipan';
        $produk->save();

        return redirect('/produkracikan/' . $produk->idproduk);

        $this->close();
        $this->resetPage('pageLOV');
        $this->dispatch('produk-disimpan', ['pesan' => 'Produk berhasil disimpan!']);
        $this->dispatch('close-produk-modal');
    }

    public function editProduk($idproduk)
    {
        $produk = Produk::where('idproduk', $idproduk)->first();

        $this->idproduk = $produk->idproduk;
        $this->nama = $produk->nama;
        $this->image = $produk->image;
        $this->jenisproduk = $produk->jenisproduk;
        $this->kategori = $produk->kategori;
        $this->supplier_idsupplier = $produk->supplier_idsupplier;
        $this->supplierName = $produk->supplier->nama ?? '-';
        $this->harga_jual = $produk->harga_jual;
        $this->harga_beli = $produk->harga_beli;
        $this->tanggal_kedaluwarsa = $produk->tanggal_kedaluwarsa;
        $this->stok_minimum = $produk->stok_minimum;
        // $this->is_titipan = $produk->is_titipan;
        $this->is_titipan = strtolower($produk->jenisproduk) === 'titipan'; // ✅ hasilnya true


        $this->isEdit = true;

        $this->dispatch('show-edit-produk-modal');
    }

    public function updateProduk()
    {
        $this->validate([
            'nama' => 'required',
            // Jika kamu butuh validasi lainnya tinggal uncomment atau tambah
            // 'jenisproduk' => 'required',
            // 'kategori' => 'required',
            // 'supplier_idsupplier' => 'required',
            // 'harga_jual' => 'required|numeric',
            // 'harga_beli' => 'required|numeric',
            // 'tanggal_kedaluwarsa' => 'nullable|date',
            // 'stok_minimum' => 'required|numeric',
            // 'is_titipan' => 'required|boolean',
        ]);

        $produk = Produk::where('idproduk', $this->idproduk)->firstOrFail();
        $produk->nama = $this->nama;

        if ($this->image && $this->image != $produk->image) {
            if ($produk->image && Storage::exists('image-website/' . $produk->image)) {
                Storage::delete('image-website/' . $produk->image);
            }
            $namaFile = 'produk' . now()->format('YmdHis') . '.' . $this->image->extension();
            $this->image->storeAs('image-website', $namaFile);
            $produk->image = $namaFile;
        }

        $produk->jenisproduk = $this->jenisproduk;
        $produk->kategori = $this->kategori;
        $produk->supplier_idsupplier = $this->supplier_idsupplier;
        $produk->harga_jual = $this->harga_jual;
        $produk->harga_beli = $this->harga_beli;
        $produk->tanggal_kedaluwarsa = $this->tanggal_kedaluwarsa;
        $produk->stok_minimum = $this->stok_minimum;
        $produk->is_titipan = $this->is_titipan;

        $produk->save();

        $this->close();
        $this->resetPage('pageLOV');
        $this->reset(['image']);
        $this->dispatch('produk-disimpan', ['pesan' => 'Produk berhasil diupdate!']);
        $this->dispatch('close-produk-modal');
    }



    public function deleteConfirmationProduk($idproduk)
    {
        $this->idprodukToDelete = $idproduk;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusProduk')]
    public function deleteProduk()
    {
        $produk = Produk::where('idproduk', $this->idprodukToDelete)->first();

        if (!$produk) {
            $this->dispatch('produk-error', ['pesan' => 'Produk tidak ditemukan!']);
            return;
        }

        // Hapus gambar kalau ada
        if ($produk->image && Storage::exists('image-website/' . $produk->image)) {
            Storage::delete('image-website/' . $produk->image);
        }

        $produk->delete();
        $this->reset('idprodukToDelete');

        $this->dispatch('produk-disimpan', ['pesan' => 'Produk berhasil dihapus!']);
        $this->dispatch('close-produk-modal');
    }

    public function exportToPdf()
    {
        $headers = ['Nama', 'Jenis Produk', 'Kategori', 'Supplier', 'Harga Jual', 'Harga Beli', 'Tanggal Kedaluwarsa'];
        $title = 'Export Data Produk';
        $queryResult = $this->dataProduk();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->nama, $result->jenisproduk, $result->kategori, $result->supplier->nama ?? '-', number_format($result->harga_jual, 0, ',', '.'), number_format($result->harga_beli, 0, ',', '.'), $result->tanggal_kedaluwarsa];
        }
        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'));

        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Produk.pdf');
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

    public function dataProduk()
    {
        return Produk::search($this->search)
            ->filterJenisProduk($this->selectedJenisProduk)
            ->filterKategori($this->selectedKategori)
            ->with(['supplier']) // Eager load supplier relationship
            ->simplePaginate($this->perPage);
    }

    public function render()
    {
        $filterJenisProduk = Produk::select('jenisproduk')->distinct()->get();
        $filterKategori = Produk::select('kategori')->distinct()->get();

        return view('livewire.produk-component', [
            'produks' => $this->dataProduk(),
            'filterJenisProduk' => $filterJenisProduk,
            'filterKategori' => $filterKategori
        ]);
    }
}
