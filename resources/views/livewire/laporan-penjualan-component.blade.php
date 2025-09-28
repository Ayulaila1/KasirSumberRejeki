<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">
        {{-- Breadcrumb --}}
        <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">
            Home <i class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> @yield('title')
        </div>

        {{-- Header --}}
        <div class="container-fluid px-0 p-3 rounded-top">
            <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
                <div class="mb-3 mb-lg-0">
                    <p class="p-0 m-0 h3 fw-bold">Laporan Penjualan</p>
                    <div class="mt-2" style="color: #868686">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>Menu ini digunakan untuk
                        mengelola Data Laporan Penjualan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Loader --}}
        <div wire:loading wire:target="exportToExcel, exportToPdf, search, tglStart, tglEnd" class="loading-spinner">
            <div class="loader"></div>
        </div>

        {{-- Filter --}}
        <div class="py-2 mb-2">
            <div class="d-lg-flex justify-content-between align-items-center">
                <div>
                    <input type="text" class="form-control mb-1 mb-lg-0" placeholder="Search"
                        wire:model.live.debounce.300ms="search">
                </div>
                <div class="d-lg-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglStart">
                    <input type="date" class="form-control" wire:model.live.debounce.300ms="tglEnd">
                    <div class="d-flex justify-content-start align-items-center gap-1">
                        <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf">
                            <i class="fa-regular fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cards Struk --}}
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-2 pb-0 border-0">
                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%;white-space:nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%">No</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Kode</th>
                                        <th class="text-center">Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Harga Jual</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Bayar</th>
                                        <th class="text-center">Kembalian</th>
                                        <th class="text-center">User</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($laporan as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        {{-- Nomor urut --}}
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>

                                        {{-- Tanggal --}}
                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>

                                        {{-- Kode Penjualan --}}
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $item->kode_penjualan }}</td>

                                        {{-- Produk (hasil gabungan) --}}
                                        <td class="px-4 py-3 text-gray-700">{!! $item->produk !!}</td>


                                        {{-- Qty total --}}
                                        <td class="px-4 py-3 text-end">{{ $item->qty }}</td>

                                        {{-- Harga jual (opsional: rata-rata / salah satu produk) --}}
                                        <td class="px-4 py-3 text-end">Rp {{ number_format($item->harga_jual, 0, ',',
                                            '.') }}</td>

                                        {{-- Subtotal total --}}
                                        <td class="px-4 py-3 text-end">Rp {{ number_format($item->subtotal, 0, ',', '.')
                                            }}</td>

                                        {{-- Total transaksi --}}
                                        <td class="px-4 py-3 text-end font-bold text-indigo-600">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>

                                        {{-- Bayar --}}
                                        <td class="px-4 py-3 text-end">Rp {{ number_format($item->bayar, 0, ',', '.') }}
                                        </td>

                                        {{-- Kembalian --}}
                                        <td class="px-4 py-3 text-end">Rp {{ number_format($item->kembalian, 0, ',',
                                            '.') }}</td>

                                        {{-- User --}}
                                        <td class="px-4 py-3">{{ $item->user_id }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11"
                                            class="px-4 py-4 text-center text-gray-500 italic bg-gray-50 rounded-b-lg">
                                            🙁 Data tidak ditemukan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>


                            </table>
                            @forelse($laporan as $p)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="card shadow-sm border-0 h-100" style="font-size: 13px">
                                    <div class="card-body p-3">
                                        {{-- Header --}}
                                        <div class="text-center mb-2">
                                            <h6 class="fw-bold mb-0">{{ $p->kode_penjualan }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M
                                                Y') }}</small>
                                        </div>

                                        {{-- Info Customer --}}
                                        <p class="mb-1"><strong>Meja:</strong> {{ $p->nomor_meja ?? '-' }}</p>
                                        <p class="mb-1"><strong>Customer:</strong> {{ $p->customer_name ?? 'Umum' }}</p>
                                        @if($p->catatan)
                                        <p class="mb-1"><strong>Catatan:</strong> {{ $p->catatan }}</p>
                                        @endif

                                        <hr class="my-2">

                                        {{-- Detail Produk --}}
                                        <table class="table table-sm mb-2" style="font-size: 12px">
                                            <tbody>
                                                @foreach($p->penjualanDtl as $d)
                                                <tr>
                                                    <td>{{ $d->produk->nama }}</td>
                                                    <td class="text-end">
                                                        {{ $d->qty }} x {{ number_format($d->harga_jual,0,',','.') }}
                                                    </td>
                                                    <td class="text-end">
                                                        Rp {{ number_format($d->subtotal,0,',','.') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <hr class="my-2">

                                        {{-- Total --}}
                                        <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($p->total,0,',','.')
                                            }}</p>
                                        <p class="mb-1"><strong>Bayar:</strong> Rp {{ number_format($p->bayar,0,',','.')
                                            }}</p>
                                        <p class="mb-0"><strong>Kembali:</strong> Rp {{
                                            number_format($p->kembalian,0,',','.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">Tidak ada data penjualan</div>
                            </div>
                            @endforelse
                        </div>

                        {{-- Pagination & PerPage --}}
                        <div class="row">
                            <div class=" col-lg-6 d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $laporan->firstItem() }} to {{ $laporan->lastItem() }}
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
                            {{-- <div class="mt-2 col-lg-6 d-flex justify-content-end align-items-center">
                                {{ $laporan->links() }}
                            </div> --}}
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