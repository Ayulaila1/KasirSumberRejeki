<div>
    <div class="container-fluid mb-5" style="margin-top: 40px">

        {{-- 🔹 Breadcrumb --}}
        <div class="fw-medium text-secondary small mb-2">
            Home <i class="fa-solid fa-chevron-right mx-2 small"></i> Kas Mutasi
        </div>

        {{-- 🔹 Header --}}
        <div class="p-3 bg-white border-0 shadow-sm rounded-3">
            <div class="d-lg-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold text-brown mb-1">💰 Kas Masuk / Keluar</h4>
                    <p class="text-muted mb-0 small">
                        <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                        Catat transaksi kas harian berdasarkan shift pengguna.
                    </p>
                </div>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('laporan.kasmutasi.pdf', [
                        'tanggal_awal' => now()->format('Y-m-d'),
                        'tanggal_akhir' => now()->format('Y-m-d'),
                        'shift' => Auth::user()->shift ?? 1
                    ]) }}" target="_blank" class="btn btn-light border shadow-sm">
                        <i class="fa-regular fa-file-pdf me-2 text-danger"></i> Cetak PDF
                    </a>

                    <a href="{{ route('laporan.kasmutasi.excel', [
                        'tanggal_awal' => now()->format('Y-m-d'),
                        'tanggal_akhir' => now()->format('Y-m-d'),
                        'shift' => Auth::user()->shift ?? 1
                    ]) }}" target="_blank" class="btn btn-light border shadow-sm">
                        <i class="fa-solid fa-chart-column me-2 text-success"></i> Export Excel
                    </a>

                    <button wire:click="openModal" class="btn btn-brown shadow-sm text-white">
                        <i class="fa-solid fa-plus me-2"></i> Tambah Kas
                    </button>
                </div>
            </div>
        </div>

        {{-- 🔹 Alert --}}
        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm rounded-3">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- 🔹 Table --}}
        <div class="card border-0 shadow-sm mt-3 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-brown text-white">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kasMutasiList as $key => $item)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td><span class="badge bg-success">Shift {{ $item->shift }}</span></td>
                                <td
                                    class="{{ $item->jenis == 'masuk' ? 'text-success fw-semibold' : 'text-danger fw-semibold' }}">
                                    {{ ucfirst($item->jenis) }}
                                </td>
                                <td class="text-end fw-semibold">Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Belum ada data kas mutasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 🔹 Modal --}}
        @if($isOpen)
        <div class="custom-modal">
            <div class="custom-modal-dialog animate__animated animate__fadeInDown">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h5 class="modal-title fw-semibold text-white">
                            <i class="bi bi-coin text-warning me-2"></i> Tambah Kas Mutasi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>

                    <div class="custom-modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" class="form-control rounded-3 shadow-sm" wire:model="tanggal">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Shift (otomatis dari user)</label>
                            <input type="text" class="form-control bg-light rounded-3 shadow-sm"
                                value="Shift {{ Auth::user()->shift ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kas</label>
                            <select class="form-select rounded-3 shadow-sm" wire:model="jenis">
                                <option value="masuk">Kas Masuk</option>
                                <option value="keluar">Kas Keluar</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nominal</label>
                            <input type="number" class="form-control rounded-3 shadow-sm" wire:model="nominal"
                                placeholder="Masukkan nominal...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea class="form-control rounded-3 shadow-sm" wire:model="keterangan" rows="2"
                                placeholder="Tulis keterangan..."></textarea>
                        </div>
                    </div>
                    <div class="custom-modal-footer">
                        <button wire:click="store" class="btn btn-success rounded-3 px-4 shadow-sm">
                            💾 Simpan
                        </button>
                        <button wire:click="closeModal" class="btn btn-outline-secondary rounded-3 px-4">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>