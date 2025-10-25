<div class="container py-4">

    {{-- 🔹 Notifikasi --}}
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @elseif (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 🔹 Header & Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold">📘 Pembukuan Keuangan</h4>

            <div class="d-flex gap-2 align-items-center">
                <div>
                    <label class="form-label mb-0 small text-muted">Tanggal</label>
                    <input type="date" wire:model.live="tanggal" class="form-control form-control-sm">
                </div>

                {{-- 🔹 Shift hanya bisa diubah oleh Admin --}}
                @if (Auth::user()->role === 'admin')
                <div>
                    <label class="form-label mb-0 small text-muted">Shift</label>
                    <select wire:model.live="shift" class="form-select form-select-sm">
                        <option value="1">Shift 1 (08:00 - 16:00)</option>
                        <option value="2">Shift 2 (16:00 - 00:00)</option>
                        <option value="3">Shift 3 (00:00 - 08:00)</option>
                    </select>
                </div>
                @else
                <div>
                    <label class="form-label mb-0 small text-muted">Shift</label>
                    <input type="text" class="form-control form-control-sm" value="Shift {{ Auth::user()->shift }}"
                        readonly>
                </div>
                @endif

                <button wire:click="loadSummary" class="btn btn-outline-secondary btn-sm">
                    🔄 Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- 🔹 Ringkasan Keuangan --}}
    <div class="row g-3 mb-3">
        @php $sum = $summary ?? []; @endphp

        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6>Total Penjualan</h6>
                    <h4 class="text-success fw-bold">
                        Rp {{ number_format($sum['total_penjualan'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6>Total Modal</h6>
                    <h4 class="text-primary fw-bold">
                        Rp {{ number_format($sum['total_modal'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h6>Kas Masuk / Keluar</h6>
                    <h5 class="fw-bold">
                        + Rp {{ number_format($sum['kas_masuk'] ?? 0, 0, ',', '.') }}<br>
                        - Rp {{ number_format($sum['kas_keluar'] ?? 0, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6>Laba Bersih</h6>
                    <h4 class="text-danger fw-bold">
                        Rp {{ number_format($sum['laba'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Rangkuman Kas --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Penjualan</td>
                        <td class="text-end">Rp {{ number_format($sum['total_penjualan'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Modal</td>
                        <td class="text-end">Rp {{ number_format($sum['total_modal'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba Kotor</td>
                        <td class="text-end">Rp {{ number_format($sum['laba'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Kas Masuk (manual)</td>
                        <td class="text-end">Rp {{ number_format($sum['kas_masuk'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Kas Keluar (manual)</td>
                        <td class="text-end">Rp {{ number_format($sum['kas_keluar'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-warning fw-bold">
                        <td>Kas Seharusnya di Laci</td>
                        <td class="text-end">Rp {{ number_format($sum['expected_cash'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-end mt-3">
                <button wire:click="openCloseModal" class="btn btn-primary btn-sm">
                    🔒 Tutup Shift
                </button>
                <button
                    onclick="window.open('{{ route('laporan.pembukuan.pdf', ['tanggal' => $tanggal, 'shift' => $shift]) }}', '_blank')"
                    class="btn btn-outline-success btn-sm">
                    📄 Cetak PDF
                </button>
                <button
                    onclick="window.open('{{ route('laporan.pembukuan.excel', ['tanggal' => $tanggal, 'shift' => $shift]) }}', '_blank')"
                    class="btn btn-outline-secondary btn-sm">
                    📊 Export Excel
                </button>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal Tutup Shift --}}
    @if ($showCloseModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tutup Shift {{ $shift }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCloseModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Kas Fisik di Laci</label>
                        <input type="number" wire:model="kas_fisik" class="form-control">
                        @error('kas_fisik') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Keterangan</label>
                        <textarea wire:model="keterangan_close" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button wire:click="closeShift" class="btn btn-success">💾 Simpan & Tutup</button>
                    <button wire:click="$set('showCloseModal', false)" class="btn btn-outline-secondary">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 🔹 Riwayat Tutup Shift --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span>Riwayat Tutup Shift</span>
            <input type="date" wire:model.live="tanggal" class="form-control form-control-sm w-auto">
        </div>
        <div class="card-body p-0">
            @if (count($riwayatShift) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0 align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Total Penjualan</th>
                            <th>Kas Fisik</th>
                            <th>Selisih</th>
                            <th>Ditutup Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayatShift as $r)
                        <tr class="text-center">
                            <td>{{ $r->tanggal }}</td>
                            <td><span class="badge bg-info">Shift {{ $r->shift }}</span></td>
                            <td class="text-end">Rp {{ number_format($r->total_penjualan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($r->kas_fisik, 0, ',', '.') }}</td>
                            <td
                                class="{{ $r->selisih == 0 ? 'text-success' : ($r->selisih > 0 ? 'text-primary' : 'text-danger') }}">
                                {{ $r->selisih >= 0 ? '+' : '-' }} Rp {{ number_format(abs($r->selisih), 0, ',', '.') }}
                            </td>
                            <td>{{ $r->user ?? '-' }}</td>
                            <td>
                                <a href="{{ route('laporan.pembukuan.pdf', ['tanggal' => $r->tanggal, 'shift' => $r->shift]) }}"
                                    target="_blank" class="btn btn-outline-success btn-sm">
                                    📄 PDF
                                </a>
                                <a href="{{ route('laporan.pembukuan.excel', ['tanggal' => $r->tanggal, 'shift' => $r->shift]) }}"
                                    target="_blank" class="btn btn-outline-secondary btn-sm">
                                    📊 Excel
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center my-3 text-muted">Belum ada shift yang ditutup.</p>
            @endif
        </div>
    </div>

    <hr class="my-4">

    {{-- 🔹 Detail Barang Terjual --}}
    <h5 class="fw-bold text-primary">Detail Barang Terjual</h5>
    <div class="d-flex gap-2 align-items-center mb-3">
        <div>
            <label class="form-label small text-muted mb-0">Dari</label>
            <input type="date" wire:model.live="tanggalAwal" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label small text-muted mb-0">Sampai</label>
            <input type="date" wire:model.live="tanggalAkhir" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label small text-muted mb-0">Shift</label>
            <select wire:model.live="shiftFilter" class="form-select form-select-sm">
                <option value="">Semua Shift</option>
                <option value="1">Shift 1</option>
                <option value="2">Shift 2</option>
                <option value="3">Shift 3</option>
            </select>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Subtotal</th>
                    <th>Laba Kotor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanPenjualan as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-end">{{ number_format($item->qty, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->laba_kotor, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔹 Detail Pembelian (Restok Bahan) --}}
    <h5 class="fw-bold text-danger">Detail Pembelian (Restok Bahan)</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Total</th>
                    <th>Supplier</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanPengeluaran as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->bahan_nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->tanggal }}</td>
                    <td class="text-end">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->total, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->supplier_id ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data pembelian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>