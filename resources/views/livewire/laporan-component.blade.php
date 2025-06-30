<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sistem Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* CSS yang Anda berikan */
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
        }

        .report-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .report-title {
            color: var(--primary);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .report-subtitle {
            color: #666;
            font-size: 16px;
            font-weight: 400;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .report-table th {
            background-color: var(--primary);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .report-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .report-table tr:hover {
            background-color: rgba(122, 75, 71, 0.05);
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: 600;
            background-color: rgba(122, 75, 71, 0.1) !important;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
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

        .report-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            text-align: center;
            margin-top: 50px;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 0 auto 10px;
            padding-top: 10px;
        }

        .print-button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px auto;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                background-color: white;
            }

            .report-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>

    <div class="report-container">
        <!-- Header Laporan -->
        <div class="report-header">
            <h1 class="report-title">LAPORAN TRANSAKSI KASIR</h1>
            <p class="report-subtitle">Toko ABC - Jl. Contoh No. 123, Jakarta</p>
        </div>

        <!-- Informasi Laporan -->
        <div class="report-info">
            <div>
                <p><strong>Tanggal:</strong> 28 Juni 2025</p>
                <p><strong>Periode:</strong> 1 - 28 Juni 2025</p>
            </div>
            <div>
                <p><strong>Dibuat oleh:</strong> Admin</p>
                <p><strong>Tanggal Cetak:</strong> 28/06/2025 10:00</p>
            </div>
        </div>

        <!-- Contoh Laporan Produk -->
        <h3 style="color: var(--primary); margin-bottom: 15px;">Laporan Stok Produk</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Jenis</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                    <th>Status Stok</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>PRD001</td>
                    <td>Kopi Sachet</td>
                    <td>Sachet</td>
                    <td class="text-right">50</td>
                    <td class="text-right">Rp 5.000</td>
                    <td><span class="status-badge success">Aman</span></td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>PRD002</td>
                    <td>Kopi Racikan Spesial</td>
                    <td>Racikan</td>
                    <td class="text-right">15</td>
                    <td class="text-right">Rp 12.000</td>
                    <td><span class="status-badge warning">Hampir Habis</span></td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>PRD003</td>
                    <td>Biji Kopi Arabica</td>
                    <td>Bahan Baku</td>
                    <td class="text-right">2.5 kg</td>
                    <td class="text-right">Rp 120.000</td>
                    <td><span class="status-badge danger">Stok Minimum</span></td>
                </tr>
                <!-- Data lainnya akan diisi dari database -->
            </tbody>
        </table>

        <!-- Contoh Laporan Pembelian -->
        <h3 style="color: var(--primary); margin-bottom: 15px; margin-top: 30px;">Laporan Pembelian</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Pembelian</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>PBL-202506-001</td>
                    <td>05/06/2025</td>
                    <td>Toko Sumber Jaya</td>
                    <td class="text-right">Rp 1.250.000</td>
                    <td>Admin</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>PBL-202506-002</td>
                    <td>15/06/2025</td>
                    <td>CV. Aroma Kopi</td>
                    <td class="text-right">Rp 2.750.000</td>
                    <td>Admin</td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>Total Pembelian:</strong></td>
                    <td class="text-right"><strong>Rp 4.000.000</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Contoh Laporan Retur Titipan -->
        <h3 style="color: var(--primary); margin-bottom: 15px; margin-top: 30px;">Laporan Retur Titipan</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>10/06/2025</td>
                    <td>Toko Sumber Jaya</td>
                    <td>Susu Kental Manis</td>
                    <td class="text-right">5</td>
                    <td>Kadaluarsa</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>20/06/2025</td>
                    <td>CV. Aroma Kopi</td>
                    <td>Kopi Sachet Vanilla</td>
                    <td class="text-right">10</td>
                    <td>Kemasan Rusak</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Laporan -->
        <div class="report-footer">
            <div>
                <p><strong>Catatan:</strong></p>
                <p>Laporan ini dibuat secara otomatis oleh sistem.</p>
            </div>
            <div class="signature">
                <p>Jakarta, 28 Juni 2025</p>
                <div class="signature-line"></div>
                <p><strong>Admin Toko ABC</strong></p>
            </div>
        </div>
    </div>

    <script>
        // Script untuk mengisi data dari database
        document.addEventListener('DOMContentLoaded', function() {
            // Anda bisa menambahkan fungsi untuk mengambil data dari database
            // dan mengisi tabel-tabel di laporan ini

            // Contoh:
            // fetch('/api/laporan-produk')
            //     .then(response => response.json())
            //     .then(data => {
            //         // Isi tabel produk dengan data dari API
            //     });

            // Set tanggal saat ini
            const now = new Date();
            document.querySelectorAll('.current-date').forEach(el => {
                el.textContent = now.toLocaleDateString('id-ID');
            });

            document.querySelectorAll('.current-datetime').forEach(el => {
                el.textContent = now.toLocaleString('id-ID');
            });
        });
    </script>
</body>

</html>