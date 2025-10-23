<div class="container-fluid mb-5" style="margin-top: 40px">
    <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">
        Home <i class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> Hold
    </div>

    <div class="container-fluid px-0 p-3 rounded-top">
        <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
            <div class="mb-3 mb-lg-0">
                <p class="p-0 m-0 h3 fw-bold">Daftar Keranjang Hold</p>
                <div class="mt-2" style="color: #868686">
                    <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                    Menu ini digunakan untuk melihat dan melanjutkan transaksi yang di-hold.
                </div>
            </div>
        </div>
    </div>

    {{-- Loader --}}
    <div wire:loading wire:target="search, delete, lanjutkan, printPdf" class="loading-spinner">
        <div class="loader"></div>
    </div>
    {{-- End Loader --}}

    <div class="py-2 mb-2">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div>
                <input type="text" class="form-control mb-1 mb-lg-0" placeholder="Cari customer..."
                    wire:model.live.debounce.300ms="search">
            </div>
            <div>
                <button wire:click="printPdf" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="fas fa-print"></i> Print PDF
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-2 pb-0 border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width:100%; white-space:nowrap">
                            {{-- MODIFIKASI HEADER TABEL --}}
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 3%">No</th>
                                    <th class="text-center">Kode Transaksi</th>
                                    {{-- HAPUS KOLOM CUSTOMER DARI SINI --}}
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Tanggal</th>
                                    <th style="width: 100px; text-align: center">Action</th>
                                </tr>
                            </thead>
                            {{-- MODIFIKASI BODY TABEL --}}
                            <tbody>
                                @php $counter = 1; @endphp
                                {{-- Gunakan $groupedHolds yang baru --}}
                                @forelse($groupedHolds as $customerName => $holds)

                                <tr style="background-color: #f0f0f0;">
                                    <td colspan="5" class="fw-bold ps-3">
                                        Customer: {{ $customerName }}
                                    </td>
                                </tr>

                                @foreach($holds as $hold)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $hold->kode_transaksi }}</td>
                                    {{-- HAPUS KOLOM CUSTOMER DARI SINI --}}
                                    <td class="text-end">Rp {{ number_format($hold->total, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $hold->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button wire:click="lanjutkan({{ $hold->id }})"
                                                class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                                <i class="fas fa-play"></i> Lanjutkan
                                            </button>
                                            <button wire:click="delete({{ $hold->id }})"
                                                class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    {{-- Sesuaikan colspan menjadi 5 --}}
                                    <td colspan="5" class="text-center">Belum ada transaksi hold</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination Info --}}
            {{-- <div class="row">
                <div class="col-lg-6 d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $holds->firstItem() }} to {{ $holds->lastItem() }}
                    </div>
                    <div>
                        Per Page
                        <select class="form-control" wire:model.live="perPage"
                            style="width: auto; display:inline-block">
                            <option value="10">10</option>
                            <option value="100">100</option>
                            <option value="10000">All</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2 col-lg-6 d-flex justify-content-end align-items-center">
                    {{ $holds->links() }}
                </div>
            </div> --}}

        </div>
    </div>
</div>
