<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Supplier</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Supplier, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-supplier-modal')"><i class="fa-solid fa-plus "></i></button>
                    --}}
                    <button wire:click="tambahSupplier" class="btn btn-primary">
                        Tambah Supplier
                    </button>
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
                        <select class="form-control" wire:model.live.debounce.300ms="nama_filter ">
                            <option value="" selected>-- Semua Filter --</option>
                            Masukan Option Filter disini
                        </select>
                    </div> --}}

                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-success text-white d-flex align-items-center" style="background: #77e779"
                            wire:click="exportToExcel"> <i class="fa-solid fa-download me-2"></i>
                            Excel</button>
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf"> <i
                                class="fa-solid fa-download me-2"></i>
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
                                        <th>No</th>
                                        <th>Action</th>
                                        <th>Nama</th>
                                        <th>Kontak</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $key => $datas)
                                    <tr
                                        class="{{ $datas->stok <= $datas->stok_minimum ? 'bg-danger text-white' : '' }}">
                                        <td>{{ $suppliers->firstItem() + $key }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning text-dark"
                                                wire:click="editSupplier('{{ $datas->idsupplier }}')">Edit
                                                {{-- <i class="fa-regular fa-pen-to-square"></i> --}}
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="deleteConfirmationSupplier('{{ $datas->idsupplier }}')">Hapus</button>
                                        </td>
                                        <td>{{ $datas->nama }}</td>
                                        <td>{{ $datas->kontak }}</td>
                                        <td>{{ $datas->alamat }}</td>
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
                            Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }}
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
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Supplier -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idsupplier">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="kontak" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Kontak<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="kontak" class="form-control" wire:model="kontak"
                                placeholder="Masukkan kontak">
                            @error('kontak')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="alamat" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Alamat
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="alamat" class="form-control" wire:model="alamat"
                                placeholder="Masukkan alamat">
                            @error('alamat')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storeSupplier">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Supplier -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="idsupplier">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="kontak" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Kontak<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="kontak" class="form-control" wire:model="kontak"
                                placeholder="Masukkan kontak">
                            @error('kontak')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="alamat" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Alamat
                            Supplier<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="alamat" class="form-control" wire:model="alamat"
                                placeholder="Masukkan alamat">
                            @error('alamat')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateSupplier">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @include('livewire.supplier-lov') --}}

</div>



<script>
    window.addEventListener('close-supplier-modal', event =>{ 
            $('#addSupplierModal').modal('hide'); 
            $('#editSupplierModal').modal('hide'); 
            $('#deleteSupplierModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#supplierModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnsupplierModalLovAdd').click(function(){ 
   //       $('#supplierModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnsupplierModalLovEdit').click(function(){ 
   //       $('#supplierModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addSupplierModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-supplier-modal', event =>{ 
            $('#addSupplierModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-supplier-modal', event =>{ 
            $('#editSupplierModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-supplier-modal', event =>{ 
            $('#deleteSupplierModal').modal('show'); 
        }); 
    
        
 
</script>

<script>
    window.addEventListener('supplier-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('supplier-error', event => {
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
        Livewire.dispatch('hapusSupplier'); // untuk Livewire v3
        }
        });
        });   
                
</script>