<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Produk Racikan</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Produk Racikan, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-produk-racikan-modal')"><i
                            class="fa-solid fa-plus "></i></button>
                    --}}
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <a class="btn btn-secondary fw-bold" href="/produk">
                            <i class="fa-solid fa-reply"></i>Kembali</a>
                        <button wire:click="tambahProdukRacikan" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>

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
                    {{-- <div>
                        <select class="form-control" wire:model.live.debounce.300ms="produk_idproduk_filter ">
                            <option value="" selected>-- Semua Filter --</option>
                            Masukan Option Filter disini
                        </select>
                    </div> --}}

                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf"> <i
                                class="fa-regular fa-file-pdf"></i>
                            PDF</button>

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
                                        {{-- <th>Produk</th> --}}
                                        <th class="text-center">Bahan</th>
                                        <th class="text-center">Takaran/Jumlah</th>
                                        <th class="text-center">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produkracikans as $key => $datas)
                                    <tr>
                                        <td>{{ $produkracikans->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editProdukRacikan('{{ $datas->idproduk_racikan }}')">Edit
                                                    {{-- <i class="fa-regular fa-pen-to-square"></i> --}}
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationProdukRacikan('{{ $datas->idproduk_racikan }}')">Hapus</button>
                                            </div>
                                        </td>
                                        {{-- <td>{{ $datas->produk_idproduk }}</td> --}}
                                        <td>{{ $datas->bahan->nama ?? '-' }}</td>
                                        <td class="text-end">{{ $datas->takaran }}</td>
                                        <td>{{ $datas->satuan }}</td>
                                        {{-- <td>{{ $datas->created_at }}</td> --}}
                                        {{-- <td>{{ $datas->updated_at }}</td> --}}
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
                            Showing {{ $produkracikans->firstItem() }} to {{ $produkracikans->lastItem() }}
                        </div>
                        <div>
                            Per Page
                            <select name="form-control" wire:model.live='perPage'>
                                <option value="10">10</option>
                                <option value="100">100</option>
                                <option value="10000">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2 col-lg-6 d-flex justify-content-end align-items-center">
                        {{ $produkracikans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah ProdukRacikan -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Racikan</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idproduk_racikan">

                    <div class="form-group row mb-3 d-none">
                        <label for="produk_idproduk"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Produk<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="produk_idproduk" class="form-control" wire:model="produk_idproduk"
                                placeholder="Masukkan produk_idproduk">
                            @error('produk_idproduk')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="bahan_idbahan"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Bahan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex justify-content-between align-items-center gap-1">
                                <input class="form-control" id="bahanName" wire:model="bahanName">
                                <button type="button" class="btn btn-primary"
                                    wire:click="$dispatch('buka-modal-lov-bahan')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            @error('bahan_idbahan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3 align-items-center">
                        <label for="takaran" class="col-form-label col-12 col-lg-3 fw-bold text-lg-end">
                            Takaran/
                            Jumlah<span class="text-danger">*</span>
                        </label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="takaran" class="form-control" wire:model="takaran"
                                placeholder="Masukkan takaran">
                            @error('takaran')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Satuan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="satuan" class="form-control" wire:model="satuan"
                                placeholder="Masukkan satuan">
                            @error('satuan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storeProdukRacikan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit ProdukRacikan -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit ProdukRacikan</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="idproduk_racikan">

                    <div class="form-group row mb-3 d-none">
                        <label for="produk_idproduk"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Produk
                            <span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="produk_idproduk" class="form-control" wire:model="produk_idproduk"
                                placeholder="Masukkan produk_idproduk">
                            @error('produk_idproduk')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="bahan_idbahan"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Bahan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex justify-content-between align-items-center gap-1">
                                <input class="form-control" id="bahanName" wire:model="bahanName">
                                <button type="button" class="btn btn-primary"
                                    wire:click="$dispatch('buka-modal-lov-bahan')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            @error('bahan_idbahan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="takaran"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Takaran/Jumlah<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="takaran" class="form-control" wire:model="takaran"
                                placeholder="Masukkan takaran">
                            @error('takaran')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Satuan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="satuan" class="form-control" wire:model="satuan"
                                placeholder="Masukkan satuan">
                            @error('satuan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateProdukRacikan">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @livewire('livewire.produk-racikan-lov') --}}
    @livewire('bahan-lov')
</div>



<script>
    window.addEventListener('close-produk-racikan-modal', event =>{ 
            $('#addProdukRacikanModal').modal('hide'); 
            $('#editProdukRacikanModal').modal('hide'); 
            $('#deleteProdukRacikanModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#produk-racikanModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnproduk-racikanModalLovAdd').click(function(){ 
   //       $('#produk-racikanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnproduk-racikanModalLovEdit').click(function(){ 
   //       $('#produk-racikanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addProdukRacikanModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-produk-racikan-modal', event =>{ 
            $('#addProdukRacikanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-produk-racikan-modal', event =>{ 
            $('#editProdukRacikanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-produk-racikan-modal', event =>{ 
            $('#deleteProdukRacikanModal').modal('show'); 
        }); 
 
</script>

<script>
    window.addEventListener('produk-racikan-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('produk-racikan-error', event => {
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
        Livewire.dispatch('hapusProdukRacikan'); // untuk Livewire v3
        }
        });
        });   
                
</script>