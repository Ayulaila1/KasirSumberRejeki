<?php
namespace App\Livewire;
use Carbon\Carbon;
use App\Models\Produk;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Pembelian;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\KategoriProduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ProdukComponent extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $idproduk, $nama, $stok = 0, $image, $kategoriproduk_idkategoriproduk, $model, $bawahan, $uk_blangkon, $supplier_idsupplier, $satuan = 'pcs', $harga_jual, $harga_beli, $tanggal_kedaluwarsa, $stok_minimum;
    public $produkName, $kategoriprodukName, $supplierName;
    public $searchlov = '';
    public $search = '';
    public $tglstart = '';
    public $tglend = '';
    public $perPage = 10;
    public $selectedSupplier;

    public function updatingSelectedSupplier()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function updatingTglstart()
    {
        $this->resetPage();
    }


    public function updatingTglend()
    {
        $this->resetPage();
    }


    public function updatingSearchlov()
    {
        $this->resetPage();
        $this->resetPage('pageLOV');
    }


    public function updatingPerpage()
    {
        $this->resetPage();
        $this->resetPage('pageLOV');
    }


    public function storeProduk()
    {
        $this->validate([
            //  'idproduk' => 'required ',
            'nama' => 'required ',
            //      'kategoriproduk_idkategoriproduk' => 'required ',
            'model' => 'required ',
            //      'bawahan' => 'required ',
            //      'uk_blangkon' => 'required ',
            'supplier_idsupplier' => 'required ',
            //      'satuan' => 'required ',
            'harga_jual' => 'required ',
            'harga_beli' => 'required ',
            //      'stok_minimum' => 'required ',
        ]);

        $produk = new Produk();
        $produk->idproduk = $this->idproduk;
        $produk->nama = $this->nama;
        // $produk->image = $this->image;
        if ($this->image) {
            $namaFile = 'produk' . now()->format('YmdHis') . '.' . $this->image->extension();
            $this->image->storeAs('image-website', $namaFile);
            $produk->image = $namaFile;
        }
        $produk->stok = 0;
        $produk->kategoriproduk_idkategoriproduk = $this->kategoriproduk_idkategoriproduk;
        $produk->model = $this->model;
        $produk->bawahan = $this->bawahan;
        $produk->uk_blangkon = $this->uk_blangkon;
        $produk->supplier_idsupplier = $this->supplier_idsupplier;
        $produk->satuan = $this->satuan;
        $produk->harga_jual = $this->harga_jual;
        $produk->harga_beli = $this->harga_beli;
        $produk->tanggal_kedaluwarsa = $this->tanggal_kedaluwarsa;
        $produk->stok_minimum = $this->stok_minimum;
        $produk->save();
        $this->close();

        $this->resetPage('pageLOV');

        // // $this->alert('success', 'New produk has been added successfully');
        // LivewireAlert::success('New produk has been added successfully');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'text' => 'Produk berhasil ditambahkan'
        ]);

        $this->dispatch('close-produk-modal');
    }


    public function close()
    {
        $this->reset();
        $this->resetValidation();
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->dispatch('close-produk-modal');
    }


    public function editProduk($idproduk)
    {
        $produk = Produk::where('idproduk', $idproduk)->first();

        $this->idproduk = $produk->idproduk;
        $this->nama = $produk->nama;
        $this->kategoriproduk_idkategoriproduk = $produk->kategoriproduk_idkategoriproduk;
        $this->kategoriprodukName = $produk->kategoriproduk->nama;
        $this->stok = $produk->stok;
        $this->model = $produk->model;
        $this->bawahan = $produk->bawahan;
        $this->uk_blangkon = $produk->uk_blangkon;
        $this->image = $produk->image;
        $this->supplier_idsupplier = $produk->supplier_idsupplier;
        $this->supplierName = $produk->supplier->nama ?? '-';
        $this->satuan = $produk->satuan;
        $this->harga_jual = $produk->harga_jual;
        $this->harga_beli = $produk->harga_beli;
        $this->tanggal_kedaluwarsa = $produk->tanggal_kedaluwarsa;
        $this->stok_minimum = $produk->stok_minimum;
        // untuk edit jika menggunakan LOV  isi  tabelName LOV
// $this->produkName = $produk ->nama_relasi_yang_di_jadikan_LOV; 

        $this->dispatch('show-edit-produk-modal');
    }


    public function updateProduk()
    {
        $this->validate([
            'idproduk' => 'required ',
            //      'nama' => 'required ',
//      'kategoriproduk_idkategoriproduk' => 'required ',
//      'model' => 'required ',
//      'bawahan' => 'required ',
//      'uk_blangkon' => 'required ',
//      'supplier_idsupplier' => 'required ',
//      'satuan' => 'required ',
//      'harga_jual' => 'required ',
//      'harga_beli' => 'required ',
//      'tanggal_kedaluwarsa' => 'required ',
//      'stok_minimum' => 'required ',
        ]);


        $produk = Produk::where('idproduk', $this->idproduk)->first();
        $produk->idproduk = $this->idproduk;
        $produk->nama = $this->nama;
        if ($this->image != $produk->image) {

            if (!is_null($produk->image)) {
                Storage::delete('image-website/' . $produk->image);
            }

            $namaFile = 'produk' . now()->format('YmdHis') . '.' . $this->image->extension();
            $this->image->storeAs('image-website', $namaFile);
            $produk->image = $namaFile;

            $this->reset('image');
        }

        $produk->stok = $this->stok;
        $produk->kategoriproduk_idkategoriproduk = $this->kategoriproduk_idkategoriproduk;
        $produk->model = $this->model;
        $produk->bawahan = $this->bawahan;
        $produk->uk_blangkon = $this->uk_blangkon;
        $produk->supplier_idsupplier = $this->supplier_idsupplier;
        $produk->satuan = $this->satuan;
        $produk->harga_jual = $this->harga_jual;
        $produk->harga_beli = $this->harga_beli;
        $produk->tanggal_kedaluwarsa = $this->tanggal_kedaluwarsa;
        $produk->stok_minimum = $this->stok_minimum;
        $produk->save();

        $this->close();

        $this->resetPage('pageLOV');

        $this->alert('success', 'Update data produk has been added successfully');

        $this->dispatch('close-produk-modal');
    }

    public function deleteConfirmationProduk($idproduk)
    {
        $this->idproduk = $idproduk;
        $this->dispatch('show-delete-confirmation-produk-modal');
    }


    public function deleteProduk()
    {
        $produk = Produk::where('idproduk', $this->idproduk)->first();
        try {
            $produk->delete();
            if (!is_null($produk->image)) {
                Storage::delete('image-website/' . $produk->image);
            }
            $this->alert('success', 'Data produk Berhasil Di hapus');
        } catch (\Throwable) {
            $this->alert('error', 'Ada data yang terkait, Data produk Gagal Di hapus');
        }
        $this->dispatch('close-produk-modal');
        $this->idproduk = '';
    }


    public function exportToExcel()
    {
        $queryResult = $this->dataProduk();
        $data = [['Nama', 'Stok Sekarang', 'Kategori Produk', 'Model', 'Bawahan', 'Ukuran Blangkon', 'Supplier', 'Satuan', 'Stok Minimum', 'Harga Jual', 'Harga Beli']];
        foreach ($queryResult as $result) {
            $data[] = [$result->nama, $result->stok, $result->kategoriproduk->nama, $result->model, $result->bawahan, $result->uk_blangkon, $result->supplier->nama, $result->satuan, $result->stok_minimum, $result->harga_jual, $result->harga_beli];
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, null, 'A1');
        $writer = new Xlsx($spreadsheet);
        $filename = 'exported-produk-data.xlsx';
        $writer->save($filename);
        return response()->download($filename)->deleteFileAfterSend(true);
    }


    public function exportToPdf()
    {
        $headers = ['Nama', 'Stok Sekarang', 'Kategori Produk', 'Model', 'Bawahan', 'Ukuran Blangkon', 'Supplier', 'Satuan', 'Stok Minimum', 'Harga Jual', 'Harga Beli'];
        $title = 'Export Data Produk';
        $queryResult = $this->dataProduk();
        $data = [];
        foreach ($queryResult as $result) {
            $data[] = [$result->nama, $result->stok, $result->kategoriproduk->nama, $result->model, $result->bawahan, $result->uk_blangkon, $result->supplier->nama, $result->satuan, $result->stok_minimum, $result->harga_jual, $result->harga_beli];
        }
        $pdf = PDF::loadView('layout.pdf_layout', compact('data', 'headers', 'title'));
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Produk.pdf');
    }


    public function produkOption($id = null)
    {
        $result = Produk::get();
        $options = '';
        foreach ($result as $row) {
            $options .= '<option value=' . $row->idproduk . '';
            if ($id == $row->idproduk) {
                $options .= ' selected';
            }
            $options .= '>' . $row->produk . '</option>';
        }
        return $options;
    }


    public function produkLOV($id)
    {
        $produk = Produk::where('idproduk', $id)->first();
        // $this->nama_relasi = $produk ->idproduk;  
        $this->produkName = $produk->produk;
        $this->alert('success', 'Terpilih : ' . $produk->produk);
        $this->resetPage('pageLOV');

        $this->dispatch('close-modal-lov');
    }

    // public function kategoriprodukLOV($id)
    // {
    //     $kategoriproduk = KategoriProduk::where('idkategoriproduk', $id)->first();
    //     $this->kategoriproduk_idkategoriproduk = $kategoriproduk->idkategoriproduk;
    //     $this->kategoriprodukName = $kategoriproduk->nama;
    //     $this->alert('success', 'Terpilih : ' . $kategoriproduk->nama);
    //     $this->resetPage('pageLOV');

    //     $this->dispatch('close-modal-lov');
    // }


    // public function supplierLOV($id)
    // {
    //     $supplier = Supplier::where('idsupplier', $id)->first();
    //     $this->supplier_idsupplier = $supplier->idsupplier;
    //     $this->supplierName = $supplier->nama;
    //     // dd($this->supplierName);
    //     $this->alert('success', 'Terpilih : ' . $supplier->nama);
    //     $this->resetPage('pageLOV');

    //     $this->dispatch('close-modal-lov');
    // }

    //untuk mencetak data yang natinya akan di tampilkan di render (memidah dari render agar flexible)   
    public function dataproduk()
    {

        $produk = Produk::with('kategoriproduk', 'supplier')
            ->filterSupplier($this->selectedSupplier)
            ->search($this->search)
            //->rangetanggal($this->tglstart, $this->tglend) //aktifkan kalau pake filter tanggal 
            ->simplePaginate($this->perPage);

        return $produk;
    }


    public function render()
    {
        // $suppliers = Supplier::search($this->searchlov)->simplePaginate($this->perPage, ['*'], 'pageLOV');
        // Filter Tahun
        // $filterSupplier = Produk::select('supplier_idsupplier')->distinct()->get();
        // $kategoriproduks = KategoriProduk::search($this->searchlov)
        // ->simplePaginate($this->perPage, ['*'], 'pageLOV');

        $produk = $this->dataProduk(); // ← ini sekarang berisi data valid

        return view('livewire.produk-component', [
            'produks' => $produk,
            // 'kategoriproduks' => $kategoriproduks,
            // 'suppliers' => $suppliers,
            // 'filterSupplier' => $filterSupplier
        ]);
    }
}
