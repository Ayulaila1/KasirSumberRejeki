<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cafe Suki</title>
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h3 {
            font-weight: 600;
        }

        .sidebar-header i {
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
            overflow-y: auto;
            max-height: calc(100vh - 70px);
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .menu-item.active {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .submenu {
            padding-left: 40px;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu .menu-item {
            padding: 10px 15px;
            font-size: 14px;
        }

        .menu-item.has-submenu::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            transition: all 0.3s;
        }

        .menu-item.has-submenu.active::after {
            transform: rotate(180deg);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Main Content */
        .main-content {
            margin-left: 0;
            transition: all 0.3s;
        }

        .main-content.sidebar-open {
            margin-left: 250px;
        }

        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--primary);
            cursor: pointer;
            display: block;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-name {
            font-weight: 500;
        }

        .user-dropdown {
            position: relative;
            cursor: pointer;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.3s;
        }

        .dropdown-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
        }

        /* Content Area */
        .content-area {
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: var(--secondary);
        }

        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .card-icon.primary {
            background-color: var(--primary);
        }

        .card-icon.success {
            background-color: var(--success);
        }

        .card-icon.warning {
            background-color: var(--warning);
        }

        .card-icon.danger {
            background-color: var(--danger);
        }

        .card-title {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .card-footer {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-footer.positive {
            color: var(--success);
        }

        .card-footer.negative {
            color: var(--danger);
        }

        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--primary);
            color: white;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: rgba(122, 75, 71, 0.05);
        }

        td {
            padding: 12px 15px;
            color: #555;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-badge.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-badge.danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: white;
        }

        .form-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        /* Charts */
        .chart-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            font-weight: 500;
            color: var(--dark);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.3s;
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-weight: 600;
            color: var(--primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 250px;
            }

            .toggle-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .chart-actions {
                width: 100%;
            }

            .form-select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay {{ $showSidebar ? 'show' : '' }}" wire:click="toggleSidebar"></div>

    <!-- Sidebar -->
    <div class="sidebar {{ $showSidebar ? 'show' : '' }}" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-mug-hot"></i>
            <h3>Cafe Suki</h3>
        </div>

        <div class="sidebar-menu">
            <a href="#" class="menu-item {{ $currentPage === 'dashboard' ? 'active' : '' }}"
                wire:click.prevent="navigateTo('dashboard')">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="menu-item {{ $currentPage === 'products' ? 'active' : '' }}"
                wire:click.prevent="navigateTo('products')">
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

    <!-- Main Content -->
    <div class="main-content {{ $showSidebar ? 'sidebar-open' : '' }}" id="mainContent">
        <!-- Top Navigation -->
        <div class="top-nav">
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

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard Page -->
            @if($currentPage === 'dashboard')
            <div id="dashboardPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </h1>
                    <div>
                        <button class="btn btn-primary" id="exportReport" wire:click="exportToExcel">
                            <i class="fas fa-download"></i> Export Laporan
                        </button>
                    </div>
                </div>

                <!-- Dashboard Cards -->
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Total Pendapatan</div>
                                <div class="card-value">Rp 12.450.000</div>
                                <div class="card-footer positive">
                                    <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                                </div>
                            </div>
                            <div class="card-icon primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Total Produk</div>
                                <div class="card-value">128</div>
                                <div class="card-footer positive">
                                    <i class="fas fa-arrow-up"></i> 5 produk baru
                                </div>
                            </div>
                            <div class="card-icon success">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Stok Minimum</div>
                                <div class="card-value">12</div>
                                <div class="card-footer negative">
                                    <i class="fas fa-exclamation-circle"></i> Perlu restock
                                </div>
                            </div>
                            <div class="card-icon warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Total Supplier</div>
                                <div class="card-value">8</div>
                                <div class="card-footer">
                                    <i class="fas fa-info-circle"></i> 2 supplier aktif
                                </div>
                            </div>
                            <div class="card-icon danger">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Pendapatan Bulanan</h3>
                            <div class="chart-actions">
                                <select class="form-select" id="revenueYear" wire:model="revenueYear"
                                    style="width: 120px;">
                                    <option value="2025">Tahun 2025</option>
                                    <option value="2024">Tahun 2024</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Produk Terlaris</h3>
                            <div class="chart-actions">
                                <select class="form-select" id="bestSellerMonth" wire:model="bestSellerMonth"
                                    style="width: 120px;">
                                    <option value="current">Bulan Ini</option>
                                    <option value="last">Bulan Lalu</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="bestSellerChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="page-header" style="margin-top: 30px;">
                    <h2 class="page-title">
                        <i class="fas fa-exchange-alt"></i> Transaksi Terakhir
                    </h2>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TRX-20250628-001</td>
                                    <td>28 Jun 2025</td>
                                    <td>Pelanggan 1</td>
                                    <td>Rp 125.000</td>
                                    <td><span class="status-badge success">Selesai</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewTransaction('TRX-20250628-001')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TRX-20250628-002</td>
                                    <td>28 Jun 2025</td>
                                    <td>Pelanggan 2</td>
                                    <td>Rp 85.000</td>
                                    <td><span class="status-badge success">Selesai</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewTransaction('TRX-20250628-002')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TRX-20250627-015</td>
                                    <td>27 Jun 2025</td>
                                    <td>Pelanggan 3</td>
                                    <td>Rp 210.000</td>
                                    <td><span class="status-badge success">Selesai</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewTransaction('TRX-20250627-015')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TRX-20250627-014</td>
                                    <td>27 Jun 2025</td>
                                    <td>Pelanggan 4</td>
                                    <td>Rp 75.000</td>
                                    <td><span class="status-badge success">Selesai</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewTransaction('TRX-20250627-014')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TRX-20250626-010</td>
                                    <td>26 Jun 2025</td>
                                    <td>Pelanggan 5</td>
                                    <td>Rp 150.000</td>
                                    <td><span class="status-badge success">Selesai</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewTransaction('TRX-20250626-010')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Products Page -->
            @if($currentPage === 'products')
            <div id="productsPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-box"></i> Daftar Produk
                    </h1>
                    <div>
                        <button class="btn btn-primary" id="addProductBtn" wire:click="navigateTo('addProduct')">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Produk</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Supplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PRD-001</td>
                                    <td>Cappuccino</td>
                                    <td>Sachet</td>
                                    <td>Rp 25.000</td>
                                    <td>45</td>
                                    <td>Supplier A</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PRD-002</td>
                                    <td>Teh Tarik</td>
                                    <td>Sachet</td>
                                    <td>Rp 15.000</td>
                                    <td>32</td>
                                    <td>Supplier A</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PRD-003</td>
                                    <td>Nasi Goreng Spesial</td>
                                    <td>Racikan</td>
                                    <td>Rp 30.000</td>
                                    <td>12</td>
                                    <td>Supplier B</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PRD-004</td>
                                    <td>Kentang Goreng</td>
                                    <td>Bahan Baku</td>
                                    <td>Rp 25.000</td>
                                    <td>8</td>
                                    <td>Supplier C</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PRD-005</td>
                                    <td>Red Velvet Cake</td>
                                    <td>Titipan</td>
                                    <td>Rp 36.000</td>
                                    <td>15</td>
                                    <td>Supplier D</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Add Product Form -->
            @if($currentPage === 'addProduct')
            <div id="addProductPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-plus-circle"></i> Tambah Produk
                    </h1>
                    <div>
                        <button class="btn btn-secondary" id="backToProducts" wire:click="navigateTo('products')">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                </div>

                <div class="form-container">
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama produk">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Jenis Produk</label>
                                <select class="form-select">
                                    <option value="">Pilih Jenis Produk</option>
                                    <option value="sachet">Sachet</option>
                                    <option value="racikan">Racikan</option>
                                    <option value="bahan_baku">Bahan Baku</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" class="form-control" placeholder="Masukkan harga jual">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" placeholder="Contoh: pcs, gram, ml">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" class="form-control" placeholder="Masukkan stok awal">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Stok Minimum</label>
                                <input type="number" class="form-control" placeholder="Masukkan stok minimum">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Supplier</label>
                            <select class="form-select">
                                <option value="">Pilih Supplier</option>
                                <option value="1">Supplier A</option>
                                <option value="2">Supplier B</option>
                                <option value="3">Supplier C</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="isTitipan">
                                <label class="form-check-label" for="isTitipan">Produk Titipan</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi Produk</label>
                            <textarea class="form-textarea" placeholder="Masukkan deskripsi produk"></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal {{ $showTransactionModal ? 'show' : '' }}" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Transaksi {{ $selectedTransaction['id'] ?? '' }}</h3>
                <button class="modal-close" wire:click="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                @if($selectedTransaction)
                <div class="transaction-info">
                    <div class="info-row">
                        <span class="info-label">ID Transaksi:</span>
                        <span class="info-value">{{ $selectedTransaction['id'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value">{{ $selectedTransaction['date'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pelanggan:</span>
                        <span class="info-value">{{ $selectedTransaction['customer'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="status-badge success">{{ $selectedTransaction['status']
                                }}</span></span>
                    </div>
                </div>

                <div class="transaction-items" style="margin-top: 20px;">
                    <h4>Item Pembelian</h4>
                    <table style="width: 100%; margin-top: 10px;">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="transactionItems">
                            @foreach($selectedTransaction['items'] as $item)
                            <tr>
                                <td>{{ $item['product'] }}</td>
                                <td>{{ $item['price'] }}</td>
                                <td>{{ $item['qty'] }}</td>
                                <td>{{ $item['subtotal'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 500;">Total:</td>
                                <td style="font-weight: 500;">{{ $selectedTransaction['total'] }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="window.print()">Print</button>
                <button class="btn btn-primary" wire:click="closeModal">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize charts when Livewire is loaded
        document.addEventListener('livewire:load', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @this.getRevenueData(),
                        backgroundColor: 'rgba(122, 75, 71, 0.1)',
                        borderColor: 'rgba(122, 75, 71, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    }
                }
            });

            // Best Seller Chart
            const bestSellerCtx = document.getElementById('bestSellerChart').getContext('2d');
            const bestSellerChart = new Chart(bestSellerCtx, {
                type: 'bar',
                data: {
                    labels: ['Cappuccino', 'Teh Tarik', 'Nasi Goreng', 'Kentang Goreng', 'Red Velvet'],
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: @this.getBestSellerData(),
                        backgroundColor: [
                            'rgba(122, 75, 71, 0.7)',
                            'rgba(255, 190, 94, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(220, 53, 69, 0.7)',
                            'rgba(23, 162, 184, 0.7)'
                        ],
                        borderColor: [
                            'rgba(122, 75, 71, 1)',
                            'rgba(255, 190, 94, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)',
                            'rgba(23, 162, 184, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Listen for Livewire events to update charts
            Livewire.on('revenueYearUpdated', () => {
                revenueChart.data.datasets[0].data = @this.getRevenueData();
                revenueChart.update();
            });

            Livewire.on('bestSellerMonthUpdated', () => {
                bestSellerChart.data.datasets[0].data = @this.getBestSellerData();
                bestSellerChart.update();
            });
        });

        // Export to Excel function
        function exportToExcel() {
            // Create a workbook
            const wb = XLSX.utils.book_new();

            // Create a worksheet with your data
            const wsData = [
                ["ID Transaksi", "Tanggal", "Pelanggan", "Total", "Status"],
                ["TRX-20250628-001", "28 Jun 2025", "Pelanggan 1", "Rp 125.000", "Selesai"],
                ["TRX-20250628-002", "28 Jun 2025", "Pelanggan 2", "Rp 85.000", "Selesai"],
                ["TRX-20250627-015", "27 Jun 2025", "Pelanggan 3", "Rp 210.000", "Selesai"],
                ["TRX-20250627-014", "27 Jun 2025", "Pelanggan 4", "Rp 75.000", "Selesai"],
                ["TRX-20250626-010", "26 Jun 2025", "Pelanggan 5", "Rp 150.000", "Selesai"]
            ];

            const ws = XLSX.utils.aoa_to_sheet(wsData);

            // Add the worksheet to the workbook
            XLSX.utils.book_append_sheet(wb, ws, "Laporan Transaksi");

            // Generate the Excel file and download it
            XLSX.writeFile(wb, "Laporan_Transaksi_Cafe_Suki.xlsx");
        }
    </script>
    @livewireScripts
</body>

</html>