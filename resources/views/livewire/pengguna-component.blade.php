<div>
    <div id="user-list">

        <div class="page-header">
            <h1 class="page-title new-style">
                <i class="fas fa-users"></i>
                <span>Daftar Pengguna</span>
            </h1>
            {{-- <button class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengguna</span>
            </button> --}}
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('message') }}
        </div>
        @endif

        <div class="card table-card">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table elegant-table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-nama">Nama</th>
                                <th>Email</th>
                                <th class="col-date">Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="col-id">{{ $user->id }}</td>
                                <td class="col-nama">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="col-date">{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal @if($showModal) show @endif" id="user-modal">
        <div class="modal-dialog">
            <div class="custom_modal">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                    <button class="modal-close" wire:click="$set('showModal', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveUser">
                        <input type="hidden" wire:model="userId">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control" wire:model.defer="name" required>
                            @error('name') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" wire:model.defer="email" required>
                            @error('email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password @if($isEdit) <small
                                    class="text-muted">(Kosongkan jika tidak ingin diubah)</small> @endif</label>
                            <input type="password" id="password" class="form-control" wire:model.defer="password"
                                @if(!$isEdit) required @endif> @error('password') <span class="error">{{ $message
                                }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm-password" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="confirm-password" class="form-control"
                                wire:model.defer="password_confirmation" @if(!$isEdit) required @endif>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                    <button class="btn btn-primary" wire:click="saveUser">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal @if($showDeleteModal) show @endif" id="confirm-modal">
        <div class="modal-dialog modal-sm">
            <div class="custom_modal">
                <div class="modal-header bg-danger text-white">
                    <h3 class="modal-title">Konfirmasi Hapus</h3>
                    <button class="modal-close" wire:click="$set('showDeleteModal', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna ini? Aksi ini **tidak bisa** dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Batal</button>
                    <button class="btn btn-danger" wire:click="deleteUser">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-dialog {
        background: white;
        border-radius: 5px;
        width: 500px;
        max-width: 90%;
    }

    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .modal-body {
        padding: 15px;
    }

    .modal-footer {
        padding: 15px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .error {
        color: red;
        font-size: 0.8rem;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    /* Kustomisasi Tabel */

    .card.table-card {
        padding: 0;
        border: 1px solid #ddd;
        /* Tambah border tipis pada card */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        /* Shadow lebih halus */
    }

    .table.elegant-table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    /* Gaya Header Tabel yang Elegant (Monokrom/Hitam Putih) */
    .table.elegant-table thead th {
        background-color: #f5f5f5;
        /* Abu-abu sangat terang */
        color: #333;
        /* Teks hitam */
        font-weight: 600;
        border-bottom: 2px solid #ddd;
        /* Garis bawah yang lebih tegas */
        position: sticky;
        top: 0;
        z-index: 10;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    /* Hilangkan border-radius di header agar tampilannya 'flat' */
    .table thead tr:first-child th {
        border-radius: 0 !important;
    }

    /* Baris Data */
    .table.elegant-table tbody td {
        padding: 15px 15px;
        /* Padding lebih besar */
        color: #444;
        border-bottom: 1px solid #eee;
        /* Garis pemisah baris halus */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #fafafa;
        /* Baris ganjil lebih kalem */
    }

    .table-hover tbody tr:hover {
        background-color: #f0f0f0;
        /* Hover yang lembut */
    }

    /* Aturan untuk lebar kolom agar lebih rapi */
    .table .col-id {
        width: 70px;
        text-align: center;
        font-weight: 600;
        color: #777;
    }

    .table .col-date {
        width: 150px;
        color: #777;
        font-size: 0.95rem;
    }

    .table .col-nama {
        font-weight: 600;
        color: #333;
    }

    /* KOLOM AKSI DIHAPUS, JADI HAPUS SEMUA STYLE TERKAIT */
    .table .col-action,
    .table tbody td.col-action,
    .action-buttons,
    .btn-icon {
        display: none !important;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 15px;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #eee;
    }

    /* Kustomisasi Modal untuk Konfirmasi Hapus */
    .modal-dialog.modal-sm {
        max-width: 350px;
        /* Modal konfirmasi lebih kecil */
    }

    .modal-header.bg-danger {
        background-color: var(--danger);
        /* Gunakan variabel danger */
        color: white;
    }

    /* Tambahkan style untuk petunjuk di form */
    .text-muted {
        color: #888;
        font-weight: normal;
        font-size: 0.9em;
    }

    /* Kustomisasi Header Halaman */
    .page-header {
        /* Hapus space-between karena tombol Tambah Pengguna dihapus */
        display: flex;
        justify-content: flex-start;
        /* Mengatur judul tetap di kiri */
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    /* FONT DAFTAR PENGGUNA ELEGANT & HITAM */
    .page-title.new-style {
        color: #333;
        /* Warna hitam elegan */
        font-weight: 700;
        /* Lebih tebal */
        font-size: 1.8rem;
        /* Lebih besar */
        /* Hapus warna secondary untuk ikon agar mengikuti warna teks */
    }

    .page-title.new-style i {
        color: #555;
        /* Ikon warna abu-abu gelap agar tidak terlalu kontras */
        font-size: 1.4rem;
        margin-right: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                Livewire.dispatch('close-modal');
            }
        });

        // Handle escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                Livewire.dispatch('close-modal');
            }
        });
    });
</script>
@endpush