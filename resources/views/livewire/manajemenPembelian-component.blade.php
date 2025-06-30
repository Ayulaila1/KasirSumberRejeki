<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembelian - Cafe Suki</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            position: relative;
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

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
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
            height: calc(100vh - 70px);
            overflow-y: auto;
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

        /* Main Content */
        .main-content {
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            z-index: 100;
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

        /* Content Area */
        .content-area {
            padding: 20px;
            flex: 1;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            gap: 15px;
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
            white-space: nowrap;
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

        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-inactive {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .btn-edit {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        .btn-edit:hover {
            background-color: rgba(23, 162, 184, 0.2);
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
        }

        /* Form Styles */
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
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.2);
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }

        /* Modal Styles */
        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }

        .modal-title {
            color: var(--primary);
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-info {
            color: #666;
            font-size: 14px;
        }

        .pagination-buttons {
            display: flex;
            gap: 5px;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
        }

        /* Responsive Styles */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 250px;
            }

            .toggle-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .card-value {
                font-size: 20px;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .content-area {
                padding: 15px;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            th,
            td {
                padding: 10px 8px;
                font-size: 13px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .btn-sm {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-store"></i>
            <h3>Cafe Suki</h3>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.html" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="products.html" class="menu-item">
                <i class="fas fa-box-open"></i>
                <span>Produk</span>
            </a>
            <a href="categories.html" class="menu-item">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>
            <a href="suppliers.html" class="menu-item">
                <i class="fas fa-truck"></i>
                <span>Supplier</span>
            </a>
            <a href="purchases.html" class="menu-item active">
                <i class="fas fa-shopping-cart"></i>
                <span>Pembelian</span>
            </a>
            <a href="sales.html" class="menu-item">
                <i class="fas fa-cash-register"></i>
                <span>Penjualan</span>
            </a>
            <a href="reports.html" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Laporan</span>
            </a>
            <a href="settings.html" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-profile">
                <div class="user-avatar">A</div>
                <div class="user-name">Admin</div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Manajemen Pembelian</h2>
                </div>
                <button class="btn btn-primary" id="addPurchaseBtn">
                    <i class="fas fa-plus"></i> Tambah Pembelian
                </button>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Total Pembelian Bulan Ini</div>
                            <div class="card-value">Rp 5.250.000</div>
                        </div>
                        <div class="card-icon primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="card-footer positive">
                        <i class="fas fa-arrow-up"></i> 15% dari bulan lalu
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Jumlah Transaksi</div>
                            <div class="card-value">24</div>
                        </div>
                        <div class="card-icon success">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                    <div class="card-footer positive">
                        <i class="fas fa-arrow-up"></i> 5 transaksi baru
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Supplier Aktif</div>
                            <div class="card-value">8</div>
                        </div>
                        <div class="card-icon warning">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="card-footer neutral">
                        <i class="fas fa-circle"></i> 2 supplier baru
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Produk Dibeli</div>
                            <div class="card-value">128</div>
                        </div>
                        <div class="card-icon danger">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div class="card-footer negative">
                        <i class="fas fa-exclamation-circle"></i> 12 perlu restock
                    </div>
                </div>
            </div>

            <!-- Purchase Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Pembelian</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Kasir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PBL-20230601-001</td>
                                <td>01 Jun 2025</td>
                                <td>PT Sumber Jaya</td>
                                <td>Rp 1.250.000</td>
                                <td>Admin</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-edit view-detail-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PBL-20230605-002</td>
                                <td>05 Jun 2025</td>
                                <td>CV Barokah Makmur</td>
                                <td>Rp 850.000</td>
                                <td>Admin</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-edit view-detail-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PBL-20230610-003</td>
                                <td>10 Jun 2025</td>
                                <td>UD Sejahtera</td>
                                <td>Rp 1.750.000</td>
                                <td>Admin</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-edit view-detail-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PBL-20230615-004</td>
                                <td>15 Jun 2025</td>
                                <td>Toko Abadi Jaya</td>
                                <td>Rp 950.000</td>
                                <td>Admin</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-edit view-detail-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PBL-20230620-005</td>
                                <td>20 Jun 2025</td>
                                <td>PT Sumber Jaya</td>
                                <td>Rp 450.000</td>
                                <td>Admin</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-edit view-detail-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan 1 sampai 5 dari 24 entri
                </div>
                <div class="pagination-buttons">
                    <button class="btn btn-sm btn-secondary">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-sm btn-primary">1</button>
                    <button class="btn btn-sm btn-secondary">2</button>
                    <button class="btn btn-sm btn-secondary">3</button>
                    <button class="btn btn-sm btn-secondary">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; 2025 Cafe Suki - Sistem Manajemen Kafe
        </div>
    </div>

    <!-- Add Purchase Modal -->
    <div class="modal fade" id="addPurchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembelian Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Pembelian</label>
                                <input type="text" class="form-control"
                                    value="PBL-<?php echo date('Ymd').'-'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); ?>"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Supplier</label>
                            <select class="form-control">
                                <option value="">Pilih Supplier</option>
                                <option value="1">PT Sumber Jaya</option>
                                <option value="2">CV Barokah Makmur</option>
                                <option value="3">UD Sejahtera</option>
                                <option value="4">Toko Abadi Jaya</option>
                            </select>
                        </div>

                        <h5 style="margin: 20px 0 10px; color: var(--primary);">Daftar Produk</h5>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th width="100px">Harga</th>
                                        <th width="100px">Jumlah</th>
                                        <th width="120px">Subtotal</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseItems">
                                    <tr>
                                        <td>
                                            <select class="form-control">
                                                <option value="">Pilih Produk</option>
                                                <option value="1">Cappuccino</option>
                                                <option value="2">Teh Tarik</option>
                                                <option value="3">Kopi Susu</option>
                                                <option value="4">Nasi Goreng Spesial</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="Harga">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="Jumlah" value="1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Subtotal" readonly>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button class="btn btn-sm btn-primary" id="addItemBtn">
                                                <i class="fas fa-plus"></i> Tambah Item
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                            <div style="width: 300px; border-top: 1px solid #eee; padding-top: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Subtotal:</span>
                                    <span>Rp 0</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Diskon:</span>
                                    <span>Rp 0</span>
                                </div>
                                <div
                                    style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;">
                                    <span>Total:</span>
                                    <span>Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Pembelian</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Purchase Modal -->
    <div class="modal fade" id="viewPurchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">No. Pembelian</label>
                                <input type="text" class="form-control" value="PBL-20230601-001" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="text" class="form-control" value="01 Jun 2025" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Supplier</label>
                                <input type="text" class="form-control" value="PT Sumber Jaya" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kasir</label>
                                <input type="text" class="form-control" value="Admin" readonly>
                            </div>
                        </div>

                        <h5 style="margin: 20px 0 10px; color: var(--primary);">Daftar Produk</h5>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th width="100px">Harga</th>
                                        <th width="100px">Jumlah</th>
                                        <th width="120px">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cappuccino</td>
                                        <td>Rp 25.000</td>
                                        <td>20</td>
                                        <td>Rp 500.000</td>
                                    </tr>
                                    <tr>
                                        <td>Teh Tarik</td>
                                        <td>Rp 15.000</td>
                                        <td>30</td>
                                        <td>Rp 450.000</td>
                                    </tr>
                                    <tr>
                                        <td>Kopi Susu</td>
                                        <td>Rp 20.000</td>
                                        <td>15</td>
                                        <td>Rp 300.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                            <div style="width: 300px; border-top: 1px solid #eee; padding-top: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Subtotal:</span>
                                    <span>Rp 1.250.000</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Diskon:</span>
                                    <span>Rp 0</span>
                                </div>
                                <div
                                    style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;">
                                    <span>Total:</span>
                                    <span>Rp 1.250.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });

        // Close sidebar when clicking on overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            this.classList.remove('active');
        });

        // Close sidebar when clicking on menu item (for mobile)
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    document.getElementById('sidebar').classList.remove('active');
                    document.getElementById('sidebarOverlay').classList.remove('active');
                }
            });
        });

        // Initialize Bootstrap modals
        const addPurchaseModal = new bootstrap.Modal(document.getElementById('addPurchaseModal'));
        const viewPurchaseModal = new bootstrap.Modal(document.getElementById('viewPurchaseModal'));

        // Show Add Purchase Modal
        document.getElementById('addPurchaseBtn').addEventListener('click', function() {
            addPurchaseModal.show();
        });

        // Add Item Row
        document.getElementById('addItemBtn').addEventListener('click', function() {
            var newRow = `
                <tr>
                    <td>
                        <select class="form-control">
                            <option value="">Pilih Produk</option>
                            <option value="1">Cappuccino</option>
                            <option value="2">Teh Tarik</option>
                            <option value="3">Kopi Susu</option>
                            <option value="4">Nasi Goreng Spesial</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" placeholder="Harga">
                    </td>
                    <td>
                        <input type="number" class="form-control" placeholder="Jumlah" value="1">
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="Subtotal" readonly>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            document.getElementById('purchaseItems').insertAdjacentHTML('beforeend', newRow);
        });

        // Show View Purchase Modal when clicking detail buttons
        document.querySelectorAll('.view-detail-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                viewPurchaseModal.show();
            });
        });

        // Calculate subtotal when price or quantity changes
        document.addEventListener('input', function(e) {
            if (e.target.type === 'number' && (e.target.placeholder === 'Harga' || e.target.placeholder === 'Jumlah')) {
                const row = e.target.closest('tr');
                const priceInput = row.querySelector('input[placeholder="Harga"]');
                const qtyInput = row.querySelector('input[placeholder="Jumlah"]');
                const subtotalInput = row.querySelector('input[placeholder="Subtotal"]');

                if (priceInput && qtyInput && subtotalInput) {
                    const price = parseFloat(priceInput.value) || 0;
                    const qty = parseFloat(qtyInput.value) || 0;
                    subtotalInput.value = 'Rp ' + (price * qty).toLocaleString('id-ID');
                }
            }
        });

        // Delete item row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fa-trash') {
                const btn = e.target.closest('button');
                if (btn) {
                    const row = btn.closest('tr');
                    if (row && row.parentElement.id === 'purchaseItems' && row.parentElement.children.length > 1) {
                        row.remove();
                    }
                }
            }
        });
    </script>
</body>

</html>