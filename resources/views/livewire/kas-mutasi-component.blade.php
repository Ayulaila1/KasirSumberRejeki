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
                <button wire:click="exportToPdf" class="btn btn-light border shadow-sm">
                    <i class="fa-regular fa-file-pdf me-2 text-danger"></i> Cetak PDF
                </button>

                <button wire:click="exportToExcel" class="btn btn-light border shadow-sm">
                    <i class="fa-solid fa-chart-column me-2 text-success"></i> Export Excel
                </button>

                <button wire:click="openModal" class="btn btn-brown shadow-sm text-white">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Kas
                </button>
            </div>
        </div>
    </div>

    {{-- 🔹 Filter dan Search --}}
    <div class="mt-3 p-3 bg-white border-0 shadow-sm rounded-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" class="form-control rounded-3 shadow-sm" placeholder="Cari keterangan..."
                    wire:model.live.debounce.300ms="search">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control rounded-3 shadow-sm" wire:model.live="tglstart">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control rounded-3 shadow-sm" wire:model.live="tglend">
            </div>
            <div class="col-md-3 text-end">
                <select class="form-select w-auto d-inline rounded-3 shadow-sm" wire:model.live="perPage">
                    <option value="10">10 / halaman</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="10000">Semua</option>
                </select>
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
                    <thead class="table-brown text-white text-center">
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
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
                            <td>{{ $kasMutasiList->firstItem() + $key }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button wire:click="editKasMutasi({{ $item->id }})"
                                        class="btn btn-sm btn-warning text-dark">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button onclick="hapusKas({{ $item->id }})" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>{{ $item->tanggal }}</td>
                            <td>
                                <span
                                    class="badge {{ $item->shift == 1 ? 'bg-primary' : ($item->shift == 2 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    Shift {{ $item->shift }}
                                </span>
                            </td>
                            <td
                                class="{{ $item->jenis == 'masuk' ? 'text-success fw-semibold' : 'text-danger fw-semibold' }}">
                                {{ ucfirst($item->jenis) }}
                            </td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-start">{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada data kas mutasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔹 Pagination --}}
    @if ($kasMutasiList->count())
    <div class="d-flex justify-content-between align-items-center mt-3 small text-muted">
        <div>
            Menampilkan {{ $kasMutasiList->firstItem() }} - {{ $kasMutasiList->lastItem() }}
            dari {{ $kasMutasiList->total() }} data
        </div>
        <div>
            {{ $kasMutasiList->links() }}
        </div>
    </div>
    @endif

    {{-- 🔹 Modal Tambah/Edit Kas --}}
    @if($isOpen)
    <div class="custom-modal">
        <div class="custom-modal-dialog animate__animated animate__fadeInDown">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h5 class="modal-title fw-semibold text-white">
                        <i class="bi bi-coin text-warning me-2"></i>
                        {{ $isEdit ? 'Edit Kas Mutasi' : 'Tambah Kas Mutasi' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>

                <div class="custom-modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control rounded-3 shadow-sm" wire:model="tanggal">
                        @error('tanggal') <span class="text-danger small">{{ $message }}</span> @enderror
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
                        @error('jenis') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal</label>
                        <input type="number" class="form-control rounded-3 shadow-sm" wire:model="nominal"
                            placeholder="Masukkan nominal...">
                        @error('nominal') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control rounded-3 shadow-sm" wire:model="keterangan" rows="2"
                            placeholder="Tulis keterangan..."></textarea>
                    </div>
                </div>

                <div class="custom-modal-footer">
                    @if($isEdit)
                    <button wire:click="updateKasMutasi" class="btn btn-primary rounded-3 px-4 shadow-sm">
                        🔄 Update
                    </button>
                    @else
                    <button wire:click="store" class="btn btn-success rounded-3 px-4 shadow-sm">
                        💾 Simpan
                    </button>
                    @endif
                    <button wire:click="closeModal" class="btn btn-outline-secondary rounded-3 px-4">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- 🔹 SweetAlert Konfirmasi Hapus --}}
<script>
    function hapusKas(id) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteKasMutasi', { id: id });
            }
        });
    }
</script>