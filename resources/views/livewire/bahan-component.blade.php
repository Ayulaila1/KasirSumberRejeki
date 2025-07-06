<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Bahan</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Bahan, termasuk menambahkan atau mengubah informasi.
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
                    {{-- <div>
                        <select class="form-control" wire:model.live.debounce.300ms="nama_filter ">
                            <option value="" selected>-- Semua Filter --</option>
                            Masukan Option Filter disini
                        </select>
                    </div> --}}

                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf"> <i
                                class="fa-regular fa-file-pdf"></i>
                            PDF</button>
                        <button wire:click="tambahBahan" class="btn btn-primary">
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
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bahans as $key => $datas)
                                    <tr
                                        class="{{ $datas->stok <= $datas->stok_minimum ? 'bg-danger text-white' : '' }}">
                                        <td>{{ $bahans->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editBahan('{{ $datas->idbahan }}')"><i
                                                        class='bx bx-pen-alt'></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationBahan('{{ $datas->idbahan }}')"><i
                                                        class='bx bx-trash'></i></button>
                                            </div>
                                        </td>
                                        <td>{{ $datas->nama }}</td>
                                        <td class="text-end">{{ number_format($datas->stok, 0, ',', '.') }}</td>
                                        <td>{{ $datas->satuan }}</td>
                                        <td>{{ $datas->jenis }}</td>
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
                            Showing {{ $bahans->firstItem() }} to {{ $bahans->lastItem() }}
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
                        {{ $bahans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Bahan -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bahan</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idbahan">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Bahan<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Satuan
                            Bahan<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="satuan" class="form-control" wire:model="satuan"
                                placeholder="Masukkan satuan">
                            @error('satuan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="jenis" class="col-12 col-lg-3 fw-bold text-lg-end">Jenis Produk<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <select id="jenis" class="form-control" wire:model="jenis">
                                <option value="">Pilih Jenis Produk</option>
                                <option value="Racikan">Racikan</option> <!-- Produk buatan sendiri, diracik -->
                                <option value="Siap Saji">Siap Saji</option> <!-- Produk jadi, langsung jual -->
                                <option value="Bahan Mentah">Bahan Mentah</option> <!-- Digunakan untuk meracik -->
                                <option value="Bumbu">Bumbu</option> <!-- Penyedap masakan -->
                                <option value="Lainnya">Lainnya</option> <!-- Produk siap pakai, tidak perlu diracik -->
                                <option value="Titipan">Titipan</option>
                            </select>
                            @error('jenis') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storeBahan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Bahan -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bahan</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="idbahan">

                    <div class="form-group row mb-3">
                        <label for="nama" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Nama
                            Bahan<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="nama" class="form-control" wire:model="nama"
                                placeholder="Masukkan nama">
                            @error('nama')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="satuan" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Satuan
                            Bahan<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="satuan" class="form-control" wire:model="satuan"
                                placeholder="Masukkan satuan">
                            @error('satuan')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="jenis" class="col-12 col-lg-3 fw-bold text-lg-end">Jenis Produk<span
                                class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <select id="jenis" class="form-control" wire:model="jenis">
                                <option value="">Pilih Jenis Produk</option>
                                <option value="Racikan">Racikan</option> <!-- Produk buatan sendiri, diracik -->
                                <option value="Siap Saji">Siap Saji</option> <!-- Produk jadi, langsung jual -->
                                <option value="Bahan Mentah">Bahan Mentah</option> <!-- Digunakan untuk meracik -->
                                <option value="Bumbu">Bumbu</option> <!-- Penyedap masakan -->
                                <option value="Lainnya">Lainnya</option> <!-- Produk siap pakai, tidak perlu diracik -->
                                <option value="Titipan">Titipan</option>
                            </select>
                            @error('jenis') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateBahan">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @include('livewire.bahan-lov') --}}

</div>



<script>
    window.addEventListener('close-bahan-modal', event =>{ 
            $('#addBahanModal').modal('hide'); 
            $('#editBahanModal').modal('hide'); 
            $('#deleteBahanModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#bahanModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnbahanModalLovAdd').click(function(){ 
   //       $('#bahanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnbahanModalLovEdit').click(function(){ 
   //       $('#bahanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addBahanModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-bahan-modal', event =>{ 
            $('#addBahanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-bahan-modal', event =>{ 
            $('#editBahanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-bahan-modal', event =>{ 
            $('#deleteBahanModal').modal('show'); 
        }); 
    
        
 
</script>

<script>
    window.addEventListener('bahan-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('bahan-error', event => {
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
        Livewire.dispatch('hapusBahan'); // untuk Livewire v3
        }
        });
        });   
                
</script>