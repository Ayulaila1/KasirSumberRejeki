<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Cafe Suki</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--primary);
            cursor: pointer;
            display: none;
        }

        .header-title h3 {
            margin: 0;
            color: var(--primary);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-btn-primary {
            background-color: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }

        .header-btn-primary:hover {
            background-color: #69423f;
            border-color: #69423f;
            color: white;
        }

        .header-btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .header-btn-outline:hover {
            background-color: rgba(122, 75, 71, 0.1);
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
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .sidebar-header h3 {
            font-weight: 600;
            margin: 0;
        }

        .sidebar-header i {
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
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
            padding-left: 0;
            display: none;
            background-color: rgba(122, 75, 71, 0.05);
        }

        .submenu .menu-item {
            padding: 10px 15px 10px 40px;
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

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            flex: 1;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
            padding: 15px 20px;
        }

        .stat-card {
            border-left: 4px solid var(--primary);
        }

        .stat-card .card-body {
            padding: 15px;
        }

        .stat-card h5 {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-card h2 {
            font-weight: 600;
            margin: 10px 0;
        }

        .stat-card .trend {
            font-size: 13px;
            display: flex;
            align-items: center;
        }

        .trend.up {
            color: var(--success);
        }

        .trend.down {
            color: var(--danger);
        }

        .trend.neutral {
            color: var(--warning);
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .badge-outline {
            background-color: transparent;
            border: 1px solid;
        }

        .badge-sachet {
            color: #6f42c1;
            border-color: #6f42c1;
        }

        .badge-racikan {
            color: #fd7e14;
            border-color: #fd7e14;
        }

        .badge-bahan_baku {
            color: #20c997;
            border-color: #20c997;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }

        .stok-warning {
            color: var(--danger);
            font-weight: 500;
        }

        .form-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-section h4 {
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #69423f;
            border-color: #69423f;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 20px;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-left: 250px;
            transition: all 0.3s;
        }

        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .footer {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }

            .header-title {
                display: none;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
            display: none;
        }

        .sidebar.show+.sidebar-overlay {
            display: block;
        }

        /* Breadcrumb adjustment */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-mug-hot"></i>
            <h3>Cafe Suki</h3>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard.html" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="produk.html" class="menu-item active">
                <i class="fas fa-box"></i>
                <span>Produk</span>
            </a>

            <div class="menu-item has-submenu">
                <i class="fas fa-users"></i>
                <span>Supplier</span>
            </div>
            <div class="submenu">
                <a href="managemen_supplier.html" class="menu-item">Daftar Supplier</a>
                <a href="tambah_supplier.html" class="menu-item">Tambah Supplier</a>
            </div>

            <a href="pembelian.html" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Pembelian</span>
            </a>

            <a href="retur.html" class="menu-item">
                <i class="fas fa-exchange-alt"></i>
                <span>Retur Titipan</span>
            </a>

            <a href="laporan.html" class="menu-item">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Laporan</span>
            </a>

            <div class="menu-item has-submenu">
                <i class="fas fa-users-cog"></i>
                <span>Pengguna</span>
            </div>
            <div class="submenu">
                <a href="daftar_pengguna.html" class="menu-item">Daftar Pengguna</a>
                <a href="tambah_pengguna.html" class="menu-item">Tambah Pengguna</a>
            </div>

            <a href="pengaturan.html" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-title">
                <h3>Manajemen Produk</h3>
            </div>
        </div>
        <div class="header-right">
            <a href="dashboard.html" class="header-btn header-btn-outline">
                <i class="fas fa-home"></i>
                <span class="d-none d-md-inline">Home</span>
            </a>
            <a href="produk.html" class="header-btn header-btn-primary">
                <i class="fas fa-box"></i>
                <span class="d-none d-md-inline">Produk</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>

            <!-- Stat Cards -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h5>Total Pendapatan</h5>
                            <h2>Rp 12.450.000</h2>
                            <div class="trend up">
                                <i class="fas fa-arrow-up me-1"></i> 12% dari bulan lalu
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h5>Total Supplier</h5>
                            <h2>8</h2>
                            <div class="trend neutral">
                                <i class="fas fa-circle me-1"></i> 2 supplier aktif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h5>Total Produk</h5>
                            <h2>128</h2>
                            <div class="trend up">
                                <i class="fas fa-arrow-up me-1"></i> 5 produk baru
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h5>Stok Minimum</h5>
                            <h2>12</h2>
                            <div class="trend neutral">
                                <i class="fas fa-circle me-1"></i> Perlu restock
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Produk</h5>
                    <div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahProdukModal">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50px">#</th>
                                    <th>Produk</th>
                                    <th>Jenis</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Supplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=100&q=60"
                                                class="product-img me-3" alt="Cappuccino">
                                            <div>
                                                <div class="fw-bold">Cappuccino</div>
                                                <small class="text-muted">Minuman</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-sachet">Sachet</span>
                                    </td>
                                    <td>Rp 25.000</td>
                                    <td class="fw-bold">45</td>
                                    <td>Supplier A</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cm90aSUyMGJha2FyfGVufDB8fDB8fHww&auto=format&fit=crop&w=100&q=60"
                                                class="product-img me-3" alt="Roti Bakar">
                                            <div>
                                                <div class="fw-bold">Roti Bakar Coklat Keju</div>
                                                <small class="text-muted">Makanan</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-racikan">Racikan</span>
                                    </td>
                                    <td>Rp 22.000</td>
                                    <td class="fw-bold">32</td>
                                    <td>Supplier B</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://images.unsplash.com/photo-1551029506-0807df4e2031?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8bWFuZ29qfGVufDB8fDB8fHww&auto=format&fit=crop&w=100&q=60"
                                                class="product-img me-3" alt="Jus Mangga">
                                            <div>
                                                <div class="fw-bold">Jus Mangga</div>
                                                <small class="text-muted">Minuman</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-bahan_baku">Bahan Baku</span>
                                    </td>
                                    <td>Rp 18.000</td>
                                    <td class="stok-warning">4</td>
                                    <td>Supplier C</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a2VudGFuZyUyMGdvcmVuZ3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=100&q=60"
                                                class="product-img me-3" alt="Kentang Goreng">
                                            <div>
                                                <div class="fw-bold">Kentang Goreng</div>
                                                <small class="text-muted">Snack</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-sachet">Sachet</span>
                                    </td>
                                    <td>Rp 25.000</td>
                                    <td class="fw-bold">18</td>
                                    <td>Supplier A</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://images.unsplash.com/photo-1558312651-5b0c0c4a5b0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFuY2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=100&q=60"
                                                class="product-img me-3" alt="Pancake">
                                            <div>
                                                <div class="fw-bold">Pancake Maple</div>
                                                <small class="text-muted">Makanan</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-racikan">Racikan</span>
                                    </td>
                                    <td>Rp 28.000</td>
                                    <td class="fw-bold">15</td>
                                    <td>Supplier D</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2023 Cafe Suki. All rights reserved.</p>
    </footer>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="namaProduk" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="namaProduk" required>
                                </div>

                                <div class="mb-3">
                                    <label for="jenisProduk" class="form-label">Jenis Produk</label>
                                    <select class="form-select" id="jenisProduk" required>
                                        <option value="">Pilih Jenis Produk</option>
                                        <option value="sachet">Sachet</option>
                                        <option value="racikan">Racikan</option>
                                        <option value="bahan_baku">Bahan Baku</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="minuman">Minuman</option>
                                        <option value="makanan">Makanan</option>
                                        <option value="snack">Snack</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga Jual</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stokMinimum" class="form-label">Stok Minimum</label>
                                    <input type="number" class="form-control" id="stokMinimum" required>
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier" required>
                                        <option value="">Pilih Supplier</option>
                                        <option value="1">Supplier A</option>
                                        <option value="2">Supplier B</option>
                                        <option value="3">Supplier C</option>
                                        <option value="4">Supplier D</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="gambarProduk" class="form-label">Gambar Produk</label>
                                    <input class="form-control" type="file" id="gambarProduk">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="3"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isTitipan">
                            <label class="form-check-label" for="isTitipan">
                                Produk Titipan
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Produk</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking on overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
        });

        // Close sidebar when clicking on a menu item (for mobile)
        document.querySelectorAll('.sidebar-menu .menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });

        // Toggle submenu
        document.querySelectorAll('.menu-item.has-submenu').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');
                const submenu = this.nextElementSibling;

                if (submenu.style.display === 'block') {
                    submenu.style.display = 'none';
                } else {
                    submenu.style.display = 'block';
                }
            });
        });

        // Set active menu item based on current page
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.href === window.location.href) {
                item.classList.add('active');
            }
        });
    </script>
</body>

</html>