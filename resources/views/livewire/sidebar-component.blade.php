<div>
    <div class="sidebar {{ $showSidebar ? 'show' : '' }}" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-mug-hot"></i>
            <h3>Cafe Suki</h3>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
                class="menu-item {{ $currentPage === 'dashboard' ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('produk.index') }}"
                class="menu-item {{ request()->routeIs('produk.index') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Produk</span>
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Supplier</span>
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Pembelian</span>
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-exchange-alt"></i>
                <span>Retur Titipan</span>
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Laporan</span>
            </a>

            <a href="#" class="menu-item has-submenu">
                <i class="fas fa-users-cog"></i>
                <span>Pengguna</span>
            </a>
            <div class="submenu">
                <a href="#" class="menu-item">Daftar Pengguna</a>
                <a href="#" class="menu-item">Tambah Pengguna</a>
            </div>

            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </div>
    <div class="main-content {{ $showSidebar ? 'sidebar-open' : '' }}" id="mainContent">
        <!-- Top Navigation -->
        <div class="top-nav d-flex align-items-center justify-content-end">
            <button class="toggle-sidebar" wire:click="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="user-profile">
                <div class="user-dropdown" wire:click="toggleDropdown">
                    <div class="user-avatar">A</div>
                    <span class="user-name">Admin</span>
                    <i class="fas fa-chevron-down"></i>

                    <div class="dropdown-menu {{ $showDropdown ? 'show' : '' }}" id="dropdownMenu">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i> Pengaturan
                        </a>
                        <a href="#" class="dropdown-item" wire:click="logout">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>