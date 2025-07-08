<div>
    <div class="sidebar {{ $showSidebar && !request()->routeIs('kasir.index') ? 'show' : '' }}" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-mug-hot"></i>
            <h3>Cafe Suki</h3>
        </div>

        <div class="sidebar-section mt-3 px-3 text-muted fw-bold small">
            Menu Dashboard
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section mt-3 px-3 text-muted fw-bold small">
                Data Master
            </div>

            <a href="{{ route('bahan.index') }}"
                class="menu-item {{ request()->routeIs('bahan.index') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i>
                <span>Bahan</span>
            </a>

            <a href="{{ route('supplier.index') }}"
                class="menu-item {{ request()->routeIs('supplier.index') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Supplier</span>
            </a>

            <div class="sidebar-section mt-3 px-3 text-muted fw-bold small">
                Data Pembelian dan Penjualan
            </div>

            <a href="{{ route('produk.index') }}"
                class="menu-item {{ request()->routeIs('produk.index') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('pembelian.index') }}"
                class="menu-item {{ request()->routeIs('pembelian.index') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Pembelian</span>
            </a>

            <a href="{{ route('returtitipan.index') }}"
                class="menu-item {{ request()->routeIs('returtitipan.index') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Retur Titipan</span>
            </a>

            <a href="{{ route('laporan.penjualan') }}"
                class="menu-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Laporan Penjualan</span>
            </a>

            <a href="{{ route('laporan.pendapatan') }}"
                class="menu-item {{ request()->routeIs('laporan.pendapatan') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Laporan Pendapatan</span>
            </a>

            <a href="{{ route('kasir.index') }}"
                class="menu-item {{ request()->routeIs('kasir.index') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <span>Kasir</span>
            </a>

            <<<<<<<<< Temporary merge branch 1 <a href="{{ route('pengguna.index') }}" class="menu-item">
                <i class="fas fa-users-cog{{ request()->routeIs('pengguna.index') ? 'active' : '' }}"></i>
                <span>Pengguna</span>
                </a>
                =========
                <div class="submenu">
                    <a href="#" class="menu-item">Daftar Pengguna</a>
                    <a href="#" class="menu-item">Tambah Pengguna</a>
                </div>
                >>>>>>>>> Temporary merge branch 2

                <a href="{{ route('pengaturan.index') }}" class="menu-item">
                    <i class="fas fa-cog {{ request()->routeIs('pengaturan.index') ? 'active' : '' }}"></i>
                    <span>Pengaturan</span>
                </a>
        </div>
    </div>
    <div class="main-content {{ $showSidebar && !request()->routeIs('kasir.index') ? 'sidebar-open' : '' }}"
        id="mainContent">
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
                        <a href="#" class="dropdown-item {{ request()->routeIs('profil.index') ? 'active' : '' }}">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="{{ route('pengaturan.index') }}" class="dropdown-item">
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