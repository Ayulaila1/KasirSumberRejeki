<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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
            z-index: 100;
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

        .submenu {
            padding-left: 40px;
            display: none;
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

        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
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
            display: none;
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

        /* Settings Tabs */
        .settings-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .tab-btn:hover:not(.active) {
            color: var(--dark);
            border-bottom-color: #ddd;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        /* Settings Card */
        .settings-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .settings-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .settings-card-title {
            font-weight: 600;
            color: var(--dark);
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

            .toggle-sidebar {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .settings-tabs {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 5px;
            }

            .tab-btn {
                padding: 10px 15px;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-store"></i>
            <h3>Kasir App</h3>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.html" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="produk.html" class="menu-item">
                <i class="fas fa-box-open"></i>
                <span>Produk</span>
            </a>
            <a href="supplier.html" class="menu-item">
                <i class="fas fa-truck"></i>
                <span>Supplier</span>
            </a>
            <a href="transaksi.html" class="menu-item">
                <i class="fas fa-cash-register"></i>
                <span>Transaksi</span>
            </a>
            <a href="laporan.html" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
            </a>
            <a href="pengaturan.html" class="menu-item active">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-dropdown">
                <div class="user-profile">
                    <div class="user-avatar">A</div>
                    <span class="user-name">Admin</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-cog"></i>
                    <h1>Pengaturan</h1>
                </div>
            </div>

            <!-- Settings Tabs -->
            <div class="settings-tabs">
                <button class="tab-btn active" data-tab="general">Umum</button>
                <button class="tab-btn" data-tab="users">Pengguna</button>
                <button class="tab-btn" data-tab="products">Produk</button>
                <button class="tab-btn" data-tab="suppliers">Supplier</button>
                <button class="tab-btn" data-tab="backup">Backup</button>
            </div>

            <!-- General Settings Tab -->
            <div id="general" class="tab-content active">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Pengaturan Umum</h3>
                    </div>
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Toko</label>
                                <input type="text" class="form-control" value="Toko Saya">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Toko</label>
                                <input type="text" class="form-control" value="Jl. Contoh No. 123">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" value="08123456789">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="toko@example.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Logo Toko</label>
                            <input type="file" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Pengaturan Notifikasi</h3>
                    </div>
                    <form>
                        <div class="form-group">
                            <label class="form-label">Notifikasi Stok Minimum</label>
                            <select class="form-select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email untuk Notifikasi</label>
                            <input type="email" class="form-control" value="admin@example.com">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <!-- Users Settings Tab -->
            <div id="users" class="tab-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Daftar Pengguna</h3>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Pengguna</span>
                        </button>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Admin</td>
                                        <td>admin123@gmail.com</td>
                                        <td>Administrator</td>
                                        <td><span class="status-badge success">Aktif</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kasir 1</td>
                                        <td>kasir1@example.com</td>
                                        <td>Kasir</td>
                                        <td><span class="status-badge success">Aktif</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-warning">
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

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Tambah Pengguna Baru</h3>
                    </div>
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select class="form-select">
                                <option value="admin">Administrator</option>
                                <option value="kasir">Kasir</option>
                                <option value="gudang">Gudang</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                    </form>
                </div>
            </div>

            <!-- Products Settings Tab -->
            <div id="products" class="tab-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Pengaturan Produk</h3>
                    </div>
                    <form>
                        <div class="form-group">
                            <label class="form-label">Satuan Default</label>
                            <select class="form-select">
                                <option value="pcs">Pcs</option>
                                <option value="kg">Kilogram</option>
                                <option value="gr">Gram</option>
                                <option value="ml">Mililiter</option>
                                <option value="l">Liter</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok Minimum Default</label>
                            <input type="number" class="form-control" value="5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori Produk</label>
                            <textarea class="form-textarea">Sachet, Racikan, Bahan Baku</textarea>
                            <small class="text-muted">Pisahkan dengan koma untuk menambahkan kategori baru</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <!-- Suppliers Settings Tab -->
            <div id="suppliers" class="tab-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Pengaturan Supplier</h3>
                    </div>
                    <form>
                        <div class="form-group">
                            <label class="form-label">Notifikasi Restock</label>
                            <select class="form-select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Supplier Default</label>
                            <input type="email" class="form-control" value="supplier@example.com">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <!-- Backup Settings Tab -->
            <div id="backup" class="tab-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Backup Data</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Backup Terakhir</label>
                        <p>29 Juni 2025, 15:30 WIB</p>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            <span>Buat Backup Sekarang</span>
                        </button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Restore Data</h3>
                    </div>
                    <form>
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

    <script>
        // Toggle sidebar on mobile
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Show dropdown menu
        document.querySelector('.user-profile').addEventListener('click', function() {
            document.querySelector('.dropdown-menu').classList.toggle('show');
        });

        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabBtns.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                btn.classList.add('active');
                const tabId = btn.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown')) {
                document.querySelector('.dropdown-menu').classList.remove('show');
            }
        });
    </script>
</body>

</html>