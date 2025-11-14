<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        {{-- Breadcrumb --}}
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">
            Home <i class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> Retur Titipan
        </div>

        {{-- Header --}}
        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold ">Retur Titipan</p>
                    <div class="mt-2 text-muted" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                        Menu ini digunakan untuk mencatat barang titipan yang dikembalikan ke supplier.
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <a class="btn btn-secondary fw-bold" href="/produk">
                        <i class="fa-solid fa-reply me-1"></i> Kembali
                    </a>
                    <button wire:click="tambahReturTitipan" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                </div>
            </div>
        </div>

        {{-- Loader --}}
        <div wire:loading wire:target="exportToPdf, search, tglstart, tglend" class="loading-spinner">
            <div class="loader"></div>
        </div>

        {{-- Filter & Export --}}
        <div class="py-2 mb-2">
            <div class="d-lg-flex justify-content-between align-items-center">
                <div>
                    <input type="text" class="form-control mb-1 mb-lg-0 " placeholder="Search..."
                        wire:model.live.debounce.300ms="search">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf">
                        <i class="fa-regular fa-file-pdf me-1"></i> PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2 pb-0 border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" style="width:100%;white-space:nowrap">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="width: 3%">No</th>
                                        <th style="width: 80px;">Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Harga Beli</th>
                                        <th>Subtotal</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Baris input retur baru --}}
                                    <tr class="bg-light">
                                        <form wire:submit.prevent="simpanReturBaru">
                                            <td>#</td>
                                            <td>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-check"></i> Simpan
                                                </button>
                                            </td>
                                            <td>
                                                <input type="date" wire:model="tanggalBaru"
                                                    class="form-control form-control-sm">
                                                @error('tanggalBaru') <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>-</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" readonly wire:model="produkNameBaru"
                                                        class="form-control">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="$dispatch('buka-modal-lov-produk')">
                                                        <i class="fa-solid fa-search"></i>
                                                    </button>
                                                </div>
                                                @error('produk_idBaru') <small class="text-danger">{{ $message
                                                    }}</small> @enderror
                                            </td>
                                            <td>
                                                <input type="number" wire:model="qtyBaru"
                                                    class="form-control form-control-sm" min="1">
                                                @error('qtyBaru') <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" wire:model="harga_beliBaru"
                                                    class="form-control form-control-sm" readonly>
                                            </td>
                                            <td>
                                                <input type="number" wire:model="subtotalBaru"
                                                    class="form-control form-control-sm" readonly>
                                            </td>
                                            <td>
                                                <input type="text" wire:model="keteranganBaru"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>{{ Auth::user()->name ?? '-' }}</td>
                                        </form>
                                    </tr>

                                    {{-- Daftar retur --}}
                                    @forelse ($returtitipans as $key => $data)
                                    <tr>
                                        <td class="text-center">{{ $returtitipans->firstItem() + $key }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger"
                                                onclick="hapusReturTitipan({{ $data->idretur_titipan }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">{{ $data->tanggal }}</td>
                                        <td>{{ $data->supplier->nama ?? '-' }}</td>
                                        <td>{{ $data->produk->nama ?? '-' }}</td>
                                        <td class="text-end">{{ $data->qty }}</td>
                                        <td class="text-end">{{ number_format($data->harga_beli ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">{{ number_format($data->subtotal ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $data->keterangan }}</td>
                                        <td>{{ $data->user->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-3">Belum ada data retur
                                            titipan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination & PerPage --}}
                <div class="row">
                    <div class=" col-lg-6 d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $returtitipans->firstItem() }} to {{ $returtitipans->lastItem() }}
                        </div>
                        <div>
                            Per Page
                            <select wire:model.live='perPage' class="form-select form-select-sm d-inline-block"
                                style="width: 80px">
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

        {{-- Modal List of Values Produk --}}
        @livewire('produk-lov')
    </div>

    {{-- 🧩 SweetAlert Scripts --}}
    @push('scripts')
    <script>
        // ✅ Fungsi untuk sukses & error umum
    window.addEventListener('retur-titipan-disimpan', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: event.detail.pesan,
            confirmButtonColor: '#3085d6',
        });
    });

    window.addEventListener('retur-titipan-error', event => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: event.detail.pesan,
        });
    });

    // ✅ Fungsi konfirmasi hapus retur (langsung panggil Livewire)
    function hapusReturTitipan(id) {
        Swal.fire({
            title: 'Yakin ingin hapus retur ini?',
            text: "Stok bahan akan dikembalikan seperti semula!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                // 🔥 panggil method delete langsung ke Livewire
                @this.call('deleteReturTitipan', id);
            }
        });
    }
    </script>
    @endpush
</div>