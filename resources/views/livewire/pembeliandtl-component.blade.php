<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Pembelian Detail</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Pembelian Detail, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-pembeliandtl-modal')"><i class="fa-solid fa-plus "></i></button>
                    --}}
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <a class="btn btn-secondary fw-bold" href="/pembelian">
                            <i class="fa-solid fa-reply"></i>Kembali</a>
                        <button wire:click="tambahPembeliandtl" @if($pembelian->status==='saved' ) disabled @endif
                            class="btn btn-primary">
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
                        <select class="form-control" wire:model.live.debounce.300ms="bahan_idbahan_filter ">
                            <option value="" selected>-- Semua Filter --</option>
                            Masukan Option Filter disini
                        </select>
                    </div> --}}

                    <div class="d-flex justify-content-start align-items-center gap-1">

                        @if ($pembelian->status == 'saved')
                        <button class="btn btn-warning text-white d-flex align-items-center justify-content-center"
                            wire:click="unsavedPembeliandtl">
                            <i class="fa-solid fa-rotate-left me-2"></i> Batal
                        </button>
                        @else
                        <button class="btn btn-success d-flex align-items-center justify-content-center"
                            wire:click="simpanPembeliandtl" @if ($pembelian->status === 'saved')
                            @endif>
                            <i class="fa-regular fa-floppy-disk me-2"></i> Simpan
                        </button>
                        @endif
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
                                        <th class="text-center">Bahan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Isi Per Satuan/ml</th>
                                        <th class="text-center">Harga Beli</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembeliandtls as $key => $datas)
                                    <tr>
                                        <td>{{ $pembeliandtls->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editPembeliandtl('{{ $datas->idpembeliandtl }}')"
                                                    @if($pembelian->status==='saved' ) disabled @endif>Edit
                                                    {{-- <i class="fa-regular fa-pen-to-square"></i> --}}
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationPembeliandtl('{{ $datas->idpembeliandtl }}')"
                                                    @if($pembelian->status==='saved' ) disabled @endif>Hapus</button>

                                            </div>
                                        </td>
                                        {{-- <td>{{ $datas->pembelian->pembelian ?? '-' }}</td> --}}
                                        <td>{{ $datas->bahan->nama ?? '-' }}</td>
                                        <td class="text-end">{{ $datas->jumlah }}</td>
                                        <td class="text-end">{{ $datas->isi_per_satuan}}</td>
                                        <td class="text-end">Rp {{ number_format($datas->harga_beli, 0, ',', '.') }}
                                        </td>
                                        @php
                                        $subtotal = $datas->jumlah * $datas->harga_beli;
                                        @endphp
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
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
                            Showing {{ $pembeliandtls->firstItem() }} to {{ $pembeliandtls->lastItem() }}
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
                        {{ $pembeliandtls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pembeliandtl -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembelian Detail</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idpembeliandtl">

                    <div class="form-group row mb-3 d-none">
                        <label for="pembelian_idpembelian"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Pembelian<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="pembelian_idpembelian" class="form-control"
                                wire:model="pembelian_idpembelian" placeholder="Masukkan pembelian_idpembelian">
                            @error('pembelian_idpembelian')
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
                        <label for="jumlah" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Jumlah<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="number" id="jumlah" class="form-control" wire:model="jumlah"
                                placeholder="Masukkan jumlah">
                            @error('jumlah')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="isi_per_satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Isi
                            per Satuan/ml<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="number" id="isi_per_satuan" class="form-control" wire:model="isi_per_satuan"
                                placeholder="Masukkan isi per satuan">
                            @error('isi_per_satuan')
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

                    {{-- <div class="form-group row mb-3">
                        <label for="subtotal" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Satuan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="subtotal" class="form-control" wire:model="subtotal"
                                placeholder="Masukkan subtotal">
                            @error('subtotal')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div> --}}

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storePembeliandtl">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Pembeliandtl -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pembeliandtl</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="idpembeliandtl">

                    <div class="form-group row mb-3 d-none">
                        <label for="pembelian_idpembelian"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Bahan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="pembelian_idpembelian" class="form-control"
                                wire:model="pembelian_idpembelian" placeholder="Masukkan pembelian_idpembelian">
                            @error('pembelian_idpembelian')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="bahan_idbahan"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Jumlah<span
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
                        <label for="jumlah" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Bahan<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="number" id="jumlah" class="form-control" wire:model="jumlah"
                                placeholder="Masukkan jumlah">
                            @error('jumlah')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="isi_per_satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Isi
                            per Satuan/ml<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="number" id="isi_per_satuan" class="form-control" wire:model="isi_per_satuan"
                                placeholder="Masukkan isi per satuan">
                            @error('isi_per_satuan')
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
                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updatePembeliandtl">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @livewire('livewire.bahan-lov') --}}
    @livewire('bahan-lov')
</div>



<script>
    window.addEventListener('close-pembeliandtl-modal', event =>{ 
            $('#addPembeliandtlModal').modal('hide'); 
            $('#editPembeliandtlModal').modal('hide'); 
            $('#deletePembeliandtlModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#pembeliandtlModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnpembeliandtlModalLovAdd').click(function(){ 
   //       $('#pembeliandtlModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnpembeliandtlModalLovEdit').click(function(){ 
   //       $('#pembeliandtlModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addPembeliandtlModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-pembeliandtl-modal', event =>{ 
            $('#addPembeliandtlModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-pembeliandtl-modal', event =>{ 
            $('#editPembeliandtlModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-pembeliandtl-modal', event =>{ 
            $('#deletePembeliandtlModal').modal('show'); 
        }); 
 
</script>

<script>
    window.addEventListener('pembeliandtl-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('pembeliandtl-error', event => {
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
        Livewire.dispatch('hapusPembeliandtl'); // untuk Livewire v3
        }
        });
        });   

        window.addEventListener('stok-disimpan', event => {
        Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.icon,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Oke!'
        });
        });       

        window.addEventListener('stok-dibatalkan', event => {
        Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.icon,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Oke!'
        });
        });

</script>