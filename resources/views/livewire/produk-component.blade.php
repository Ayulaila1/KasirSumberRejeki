<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Produk</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Produk, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-produk-modal')"><i class="fa-solid fa-plus "></i></button> --}}

                </div>
            </div>
        </div>


        {{-- Loader --}}
        <div wire:loading wire:target="exportToExcel, exportToPdf, search, tglstart, tglend" class="loading-spinner">
            <div class="loader"></div>
        </div>
        {{-- Loader --}}


        <div class="py-2 mb-2">
            <div class="d-lg-flex justify-content-between align-items-center">
                <div>
                    <input type="text" class="form-control mb-1 mb-lg-0 " placeholder="Search"
                        wire:model.live.debounce.300ms="search">
                </div>
                <div class="d-lg-flex align-items-center gap-2">
                    {{-- <input type="date" class="form-control" wire:model.live.debounce.300ms="tglstart">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglend"> --}}
                    <div>
                        <select class="form-control" wire:model.live.debounce.300ms="selectedJenisProduk">
                            <option value="" selected>Jenis Produk</option>
                            @foreach ($filterJenisProduk as $item)
                            <option value="{{ $item->jenisproduk }}">{{ $item->jenisproduk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select class="form-control" wire:model.live.debounce.300ms="selectedKategori">
                            <option value="" selected>Kategori</option>
                            @foreach ($filterKategori as $item)
                            <option value="{{ $item->kategori }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf"> <i
                                class="fa-regular fa-file-pdf"></i>
                            PDF</button>
                        <button wire:click="tambahProduk" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                        </button>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-2 pb-0 border-0">
                    <div class="card-body p-0">



                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%;white-space:nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%">No</th>
                                        <th style="width: 100px;text-align: center">Action</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Gambar</th>
                                        <th class="text-center">Jenis Produk</th>
                                        <th class="text-center">Kategori</th>
                                        {{-- <th>Stok</th> --}}
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Harga Jual</th>
                                        <th class="text-center">Harga Beli</th>
                                        <th class="text-center">Tgl Kedaluwarsa</th>
                                        {{-- <th>Stok Minimum</th> --}}
                                        <th class="text-center">Barang Titipan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produks as $key => $datas)
                                    <tr
                                        class="{{ $datas->stok <= $datas->stok_minimum ? 'bg-danger text-white' : '' }}">
                                        <td>{{ $produks->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editProduk('{{ $datas->idproduk }}')"><i
                                                        class='bx bx-pen-alt'></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationProduk('{{ $datas->idproduk }}')">
                                                    <i class='bx bx-trash'></i></button>
                                                <a class="btn btn-sm text-white bg-success"
                                                    href="/produkracikan/{{ $datas->idproduk }}">
                                                    <i class="fa-solid fa-list"></i> </a>
                                            </div>
                                        </td>
                                        <td>{{ $datas->nama }}</td>
                                        <td><img src="{{ asset('storage/image-website/' . $datas->image) }}"
                                                style="max-width: 70px;"></td>
                                        <td>{{ $datas->jenisproduk }}</td>
                                        <td>{{ $datas->kategori }}</td>
                                        {{-- <td>{{ $datas->stok }}</td> --}}
                                        <td>{{ $datas->supplier->nama ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($datas->harga_jual, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">Rp {{ number_format($datas->harga_beli, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $datas->tanggal_kedaluwarsa }}</td>
                                        {{-- <td>{{ $datas->stok_minimum }}</td> --}}
                                        <td class="text-center">
                                            @if($datas->is_titipan)
                                            <a href="{{ route('retur.produk', ['idproduk' => $datas->idproduk]) }}"
                                                class="btn btn-warning text-dark fw-semibold d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill shadow-sm"
                                                style="background-color: #ffc107; border: none; font-size: 0.85rem;">
                                                <i class="fa-solid fa-rotate-left small"></i>
                                                <span style="line-height: 1;">Retur Titipan</span>
                                            </a>
                                            @else
                                            <span class="badge bg-success px-2 py-1 rounded-pill shadow-sm"
                                                style="font-size: 0.8rem;">
                                                Bukan Titipan
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class=" col-lg-6 d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $produks->firstItem() }} to {{ $produks->lastItem() }}
                        </div>
                        <div>
                            Per Page
                            <select name="form-control" wire:model.live='perPage'>
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="10000">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2 col-lg-6 d-flex justify-content-end align-items-center">
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idproduk">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Produk<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="image" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Gambar</label>
                        <div class="col-12 col-lg-9">
                            <input type="file" id="image" class="form-control" wire:model="image"
                                placeholder="Masukkan image">
                            @error('image')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="jenisproduk" class="col-12 col-lg-3 fw-bold text-lg-end">Jenis Produk<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <select id="jenisproduk" class="form-control" wire:model="jenisproduk">
                                <option value="">Pilih Jenis Produk</option>
                                <option value="Racikan">Racikan</option>
                                <option value="Siap Saji">Siap Saji</option>
                                <option value="Titipan">Titipan</option>
                            </select>
                            @error('jenisproduk') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="kategori"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Kategori</label>
                        <div class="col-12 col-lg-9">
                            <select id="kategori" class="form-control" wire:model="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Snack">Snack</option>
                            </select>
                            @error('kategori')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="form-group row mb-3">
                        <label for="supplier_idsupplier"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex justify-content-between align-items-center gap-1">
                                <input class="form-control" id="supplierName" wire:model="supplierName">
                                <button type="button" class="btn btn-primary"
                                    wire:click="$dispatch('buka-modal-lov-supplier')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            @error('supplier_idsupplier')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="harga_jual" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Harga
                            Jual<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="harga_jual" class="form-control" wire:model="harga_jual"
                                placeholder="Masukkan harga_jual">
                            @error('harga_jual')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="harga_beli" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Harga
                            Beli<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="harga_beli" class="form-control" wire:model="harga_beli"
                                placeholder="Masukkan harga_beli">
                            @error('harga_beli')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="tanggal_kedaluwarsa"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Tanggal Kedaluwarsa<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="date" id="tanggal_kedaluwarsa" class="form-control"
                                wire:model="tanggal_kedaluwarsa" placeholder="Masukkan tanggal_kedaluwarsa">
                            @error('tanggal_kedaluwarsa')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <input type="hidden" wire:model="is_titipan">

                    {{-- <div class="form-group row mb-3">
                        <label for="is_titipan" class="col-12 col-lg-3 fw-bold text-lg-end">Barang Titipan</label>
                        <div class="col-12 col-lg-9">
                            <select id="is_titipan" class="form-control" wire:model="is_titipan">
                                <option value="Bukan Titipan">Bukan Titipan</option>
                                <option value="Titipan">Titipan</option>
                            </select>
                            @error('is_titipan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div> --}}

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storeProduk">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Produk -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="idproduk">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Produk<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="image" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Gambar</label>
                        <div class="col-12 col-lg-9">
                            <input type="file" id="image" class="form-control" wire:model="image"
                                placeholder="Masukkan image">
                            @error('image')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="jenisproduk" class="col-12 col-lg-3 fw-bold text-lg-end">Jenis Produk<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <select id="jenisproduk" class="form-control" wire:model="jenisproduk">
                                <option value="">Pilih Jenis Produk</option>
                                <option value="Racikan">Racikan</option>
                                <option value="Siap Saji">Siap Saji</option>
                                <option value="Titipan">Titipan</option>
                            </select>
                            @error('jenisproduk') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="kategori"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Kategori</label>
                        <div class="col-12 col-lg-9">
                            <select id="kategori" class="form-control" wire:model="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Snack">Snack</option>
                            </select>
                            @error('kategori')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="supplier_idsupplier"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex justify-content-between align-items-center gap-1">
                                <input class="form-control" id="supplierName" wire:model="supplierName">
                                <button type="button" class="btn btn-primary"
                                    wire:click="$dispatch('buka-modal-lov-supplier')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            @error('supplier_idsupplier')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="harga_jual" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Harga
                            Jual<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="harga_jual" class="form-control" wire:model="harga_jual"
                                placeholder="Masukkan harga_jual">
                            @error('harga_jual')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="harga_beli" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Harga
                            Beli<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="harga_beli" class="form-control" wire:model="harga_beli"
                                placeholder="Masukkan harga_beli">
                            @error('harga_beli')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="tanggal_kedaluwarsa"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Tanggal
                            Kedaluwarsa<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="date" id="tanggal_kedaluwarsa" class="form-control"
                                wire:model="tanggal_kedaluwarsa" placeholder="Masukkan tanggal_kedaluwarsa">
                            @error('tanggal_kedaluwarsa')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <input type="hidden" wire:model="is_titipan">

                    {{-- <div class="form-group row mb-3">
                        <label for="is_titipan" class="col-12 col-lg-3 fw-bold text-lg-end">Barang Titipan</label>
                        <div class="col-12 col-lg-9">
                            <select id="is_titipan" class="form-control" wire:model="is_titipan">
                                <option value="0">Bukan Titipan</option>
                                <option value="1">Titipan</option>
                            </select>
                            @error('is_titipan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div> --}}

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateProduk">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @livewire('produk-lov') --}}
    @livewire('supplier-lov')

</div>


<script>
    window.addEventListener('close-produk-modal', event =>{ 
            $('#addProdukModal').modal('hide'); 
            $('#editProdukModal').modal('hide'); 
            $('#deleteProdukModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#produkModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnprodukModalLovAdd').click(function(){ 
   //       $('#produkModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnprodukModalLovEdit').click(function(){ 
   //       $('#produkModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addProdukModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-produk-modal', event =>{ 
            $('#addProdukModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-produk-modal', event =>{ 
            $('#editProdukModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-produk-modal', event =>{ 
            $('#deleteProdukModal').modal('show'); 
        }); 
    
 
</script>

<script>
    window.addEventListener('produk-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('produk-error', event => {
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: event.detail.pesan,
        });
        });

        window.addEventListener('konfirmasi-hapus', event => {
        Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.isConfirmed) {
        Livewire.dispatch('hapusProduk'); // untuk Livewire v3
        }
        });
        });
     
</script>