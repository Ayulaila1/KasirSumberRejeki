<div class="container-fluid mb-5" style="margin-top: 40px">
    {{-- 🧭 Breadcrumb --}}
    <div class="fw-medium text-secondary" style="font-size:15px; margin-bottom:-5px;">
        Home <i class="fa-solid fa-chevron-right mx-2" style="font-size: 12px"></i> Pengeluaran
    </div>

    {{-- 🧾 Header --}}
    <div class="container-fluid px-0 p-3 rounded-top">
        <div class="d-lg-flex justify-content-between align-items-center mb-lg-1">
            <div class="mb-3 mb-lg-0">
                <p class="p-0 m-0 h3 fw-bold">Pengeluaran</p>
                <div class="mt-2 text-muted">
                    <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                    Menu ini digunakan untuk mencatat dan mengelola data pengeluaran harian
                    seperti <b>gas, air, es batu, dan bahan operasional lainnya</b>.
                </div>
            </div>
        </div>
    </div>

    {{-- 🔄 Loader --}}
    <div wire:loading wire:target="exportToPdf, search, tglstart, tglend" class="loading-spinner">
        <div class="loader"></div>
    </div>

    {{-- 🔍 Toolbar --}}
    <div class="py-2 mb-2 d-lg-flex justify-content-between align-items-center">
        <div>
            <input type="text" class="form-control mb-2 mb-lg-0" placeholder="Cari bahan..."
                wire:model.live.debounce.300ms="search">
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn bg-danger text-white d-flex align-items-center" wire:click="exportToPdf">
                <i class="fa-regular fa-file-pdf me-1"></i> PDF
            </button>
            <button wire:click="tambahPengeluaran" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-lg me-1"></i> Tambah
            </button>
        </div>
    </div>

    {{-- 📋 Tabel Data --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="width:100%;white-space:nowrap">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Nama Bahan</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Shift</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluarans as $key => $data)
                    <tr>
                        <td class="text-center">{{ $pengeluarans->firstItem() + $key }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-dark"
                                wire:click="editPengeluaran('{{ $data->idpengeluaran }}')">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger"
                                wire:click="deleteConfirmationPengeluaran('{{ $data->idpengeluaran }}')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                        <td>{{ $data->bahan->nama ?? '-' }}</td>
                        <td class="text-center">{{ $data->tanggal }}</td>
                        <td class="text-end">{{ number_format($data->jumlah, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($data->harga, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($data->total, 0, ',', '.') }}</td>
                        <td>{{ $data->keterangan ?? '-' }}</td>
                        <td class="text-center">{{ $data->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $data->shift ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Belum ada data pengeluaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 📄 Pagination --}}
    <div class="row mt-3">
        <div class="col-lg-6 d-flex justify-content-between align-items-center">
            <small>
                Menampilkan {{ $pengeluarans->firstItem() }} - {{ $pengeluarans->lastItem() }}
                dari {{ $pengeluarans->total() }} data
            </small>
            <div>
                <select class="form-select form-select-sm" wire:model.live='perPage'>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="10000">Semua</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 d-flex justify-content-end">
            {{ $pengeluarans->links() }}
        </div>
    </div>

    {{-- 🧾 Modal Tambah/Edit Pengeluaran --}}
    @if($isOpen || $isEdit)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Bahan</label>
                        <div class="d-flex gap-1">
                            <input class="form-control" wire:model="bahanName" readonly>
                            <button type="button" class="btn btn-primary"
                                wire:click="$dispatch('buka-modal-lov-bahan')">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                        @error('bahan_idbahan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tanggal</label>
                        <input type="date" class="form-control" wire:model="tanggal">
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Jumlah</label>
                        <input type="number" class="form-control" wire:model.live="jumlah">
                        @if ($bahan_idbahan)
                        <small class="text-info">Stok tersedia: {{ number_format($stokTersedia, 0, ',', '.') }}</small>
                        @endif
                        @error('jumlah') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Harga</label>
                        <input type="text" class="form-control" wire:model="harga" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Total</label>
                        <input type="text" class="form-control" wire:model="total" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Keterangan (Opsional)</label>
                        <textarea class="form-control" wire:model="keterangan" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="close">Batal</button>
                        <button class="btn btn-success"
                            wire:click="{{ $isEdit ? 'updatePengeluaran' : 'storePengeluaran' }}">
                            {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 🔍 Modal LOV Bahan --}}
    @livewire('bahan-lov')
</div>

{{-- ===================== SCRIPT ===================== --}}
<script>
    // ✅ SweetAlert success
    window.addEventListener('pengeluaran-disimpan', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: event.detail.pesan,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    });

    // ❌ SweetAlert error
    window.addEventListener('pengeluaran-error', event => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: event.detail.pesan,
        });
    });

    // ⚠️ Konfirmasi hapus
    window.addEventListener('konfirmasi-hapus', event => {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data ini tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                Livewire.dispatch('hapusPengeluaran');
            }
        });
    });
</script>