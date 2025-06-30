<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Overlay for sidebar */
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

        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            color: #666;
        }

        /* PDF Styles */
        .pdf-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7a4b47;
        }

        .pdf-title {
            color: #7a4b47;
            font-size: 24px;
            font-weight: bold;
        }

        .pdf-subtitle {
            color: #666;
            font-size: 14px;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .pdf-table th {
            background-color: #7a4b47;
            color: white;
            padding: 8px;
            text-align: left;
        }

        .pdf-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .pdf-footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
            color: #666;
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

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
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
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item active">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-box-open"></i>
                <span>Products</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
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
            <div class="user-profile">
                <div class="user-avatar">A</div>
                <span class="user-name">Admin</span>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-truck"></i>
                    Supplier Management
                </h1>
                <div>
                    <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fas fa-plus-circle"></i> Add Supplier
                    </button>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Total Income</div>
                            <div class="card-value">Rp 12.450.000</div>
                        </div>
                        <div class="card-icon primary">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="card-footer positive">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Total Suppliers</div>
                            <div class="card-value">8</div>
                        </div>
                        <div class="card-icon success">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="status-badge success">2 active</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Total Products</div>
                            <div class="card-value">128</div>
                        </div>
                        <div class="card-icon warning">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="card-footer positive">
                        <i class="fas fa-arrow-up"></i> 5 new products
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Need Restock</div>
                            <div class="card-value">12</div>
                        </div>
                        <div class="card-icon danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="status-badge danger">Urgent</span>
                    </div>
                </div>
            </div>

            <!-- Supplier Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplier Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>PT Sumber Jaya</td>
                                <td>08123456789</td>
                                <td>Jl. Merdeka No. 123, Jakarta</td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>2025-06-25</td>
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
                                <td>2</td>
                                <td>CV Mandiri Sejahtera</td>
                                <td>08234567890</td>
                                <td>Jl. Sudirman No. 45, Bandung</td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>2025-06-20</td>
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
                                <td>3</td>
                                <td>UD Makmur Abadi</td>
                                <td>08345678901</td>
                                <td>Jl. Gatot Subroto No. 67, Surabaya</td>
                                <td><span class="status-badge warning">Inactive</span></td>
                                <td>2025-05-15</td>
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

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <p>&copy; 2025 Kasir App - Supplier Management System</p>
            </div>
        </footer>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="supplierForm">
                        <div class="form-group">
                            <label for="supplierName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="supplierName" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="supplierContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="supplierContact" name="kontak" required>
                        </div>
                        <div class="form-group">
                            <label for="supplierAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="supplierAddress" name="alamat" rows="3"
                                required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSupplierBtn">Save Supplier</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-download me-2"></i>Export Report
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="exportForm">
                        <div class="form-group">
                            <label for="exportFormat" class="form-label">Format</label>
                            <select class="form-control" id="exportFormat">
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="pdf">PDF (.pdf)</option>
                                <option value="csv">CSV (.csv)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control" id="startDate">
                                <span class="align-self-center">to</span>
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="exportBtn">Export</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;

        // Toggle sidebar on mobile
        document.querySelector('.toggle-sidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').style.display = 'block';
        });

        // Close sidebar when clicking on overlay
        document.querySelector('.sidebar-overlay').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.remove('show');
            this.style.display = 'none';
        });

        // Close sidebar when clicking on a menu item (for mobile)
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    document.querySelector('.sidebar').classList.remove('show');
                    document.querySelector('.sidebar-overlay').style.display = 'none';
                }
            });
        });

        // Example JavaScript for handling form submission
        document.getElementById('saveSupplierBtn').addEventListener('click', function () {
            const form = document.getElementById('supplierForm');
            if (form.checkValidity()) {
                // Here you would typically send the data to the server via AJAX
                alert('Supplier added successfully!');
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                modal.hide();
                // Reset the form
                form.reset();
                // In a real application, you would refresh the table or add the new row
            } else {
                form.reportValidity();
            }
        });

        // Export functionality
        document.getElementById('exportBtn').addEventListener('click', function () {
            const format = document.getElementById('exportFormat').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            // In a real application, you would fetch data from the server based on date range
            // For this example, we'll use the table data

            if (format === 'excel') {
                exportToExcel();
            } else if (format === 'csv') {
                exportToCSV();
            } else {
                exportToPDF();
            }

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
            modal.hide();
        });

        function exportToExcel() {
            // Get table data
            const table = document.querySelector('.table');
            const workbook = XLSX.utils.table_to_book(table);
            XLSX.writeFile(workbook, 'suppliers_report.xlsx');
        }

        function exportToCSV() {
            // Get table data
            const table = document.querySelector('.table');
            const csv = XLSX.utils.sheet_to_csv(XLSX.utils.table_to_sheet(table));
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            saveAs(blob, 'suppliers_report.csv');
        }

        function exportToPDF() {
            // Create a new PDF document
            const doc = new jsPDF();

            // Add title
            doc.setFontSize(18);
            doc.setTextColor(122, 75, 71);
            doc.text('Supplier Report', 105, 15, { align: 'center' });

            // Add subtitle with date range
            doc.setFontSize(12);
            doc.setTextColor(100, 100, 100);
            const startDate = document.getElementById('startDate').value || 'N/A';
            const endDate = document.getElementById('endDate').value || 'N/A';
            doc.text(`Date Range: ${startDate} to ${endDate}`, 105, 22, { align: 'center' });

            // Add current date
            const currentDate = new Date().toLocaleDateString();
            doc.text(`Generated on: ${currentDate}`, 105, 29, { align: 'center' });

            // Get table data
            const table = document.querySelector('.table');
            const headers = [];
            const rows = [];

            // Get headers
            table.querySelectorAll('thead th').forEach(th => {
                headers.push(th.innerText);
            });

            // Get rows data
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => {
                    // Remove action buttons from PDF
                    if (!td.querySelector('.action-buttons')) {
                        // Get text content, handling status badges
                        if (td.querySelector('.status-badge')) {
                            row.push(td.querySelector('.status-badge').innerText);
                        } else {
                            row.push(td.innerText);
                        }
                    }
                });
                rows.push(row);
            });

            // Add table to PDF
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 35,
                styles: {
                    cellPadding: 5,
                    fontSize: 10,
                    valign: 'middle',
                    halign: 'left',
                    fillColor: [122, 75, 71],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                headStyles: {
                    fillColor: [122, 75, 71],
                    textColor: [255, 255, 255]
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                columnStyles: {
                    0: { cellWidth: 10 },
                    1: { cellWidth: 40 },
                    2: { cellWidth: 30 },
                    3: { cellWidth: 50 },
                    4: { cellWidth: 20 },
                    5: { cellWidth: 25 }
                }
            });

            // Add footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text(`Page ${i} of ${pageCount}`, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }

            // Save the PDF
            doc.save('suppliers_report.pdf');
        }

        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>