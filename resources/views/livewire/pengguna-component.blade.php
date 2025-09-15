<div>
    <!-- Daftar Pengguna -->
    <div id="user-list">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                <span>Daftar Pengguna</span>
            </h1>
            <button class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-warning" wire:click="editUser({{ $user->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $user->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>

    <!-- Form Tambah/Edit Pengguna (Modal) -->
    <div class="modal @if($showModal) show @endif" id="user-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                <button class="modal-close" wire:click="$set('showModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="saveUser">
                    <input type="hidden" wire:model="userId">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" class="form-control" wire:model="name" required>
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" wire:model="email" required>
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="form-control" wire:model="password" @if(!$isEdit)
                            required @endif>
                        @error('password') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirm-password" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="confirm-password" class="form-control"
                            wire:model="password_confirmation" @if(!$isEdit) required @endif>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                <button class="btn btn-primary" wire:click="saveUser">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Konfirmasi Hapus (Modal) -->
    <div class="modal @if($showDeleteModal) show @endif" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button class="modal-close" wire:click="$set('showDeleteModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Batal</button>
                <button class="btn btn-danger" wire:click="deleteUser">Hapus</button>
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