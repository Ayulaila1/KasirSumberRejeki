<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retur Titipan</title>
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
            z-index: 1000;
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

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
            cursor: pointer;
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
            flex: 1;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1100;
            overflow-y: auto;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s;
        }

        .modal-dialog {
            background-color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            margin: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
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
            color: #999;
        }

        .modal-close:hover {
            color: var(--danger);
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

        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
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
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-dialog {
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-store"></i>
            <h3>Kasir App</h3>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="menu-item">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i> Penjualan
            </a>
            <a href="#" class="menu-item active">
                <i class="fas fa-exchange-alt"></i> Retur Titipan
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-boxes"></i> Produk
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i> Supplier
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-line"></i> Laporan
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-nav">
            <button class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-profile">
                <div class="user-dropdown">
                    <div class="user-avatar">A</div>
                    <span class="user-name">Admin</span>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-area">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-exchange-alt"></i> Retur Titipan
                </h1>
                <button class="btn btn-primary" id="btnTambahRetur">
                    <i class="fas fa-plus"></i> Tambah Retur
                </button>
            </div>

            <!-- Form Filter -->
            <div class="form-container mb-3">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggalMulai">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggalSelesai">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <select class="form-select" id="filterSupplier">
                            <option value="">Semua Supplier</option>
                            <!-- Data supplier akan diisi via JavaScript -->
                        </select>
                    </div>
                    <div class="form-group" style="align-self: flex-end;">
                        <button class="btn btn-primary" id="btnFilter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Retur Titipan -->
            <div class="table-container">
                <div class="table-responsive">
                    <table id="tabelRetur">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            &copy; 2025 Kasir App - All Rights Reserved
        </div>
    </div>

    <!-- Modal Form Retur Titipan -->
    <div class="modal" id="modalRetur">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Retur Titipan</h5>
                    <button type="button" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formRetur">
                        <input type="hidden" id="idretur_titipan">
                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" id="supplier_idsupplier" required>
                                <option value="">Pilih Supplier</option>
                                <!-- Data supplier akan diisi via JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Produk Titipan</label>
                            <select class="form-select" id="produk_idproduk" required>
                                <option value="">Pilih Produk</option>
                                <!-- Data produk akan diisi via JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control" id="qty" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnBatalRetur">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanRetur">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi sidebar
            $('.toggle-sidebar').click(function(e) {
                e.stopPropagation();
                $('.sidebar').addClass('show');
                $('.sidebar-overlay').addClass('show');
            });

            // Tutup sidebar saat overlay diklik
            $('.sidebar-overlay').click(function() {
                $('.sidebar').removeClass('show');
                $('.sidebar-overlay').removeClass('show');
            });

            // Tutup sidebar saat mengklik di luar sidebar
            $(document).click(function(e) {
                if (!$(e.target).closest('.sidebar').length && !$(e.target).is('.toggle-sidebar')) {
                    $('.sidebar').removeClass('show');
                    $('.sidebar-overlay').removeClass('show');
                }
            });

            // Dropdown user profile
            $('.user-avatar, .user-name').click(function(e) {
                e.stopPropagation();
                $('.dropdown-menu').toggleClass('show');
            });

            // Tutup dropdown saat mengklik di luar
            $(document).click(function() {
                $('.dropdown-menu').removeClass('show');
            });

            // Inisialisasi data dan event handler
            loadDataRetur();
            loadSupplierOptions();

            // Event handler untuk tombol tambah retur
            $('#btnTambahRetur').click(function() {
                $('#formRetur')[0].reset();
                $('#idretur_titipan').val('');
                $('#modalRetur').addClass('show');
            });

            // Event handler untuk tombol batal
            $('#btnBatalRetur, .modal-close').click(function() {
                $('#modalRetur').removeClass('show');
            });

            // Event handler ketika supplier dipilih
            $('#supplier_idsupplier').change(function() {
                loadProdukTitipan($(this).val());
            });

            // Event handler untuk simpan retur
            $('#btnSimpanRetur').click(function() {
                simpanRetur();
            });

            // Event handler untuk filter
            $('#btnFilter').click(function() {
                loadDataRetur();
            });

            // Tutup modal saat mengklik di luar modal
            $(document).click(function(e) {
                if ($(e.target).is('.modal')) {
                    $('#modalRetur').removeClass('show');
                }
            });
        });

        function loadDataRetur() {
            // Implementasi AJAX untuk mengambil data retur dari server
            const tanggalMulai = $('#tanggalMulai').val();
            const tanggalSelesai = $('#tanggalSelesai').val();
            const supplierId = $('#filterSupplier').val();

            // Contoh data dummy (pada implementasi nyata, ini akan diganti dengan AJAX)
            const dataDummy = [
                {
                    idretur_titipan: 1,
                    tanggal: '2025-06-28',
                    supplier_nama: 'Supplier A',
                    produk_nama: 'Produk X',
                    qty: 5,
                    keterangan: 'Produk rusak'
                },
                {
                    idretur_titipan: 2,
                    tanggal: '2025-06-25',
                    supplier_nama: 'Supplier B',
                    produk_nama: 'Produk Y',
                    qty: 3,
                    keterangan: 'Tidak laku'
                },
                {
                    idretur_titipan: 3,
                    tanggal: '2025-06-20',
                    supplier_nama: 'Supplier C',
                    produk_nama: 'Produk Z',
                    qty: 2,
                    keterangan: 'Kadaluarsa'
                }
            ];

            // Filter data berdasarkan tanggal dan supplier
            let filteredData = dataDummy;

            if (tanggalMulai && tanggalSelesai) {
                filteredData = filteredData.filter(item => {
                    return item.tanggal >= tanggalMulai && item.tanggal <= tanggalSelesai;
                });
            }

            if (supplierId) {
                filteredData = filteredData.filter(item => {
                    return item.supplier_nama === $(`#filterSupplier option[value="${supplierId}"]`).text();
                });
            }

            // Render data ke tabel
            const tbody = $('#tabelRetur tbody');
            tbody.empty();

            if (filteredData.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data retur</td>
                    </tr>
                `);
            } else {
                filteredData.forEach((item, index) => {
                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.tanggal}</td>
                            <td>${item.supplier_nama}</td>
                            <td>${item.produk_nama}</td>
                            <td>${item.qty}</td>
                            <td>${item.keterangan || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit" data-id="${item.idretur_titipan}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-hapus" data-id="${item.idretur_titipan}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
        }

        function loadSupplierOptions() {
            // Implementasi AJAX untuk mengambil data supplier dari server
            // Contoh data dummy
            const suppliers = [
                { idsupplier: 1, nama: 'Supplier A' },
                { idsupplier: 2, nama: 'Supplier B' },
                { idsupplier: 3, nama: 'Supplier C' }
            ];

            const selectFilter = $('#filterSupplier');
            const selectForm = $('#supplier_idsupplier');

            selectFilter.empty();
            selectForm.empty();

            selectFilter.append('<option value="">Semua Supplier</option>');
            selectForm.append('<option value="">Pilih Supplier</option>');

            suppliers.forEach(supplier => {
                selectFilter.append(`<option value="${supplier.idsupplier}">${supplier.nama}</option>`);
                selectForm.append(`<option value="${supplier.idsupplier}">${supplier.nama}</option>`);
            });
        }

        function loadProdukTitipan(supplierId) {
            // Implementasi AJAX untuk mengambil produk titipan berdasarkan supplier
            // Contoh data dummy
            const produkTitipan = [
                { idproduk: 1, nama: 'Produk X', stok: 10 },
                { idproduk: 2, nama: 'Produk Y', stok: 5 },
                { idproduk: 3, nama: 'Produk Z', stok: 8 }
            ];

            const select = $('#produk_idproduk');
            select.empty();
            select.append('<option value="">Pilih Produk</option>');

            produkTitipan.forEach(produk => {
                select.append(`<option value="${produk.idproduk}" data-stok="${produk.stok}">${produk.nama} (Stok: ${produk.stok})</option>`);
            });
        }

        function simpanRetur() {
            // Implementasi AJAX untuk menyimpan data retur
            const formData = {
                idretur_titipan: $('#idretur_titipan').val(),
                tanggal: $('#tanggal').val(),
                supplier_idsupplier: $('#supplier_idsupplier').val(),
                produk_idproduk: $('#produk_idproduk').val(),
                qty: $('#qty').val(),
                keterangan: $('#keterangan').val()
            };

            // Validasi form
            if (!formData.tanggal || !formData.supplier_idsupplier || !formData.produk_idproduk || !formData.qty) {
                alert('Harap lengkapi semua field yang wajib diisi!');
                return;
            }

            console.log('Data yang akan disimpan:', formData);

            // Pada implementasi nyata, di sini akan ada AJAX POST ke server
            // $.post('/api/retur-titipan', formData, function(response) {
            //     if (response.success) {
            //         $('#modalRetur').modal('hide');
            //         loadDataRetur();
            //     } else {
            //         alert('Gagal menyimpan data: ' + response.message);
            //     }
            // });

            // Untuk contoh, kita anggap simpan berhasil
            $('#modalRetur').removeClass('show');
            loadDataRetur();
            alert('Data retur berhasil disimpan!');
        }

        // Event delegation untuk edit dan hapus
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            // Implementasi AJAX untuk mengambil data retur berdasarkan ID
            // Contoh data dummy
            const returData = {
                idretur_titipan: id,
                tanggal: '2025-06-28',
                supplier_idsupplier: 1,
                produk_idproduk: 1,
                qty: 5,
                keterangan: 'Produk rusak'
            };

            // Isi form dengan data yang ada
            $('#idretur_titipan').val(returData.idretur_titipan);
            $('#tanggal').val(returData.tanggal);
            $('#supplier_idsupplier').val(returData.supplier_idsupplier);
            loadProdukTitipan(returData.supplier_idsupplier);

            // Tunggu sampai opsi produk dimuat
            setTimeout(() => {
                $('#produk_idproduk').val(returData.produk_idproduk);
                $('#qty').val(returData.qty);
                $('#keterangan').val(returData.keterangan);
                $('#modalRetur').addClass('show');
            }, 300);
        });

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus data retur ini?')) {
                // Implementasi AJAX untuk menghapus data
                console.log('Menghapus retur dengan ID:', id);

                // Pada implementasi nyata, di sini akan ada AJAX DELETE ke server
                // $.ajax({
                //     url: '/api/retur-titipan/' + id,
                //     type: 'DELETE',
                //     success: function(response) {
                //         if (response.success) {
                //             loadDataRetur();
                //             alert('Data berhasil dihapus');
                //         } else {
                //             alert('Gagal menghapus data: ' + response.message);
                //         }
                //     }
                // });

                // Untuk contoh, kita anggap hapus berhasil
                loadDataRetur();
                alert('Data retur berhasil dihapus!');
            }
        });
    </script>
</body>

</html>