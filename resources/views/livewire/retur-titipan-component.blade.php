<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">Home <i
                class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')</div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Retur Titipan</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Retur Titipan, termasuk menambahkan atau mengubah informasi.
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-primary" style="z-index:9999; position:relative"
                        wire:click="$dispatch('show-add-retur-titipan-modal')"><i
                            class="fa-solid fa-plus "></i></button>
                    --}}
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <a class="btn btn-secondary fw-bold" href="/produk">
                            <i class="fa-solid fa-reply"></i>Kembali</a>
                        <button wire:click="tambahReturTitipan" class="btn btn-primary">
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
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Inline input untuk retur baru --}}
                                    <tr class="bg-light">
                                        <td>#</td>
                                        <td>
                                            <form wire:submit.prevent="simpanReturBaru">
                                                {{-- input2 --}}
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                            </form>
                                        </td>
                                        <td>
                                            <input type="date" wire:model="tanggalBaru" class="form-control">
                                        </td>
                                        <td>-</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" readonly wire:model="produkNameBaru"
                                                    class="form-control">
                                                <!-- Benar -->
                                                {{-- <button wire:click="@this.emit('produkDipilih', 1)">Pilih</button>
                                                --}}
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" wire:model="qtyBaru" class="form-control" min="1">
                                        </td>
                                        <td>
                                            <input type="text" wire:model="keteranganBaru" class="form-control">
                                        </td>
                                    </tr>
                                    @foreach ($returtitipans as $key => $datas)
                                    <tr>
                                        <td>{{ $returtitipans->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-warning text-dark"
                                                    wire:click="editReturTitipan('{{ $datas->idretur_titipan }}')">Edit</button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="deleteConfirmationReturTitipan('{{ $datas->idretur_titipan }}')">Hapus</button>
                                            </div>
                                        </td>
                                        <td>{{ $datas->tanggal }}</td>
                                        <td>{{ $datas->supplier->nama ?? '-' }}</td>
                                        <td>{{ $datas->produk->nama ?? '-' }}</td>
                                        <td>{{ $datas->qty }}</td>
                                        <td>{{ $datas->keterangan }}</td>
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
                            Showing {{ $returtitipans->firstItem() }} to {{ $returtitipans->lastItem() }}
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
                        {{ $returtitipans->links() }}
                    </div>
                </div>
            </div>
        </div>


        {{-- @livewire('livewire.retur-titipan-lov') --}}
        @livewire('produk-lov')
    </div>



    <script>
        window.addEventListener('close-retur-titipan-modal', event =>{ 
            $('#addReturTitipanModal').modal('hide'); 
            $('#editReturTitipanModal').modal('hide'); 
            $('#deleteReturTitipanModal').modal('hide'); 
        }); 
 
 
  // copy dan aktifkan jika ingin menggunakan LOV  
 
  //       window.addEventListener('close-modal-lov', event =>{ 
   //          $('#retur-titipanModal').modal('hide'); 
   //     }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnretur-titipanModalLovAdd').click(function(){ 
   //       $('#retur-titipanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
  // $(document).ready(function(){ 
   //      $('#btnretur-titipanModalLovEdit').click(function(){ 
   //       $('#retur-titipanModal').modal('show'); 
    //    }); 
  //  }); 
 
 
       $('#addReturTitipanModal').on('shown.bs.modal', function () { 
            $("id input form pertama").focus(); 
        }); 
 
 
        window.addEventListener('show-add-retur-titipan-modal', event =>{ 
            $('#addReturTitipanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-edit-retur-titipan-modal', event =>{ 
            $('#editReturTitipanModal').modal('show'); 
        }); 
 
 
        window.addEventListener('show-delete-confirmation-retur-titipan-modal', event =>{ 
            $('#deleteReturTitipanModal').modal('show'); 
        }); 
 
    </script>

    <script>
        window.addEventListener('retur-titipan-disimpan', event => {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: event.detail.pesan,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
        });
        });

            
        window.addEventListener('retur-titipan-error', event => {
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
        Livewire.dispatch('hapusReturTitipan'); // untuk Livewire v3
        }
        });
        });   
                
    </script>