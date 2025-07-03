<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Pembelian</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Pembelian, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-pembelian-modal')"><i class="fa-solid fa-plus "></i></button>
                    --}}
                    <button wire:click="tambahPembelian" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
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
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglstart">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglend">
                    {{-- <div>
                        <select class="form-control" wire:model.live.debounce.300ms="tanggal_filter ">
                            <option value="" selected>-- Semua Filter --</option>
                            Masukan Option Filter disini
                        </select>
                    </div> --}}

                    <div class="d-flex justify-content-start align-items-center gap-1">
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
                                        <th class="text-center">No</th>
                                        <th class="text-center">Action</th>
                                        {{-- <th>Id Pembelian</th> --}}
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Total Item</th>
                                        <th class="text-center">Total Harga Beli</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelians as $key => $datas)
                                    <tr
                                        class="{{ $datas->stok <= $datas->stok_minimum ? 'bg-danger text-white' : '' }}">
                                        <td>{{ $pembelians->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editPembelian('{{ $datas->idpembelian }}')">
                                                    <i class='bx bx-pen-alt'></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationPembelian('{{ $datas->idpembelian }}')"><i
                                                        class='bx bx-trash'></i>
                                                </button>
                                                <a class="btn btn-sm text-white bg-success"
                                                    href="/pembeliandtl/{{ $datas->idpembelian }}">
                                                    <i class="fa-solid fa-list"></i> </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($datas->status === 'unsaved')
                                            <span class="badge font-semibold bg-danger">Unsaved</span>
                                            @else
                                            <span class="badge font-bold bg-success">Saved</span>
                                            @endif
                                        </td>
                                        <td>{{ $datas->tanggal }}</td>
                                        <td>{{ $datas->supplier->nama ?? '-' }}</td>
                                        <td>{{ $datas->user->name ?? '-' }}</td>
                                        @php
                                        $total_item = App\Models\Pembeliandtl::where('pembelian_idpembelian',
                                        $datas->idpembelian)->sum('jumlah');
                                        @endphp
                                        <td class="text-end">{{ $total_item }}</td>
                                        @php
                                        $total_hargabeli = App\Models\Pembeliandtl::where('pembelian_idpembelian',
                                        $datas->idpembelian)
                                        ->get()
                                        ->sum(function ($item) {
                                        return $item->jumlah * $item->harga_beli;
                                        });
                                        @endphp
                                        <td class="text-end">Rp {{ number_format($total_hargabeli, 0, ',', '.') }}</td>
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
                            Showing {{ $pembelians->firstItem() }} to {{ $pembelians->lastItem() }}
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
                        {{ $pembelians->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pembelian -->
    @if($isOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembelian</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID (optional untuk future use) -->
                    <input type="hidden" wire:model="idpembelian">

                    <div class="form-group row mb-3">
                        <label for="tanggal" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Tanggal
                            Pembelian</label>
                        <div class="col-12 col-lg-9">
                            <input type="date" id="tanggal" class="form-control" wire:model="tanggal"
                                placeholder="Masukkan tanggal">
                            @error('tanggal')
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

                    {{-- <div class="form-group row mb-3">
                        <label for="user_iduser" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">User
                            <span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="user_iduser" class="form-control" wire:model="user_iduser"
                                placeholder="Masukkan user_iduser">
                            @error('user_iduser')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="form-group row mb-3">
                        <label for="total_item" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Total
                            Item</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="total_item" class="form-control" wire:model="total_item"
                                placeholder="Masukkan total_item">
                            @error('total_item')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="total_hargabeli"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Total
                            Harga Beli</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="total_hargabeli" class="form-control" wire:model="total_hargabeli"
                                placeholder="Masukkan total_hargabeli">
                            @error('total_hargabeli')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-success" wire:click="storePembelian">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Pembelian -->
    @if($isEdit)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true"
        role="dialog">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pembelian</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" wire:model="	idpembelian">

                    <div class="form-group row mb-3">
                        <label for="tanggal" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Tanggal
                            Pembelian</label>
                        <div class="col-12 col-lg-9">
                            <input type="date" id="tanggal" class="form-control" wire:model="tanggal"
                                placeholder="Masukkan tanggal">
                            @error('tanggal')
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

                    {{-- <div class="form-group row mb-3">
                        <label for="user_iduser" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Alamat
                            Pembelian<span class="text-danger">*</span></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="user_iduser" class="form-control" wire:model="user_iduser"
                                placeholder="Masukkan user_iduser">
                            @error('user_iduser')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="form-group row mb-3">
                        <label for="total_item" class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Total
                            Item</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="total_item" class="form-control" wire:model="total_item"
                                placeholder="Masukkan total_item">
                            @error('total_item')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="total_hargabeli"
                            class="col-12 col-lg-3 fw-bold text-lg-end mb-2 mb-lg-0 label">Total
                            Harga Beli</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" id="total_hargabeli" class="form-control" wire:model="total_hargabeli"
                                placeholder="Masukkan total_hargabeli">
                            @error('total_hargabeli')
                            <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updatePembelian">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- @include('livewire.pembelian-lov') --}}
    @livewire('supplier-lov')

</div>



<script>
    window.addEventListener('close-pembelian-modal', event =>{ 
            $('#addPembelianModal').modal('hide'); 
            $('#editPembelianModal').modal('hide'); 
            $('#deletePembelianModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#pembelianModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnpembelianModalLovAdd').click(function(){ 
   //       $('#pembelianModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnpembelianModalLovEdit').click(function(){ 
   //       $('#pembelianModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addPembelianModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-pembelian-modal', event =>{ 
            $('#addPembelianModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-pembelian-modal', event =>{ 
            $('#editPembelianModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-pembelian-modal', event =>{ 
            $('#deletePembelianModal').modal('show'); 
        }); 
    
        
 
</script>

<script>
    window.addEventListener('pembelian-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('pembelian-error', event => {
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
        Livewire.dispatch('hapusPembelian'); // untuk Livewire v3
        }
        });
        });   
                
</script>