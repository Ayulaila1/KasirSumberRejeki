<div class="main-content">
    <!-- Content Area -->
    <div class="content-area">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-cog"></i>
                <h1>Pengaturan</h1>
            </div>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <!-- Settings Tabs -->
        <div class="settings-tabs">
            <button wire:click="changeTab('general')" class="tab-btn {{ $activeTab === 'general' ? 'active' : '' }}"
                data-tab="general">Umum</button>
            <button wire:click="changeTab('users')" class="tab-btn {{ $activeTab === 'users' ? 'active' : '' }}"
                data-tab="users">Pengguna</button>
            <button wire:click="changeTab('products')" class="tab-btn {{ $activeTab === 'products' ? 'active' : '' }}"
                data-tab="products">Produk</button>
            <button wire:click="changeTab('suppliers')" class="tab-btn {{ $activeTab === 'suppliers' ? 'active' : '' }}"
                data-tab="suppliers">Supplier</button>
            <button wire:click="changeTab('backup')" class="tab-btn {{ $activeTab === 'backup' ? 'active' : '' }}"
                data-tab="backup">Backup</button>
        </div>

        <!-- General Settings Tab -->
        <div id="general" class="tab-content {{ $activeTab === 'general' ? 'active' : '' }}">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Pengaturan Umum</h3>
                </div>
                <form wire:submit.prevent="saveStoreSettings">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" wire:model="storeSettings.nama_toko">
                            @error('storeSettings.nama_toko') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Toko</label>
                            <input type="text" class="form-control" wire:model="storeSettings.alamat_toko">
                            @error('storeSettings.alamat_toko') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" wire:model="storeSettings.telepon">
                            @error('storeSettings.telepon') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="storeSettings.email">
                            @error('storeSettings.email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo Toko</label>
                        <input type="file" class="form-control" wire:model="storeSettings.logo">
                        @error('storeSettings.logo') <span class="error">{{ $message }}</span> @enderror
                        @if($logoPreview)
                        <img src="{{ $logoPreview }}" alt="Logo Preview" class="mt-2" style="max-width: 100px;">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>

            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Pengaturan Notifikasi</h3>
                </div>
                <form wire:submit.prevent="saveNotificationSettings">
                    <div class="form-group">
                        <label class="form-label">Notifikasi Stok Minimum</label>
                        <select class="form-select" wire:model="notificationSettings.stok_minimum">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        @error('notificationSettings.stok_minimum') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email untuk Notifikasi</label>
                        <input type="email" class="form-control" wire:model="notificationSettings.email_notifikasi">
                        @error('notificationSettings.email_notifikasi') <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Users Settings Tab -->
        <div id="users" class="tab-content {{ $activeTab === 'users' ? 'active' : '' }}">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Daftar Pengguna</h3>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="deleteUser({{ $user->id }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Tambah Pengguna Baru</h3>
                </div>
                <form wire:submit.prevent="createUser">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" wire:model="newUser.name">
                            @error('newUser.name') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="newUser.email">
                            @error('newUser.email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" wire:model="newUser.password">
                            @error('newUser.password') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" wire:model="newUser.password_confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select class="form-select" wire:model="newUser.role">
                            <option value="admin">Administrator</option>
                            <option value="kasir">Kasir</option>
                            <option value="gudang">Gudang</option>
                        </select>
                        @error('newUser.role') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                </form>
            </div>
        </div>

        <!-- Products Settings Tab -->
        <div id="products" class="tab-content {{ $activeTab === 'products' ? 'active' : '' }}">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Pengaturan Produk</h3>
                </div>
                <form wire:submit.prevent="saveProductSettings">
                    <div class="form-group">
                        <label class="form-label">Satuan Default</label>
                        <select class="form-select" wire:model="productSettings.satuan_default">
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kilogram</option>
                            <option value="gr">Gram</option>
                            <option value="ml">Mililiter</option>
                            <option value="l">Liter</option>
                        </select>
                        @error('productSettings.satuan_default') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok Minimum Default</label>
                        <input type="number" class="form-control" wire:model="productSettings.stok_minimum_default">
                        @error('productSettings.stok_minimum_default') <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Produk</label>
                        <textarea class="form-textarea" wire:model="productSettings.kategori_produk"></textarea>
                        <small class="text-muted">Pisahkan dengan koma untuk menambahkan kategori baru</small>
                        @error('productSettings.kategori_produk') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Suppliers Settings Tab -->
        <div id="suppliers" class="tab-content {{ $activeTab === 'suppliers' ? 'active' : '' }}">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Pengaturan Supplier</h3>
                </div>
                <form wire:submit.prevent="saveSupplierSettings">
                    <div class="form-group">
                        <label class="form-label">Notifikasi Restock</label>
                        <select class="form-select" wire:model="supplierSettings.notifikasi_restock">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        @error('supplierSettings.notifikasi_restock') <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Supplier Default</label>
                        <input type="email" class="form-control" wire:model="supplierSettings.email_supplier_default">
                        @error('supplierSettings.email_supplier_default') <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Backup Settings Tab -->
        <div id="backup" class="tab-content {{ $activeTab === 'backup' ? 'active' : '' }}">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Backup Data</h3>
                </div>
                <div class="form-group">
                    <label class="form-label">Backup Terakhir</label>
                    <p>{{ $backupStatus }}</p>
                </div>
                <div class="form-group">
                    <button wire:click="createBackup" class="btn btn-primary">
                        <i class="fas fa-download"></i>
                        <span>Buat Backup Sekarang</span>
                    </button>
                </div>
            </div>

            <div class="settings-card">
                <div class="settings-card-header">
                    <h3 class="settings-card-title">Restore Data</h3>
                </div>
                <form wire:submit.prevent="restoreBackup">
                    <div class="form-group">
                        <label class="form-label">Pilih File Backup</label>
                        <input type="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-upload"></i>
                        <span>Restore Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Close success message after 3 seconds
        Livewire.on('showMessage', () => {
            setTimeout(() => {
                Livewire.emit('resetMessage');
            }, 3000);
        });
    });
</script>
@endpush