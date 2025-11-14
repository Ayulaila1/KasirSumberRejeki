<div class="container py-4">

    {{-- ✅ Notifikasi --}}
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3">
        <strong>✅ Sukses:</strong> {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @elseif (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3">
        <strong>⚠️ Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 🧾 Header & Filter --}}
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h4 class="fw-bold mb-0 text-primary">
                📘 Pembukuan Keuangan
            </h4>

            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="form-label small text-muted mb-1">Tanggal</label>
                    <input type="date" wire:model.live="tanggal" class="form-control form-control-sm shadow-sm">
                </div>

                {{-- 🔹 Shift --}}
                @if (Auth::user()->role === 'admin')
                <div>
                    <label class="form-label small text-muted mb-1">Shift</label>
                    <select wire:model.live="shift" class="form-select form-select-sm shadow-sm">
                        <option value="1">Shift 1 (08:00 - 16:00)</option>
                        <option value="2">Shift 2 (16:00 - 00:00)</option>
                        <option value="3">Shift 3 (00:00 - 08:00)</option>
                    </select>
                </div>
                @else
                <div>
                    <label class="form-label small text-muted mb-1">Shift</label>
                    <input type="text" readonly class="form-control form-control-sm bg-light shadow-sm"
                        value="Shift {{ Auth::user()->shift }}">
                </div>
                @endif

                <button wire:click="loadSummary" class="btn btn-outline-secondary btn-sm shadow-sm mt-1">
                    🔄 Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- 💰 Ringkasan Keuangan --}}
    <div class="row g-3 mb-4">
        @php $sum = $summary ?? []; @endphp

        <div class="col-md-3">
            <div class="card border-success shadow-sm rounded-3 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Total Penjualan</h6>
                    <h4 class="fw-bold text-success mb-0">
                        Rp {{ number_format($sum['total_penjualan'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary shadow-sm rounded-3 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Total Modal</h6>
                    <h4 class="fw-bold text-primary mb-0">
                        Rp {{ number_format($sum['total_modal'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning shadow-sm rounded-3 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Kas Masuk / Keluar</h6>
                    <h5 class="fw-bold mb-0">
                        + Rp {{ number_format($sum['kas_masuk'] ?? 0, 0, ',', '.') }}<br>
                        - Rp {{ number_format($sum['kas_keluar'] ?? 0, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-danger shadow-sm rounded-3 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Laba Bersih</h6>
                    <h4 class="fw-bold text-danger mb-0">
                        Rp {{ number_format($sum['laba'] ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- 📊 Rangkuman Kas --}}
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body p-0">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light text-center sticky-top shadow-sm">
                    <tr>
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

            <div class="d-flex justify-content-end gap-2 mt-3 px-3 pb-3">
                <button type="button" wire:click="openCloseModal" class="btn btn-primary btn-sm shadow-sm">
                    🔒 Tutup Shift
                </button>
                <button wire:click="exportPDF" class="btn btn-outline-success btn-sm">📄 Cetak
                    PDF</button>
                <button wire:click="exportExcel" class="btn btn-success">
                    📊 Export ke Excel
                </button>
            </div>
        </div>
    </div>

    {{-- 🧾 Riwayat Tutup Shift --}}
    <div class="card shadow-sm mt-4 border-0 rounded-3">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span>Riwayat Tutup Shift</span>
            <input type="date" wire:model.live="tanggal" class="form-control form-control-sm w-auto shadow-sm">
        </div>
        <div class="card-body p-0">
            @if (count($riwayatShift) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0 align-middle">
                    <thead class="table-light text-center sticky-top shadow-sm">
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

    {{-- 📦 Detail Barang Terjual --}}
    <h5 class="fw-bold text-primary mb-3">📦 Detail Barang Terjual</h5>
    <div class="d-flex flex-wrap gap-3 mb-3 align-items-end">
        <div>
            <label class="form-label small text-muted mb-0">Dari</label>
            <input type="date" wire:model.live="tanggalAwal" class="form-control form-control-sm shadow-sm">
        </div>
        <div>
            <label class="form-label small text-muted mb-0">Sampai</label>
            <input type="date" wire:model.live="tanggalAkhir" class="form-control form-control-sm shadow-sm">
        </div>
        <div>
            <label class="form-label small text-muted mb-0">Shift</label>
            <select wire:model.live="shiftFilter" class="form-select form-select-sm shadow-sm">
                <option value="">Semua Shift</option>
                <option value="1">Shift 1</option>
                <option value="2">Shift 2</option>
                <option value="3">Shift 3</option>
            </select>
        </div>
    </div>

    <div class="table-responsive mb-4" style="max-height: 400px; overflow-y:auto;">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light text-center sticky-top shadow-sm">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Produk</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Qty</th>
                    {{-- <th class="text-center">Stok Awal</th>
                    <th class="text-center">Stok Sisa</th> --}}
                    <th class="text-center">Harga Beli</th>
                    <th class="text-center">Harga Jual</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center">Laba Kotor</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanPenjualan as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-center">{{ $item->tanggal }}</td>
                    <td class="text-end">{{ number_format($item->qty, 0, ',', '.') }}</td>

                    {{-- Stok Awal --}}
                    {{-- <td class="text-end @if($item->stok_awal == 0) text-danger fw-bold @endif">
                        {{ number_format($item->stok_awal, 0, ',', '.') }}
                        @if($item->stok_awal == 0)
                        <small class="d-block text-danger">Shift pertama / stok belum ada</small>
                        @endif
                    </td> --}}

                    {{-- Stok Sisa --}}
                    {{-- <td class="text-end @if($item->stok_sisa < 0) text-danger fw-bold @endif">
                        {{ number_format($item->stok_sisa, 0, ',', '.') }}
                    </td> --}}

                    <td class="text-end">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->laba_kotor, 0, ',', '.') }}</td>

                    {{-- Keterangan Shift --}}
                    <td class="text-center">
                        <span class="badge 
                            @if($item->shift == 1) bg-primary
                            @elseif($item->shift == 2) bg-warning text-dark
                            @else bg-success
                            @endif ">
                            {{ $item->keterangan }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">Tidak ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🧵 Detail Pembelian (Restok Bahan) --}}
    <h5 class="fw-bold text-danger mb-3">🧵 Detail Pembelian (Restok Bahan)</h5>
    <div class="table-responsive" style="max-height: 400px; overflow-y:auto;">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light text-center sticky-top shadow-sm">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Bahan</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Harga Beli</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanPengeluaran as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->bahan_nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->tanggal }}</td>
                    <td class="text-end">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->supplier_nama ?? '-' }}</td>
                    <td>
                        @php
                        if (str_contains($item->keterangan, 'Shift 1')) {
                        $warna = 'bg-light text-primary';
                        } elseif (str_contains($item->keterangan, 'Shift 2')) {
                        $warna = 'bg-warning-subtle text-dark';
                        } elseif (str_contains($item->keterangan, 'Shift 3')) {
                        $warna = 'bg-danger-subtle text-dark';
                        } else {
                        $warna = 'bg-light text-muted';
                        }
                        @endphp
                        <span class="badge {{ $warna }} px-3 py-2 rounded-pill shadow-sm border">
                            {{ $item->keterangan ?? '-' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data pembelian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔒 Modal Tutup Shift --}}
    @if($showCloseModal)
    <div class="custom-modal">
        <div class="custom-modal-dialog animate__animated animate__fadeInDown">
            <div class="custom-modal-content">

                {{-- 🟤 Header Coklat --}}
                <div class="custom-modal-header" style="background-color: #8B5E3C;">
                    <h5 class="modal-title fw-semibold text-white">
                        <i class="bi bi-lock-fill text-warning me-2"></i>
                        Tutup Shift Kasir
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                        wire:click="$set('showCloseModal', false)"></button>
                </div>

                {{-- 📋 Body --}}
                <div class="custom-modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kas Seharusnya di Laci</label>
                        <input type="text" class="form-control bg-light rounded-3 shadow-sm" readonly
                            value="Rp {{ number_format($summary['expected_cash'] ?? 0, 0, ',', '.') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kas Fisik di Laci</label>
                        <input type="number" wire:model="kas_fisik" class="form-control rounded-3 shadow-sm"
                            placeholder="Masukkan jumlah kas fisik...">
                        @error('kas_fisik') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea wire:model="keterangan_close" class="form-control rounded-3 shadow-sm"
                            placeholder="Catatan tambahan (opsional)" rows="2"></textarea>
                    </div>
                </div>

                {{-- 🔘 Footer --}}
                <div class="custom-modal-footer">
                    <button type="button" wire:click="closeShift" class="btn btn-success rounded-3 px-4 shadow-sm">
                        ✅ Tutup Shift
                    </button>
                    <button type="button" wire:click="$set('showCloseModal', false)"
                        class="btn btn-outline-secondary rounded-3 px-4">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>