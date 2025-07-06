<div class="main-content">
    <!-- Content Area -->
    <div class="content-area">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-user"></i>
                <h1>Profil Pengguna</h1>
            </div>
            @if(!$editMode)
            <button wire:click="toggleEditMode" class="btn btn-primary" id="editProfileBtn">
                <i class="fas fa-edit"></i>
                <span>Edit Profil</span>
            </button>
            @endif
        </div>

        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                <p class="profile-role">{{ ucfirst(Auth::user()->role) }}</p>
            </div>
            <div class="profile-body">
                <!-- View Mode -->
                <div id="viewMode" style="{{ $editMode ? 'display: none;' : 'display: block;' }}">
                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            <span>Informasi Dasar</span>
                        </h3>
                        <div class="profile-info">
                            <div class="info-item">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Role</div>
                                <div class="info-value">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Bergabung</div>
                                <div class="info-value">{{ Auth::user()->created_at->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-section">
                        <h3 class="section-title">
                            <i class="fas fa-lock"></i>
                            <span>Keamanan Akun</span>
                        </h3>
                        <div class="profile-info">
                            <div class="info-item">
                                <div class="info-label">Status Akun</div>
                                <div class="info-value">Aktif</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Login</div>
                                <div class="info-value">{{ now()->format('d F Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div id="editMode" style="{{ $editMode ? 'display: block;' : 'display: none;' }}">
                    <form wire:submit.prevent="saveProfile">
                        <div class="profile-section">
                            <h3 class="section-title">
                                <i class="fas fa-edit"></i>
                                <span>Edit Profil</span>
                            </h3>
                            <div class="form-container">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" id="name" class="form-control" wire:model="name">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control" wire:model="email">
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="currentPassword" class="form-label">Password Saat Ini</label>
                                        <input type="password" id="currentPassword" class="form-control"
                                            wire:model="current_password">
                                        @error('current_password') <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword" class="form-label">Password Baru</label>
                                        <input type="password" id="newPassword" class="form-control"
                                            wire:model="new_password">
                                        @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" id="confirmPassword" class="form-control"
                                        wire:model="confirm_password">
                                    @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Perubahan</span>
                                    </button>
                                    <button type="button" class="btn btn-danger" wire:click="toggleEditMode">
                                        <i class="fas fa-times"></i>
                                        <span>Batal</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Sidebar
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Toggle User Dropdown
    document.getElementById('userProfile').addEventListener('click', function() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    });

    // Close dropdown when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.matches('#userProfile') && !event.target.closest('#userProfile')) {
            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight current page in sidebar
        const currentPage = window.location.pathname.split('/').pop() || 'dashboard.html';
        const menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && href.includes(currentPage)) {
                item.classList.add('active');

                // If it's in a submenu, open the parent menu
                if (item.closest('.submenu')) {
                    const parent = item.closest('.has-submenu');
                    if (parent) {
                        parent.classList.add('active');
                        parent.nextElementSibling.style.display = 'block';
                    }
                }
            }
        });

        // Toggle submenus
        const hasSubmenuItems = document.querySelectorAll('.has-submenu');
        hasSubmenuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target === this) {
                    e.preventDefault();
                    this.classList.toggle('active');
                    const submenu = this.nextElementSibling;
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                }
            });
        });
    });
</script>
@endpush