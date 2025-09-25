<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">
            Home <i class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> Laporan Pendapatan
        </div>

        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold">Laporan Pendapatan Bulanan</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        melihat dan mengunduh laporan pendapatan bulanan.
                    </div>
                </div>
            </div>
        </div>

        <div wire:loading wire:target="exportToPdf, search, tglstart, tglend" class="loading-spinner">
            <div class="loader"></div>
        </div>

        <div class="py-2 mb-2">
            <div class="d-lg-flex justify-content-between align-items-center">
                <div>
                    <input type="text" class="form-control mb-1 mb-lg-0" placeholder="Search"
                        wire:model.live.debounce.300ms="search">
                </div>
                <div class="d-lg-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglstart">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglend">
                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf">
                            <i class="fa-regular fa-file-pdf"></i> PDF
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
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Bulan</th>
                                        {{-- <th class="text-center">Total Pendapatan</th> --}}
                                        <th class="text-center">Total Penjualan</th>
                                        <th class="text-center">Total Modal</th>
                                        <th class="text-center">Keuntungan</th>
                                        <th class="text-center">Kerugian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporan as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="text-center">{{ $laporan->firstItem() + $index }}</td>
                                        <td class="text-center">{{ $item->bulan }}</td>
                                        {{-- <td class="text-end">Rp {{ number_format($item->total_pendapatan, 0, ',',
                                            '.')
                                            }}</td> --}}
                                        <td class="text-end">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">Rp {{ number_format($item->total_modal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end text-success fw-bold">Rp {{ number_format($item->keuntungan,
                                            0, ',', '.') }}</td>
                                        <td class="text-end text-danger">Rp {{ number_format($item->kerugian, 0, ',',
                                            '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-500 italic">🙁 Data tidak
                                            ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-lg-6 d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $laporan->firstItem() }} sampai {{ $laporan->lastItem() }}
                        </div>
                        <div>
                            Per Halaman
                            <select name="form-control" wire:model.live='perPage'>
                                <option value="10">10</option>
                                <option value="100">100</option>
                                <option value="10000">Semua</option>
                            </select>
                        </div>
                    </div>
                    {{-- <div class="mt-2 col-lg-6 d-flex justify-content-end align-items-center">
                        {{ $laporan->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    Livewire.on('swal:alert', param => {
        Swal.fire({
            icon: param.type,
            title: param.title,
            text: param.text,
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>