<div>
    <!-- Konten Utama -->
    <div class="main-content {{ $tampilSidebar ? 'sidebar-open' : '' }}" id="mainContent">
        <!-- Area Konten -->
        <div>
            <!-- Halaman Dashboard -->
            @if($halamanSekarang === 'dashboard')
            <div id="dashboardPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </h1>
                    <div>
                        <button class="btn btn-primary" id="exportReport" onclick="exportToExcel()">
                            <i class="fas fa-download"></i> Export Laporan
                        </button>
                    </div>
                </div>

                <!-- Kartu Statistik Dashboard -->
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Total Pendapatan</div>
                                <div class="card-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>

                                <div class="card-footer {{ $persen >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fas {{ $persen >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                    {{ abs(round($persen, 2)) }}% dari bulan lalu
                                </div>
                            </div>

                            <div class="card-icon primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Total Produk</div>
                                <div class="card-value">{{ $this->totalProduk() }}</div>
                                <div class="card-footer positive">
                                    <i class="fas fa-arrow-up"></i> {{ $this->totalProduk() }} produk baru
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

                <!-- Bagian Grafik -->
                <div class="row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Pendapatan Bulanan</h3>
                            <div class="chart-actions">
                                <select class="form-select" id="revenueYear" wire:model="tahunPendapatan"
                                    style="width: 120px;">
                                    <option value="2025">Tahun 2025</option>
                                    <option value="2024">Tahun 2024</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-wrapper" style="height:220px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Produk Terlaris</h3>
                            <div class="chart-actions">
                                <select class="form-select" id="bestSellerMonth" wire:model="bulanProdukTerlaris"
                                    style="width: 120px;">
                                    <option value="sekarang">Bulan Ini</option>
                                    <option value="lalu">Bulan Lalu</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-wrapper" style="height:220px;">
                            <canvas id="bestSellerChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Terakhir -->
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
                                        <button class="btn btn-sm btn-primary"
                                            wire:click="lihatTransaksi('TRX-20250628-001')">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </td>
                                </tr>
                                <!-- transaksi contoh lain -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Halaman Produk -->
            @if($halamanSekarang === 'products')
            <div id="productsPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-box"></i> Daftar Produk
                    </h1>
                    <div>
                        <button class="btn btn-primary" wire:click="pindahHalaman('addProduct')">
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
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- contoh produk lain -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Tambah Produk -->
            @if($halamanSekarang === 'addProduct')
            <div id="addProductPage">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-plus-circle"></i> Tambah Produk
                    </h1>
                    <div>
                        <button class="btn btn-secondary" wire:click="pindahHalaman('products')">
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

    <!-- Modal Detail Transaksi -->
    <div class="modal {{ $tampilModalTransaksi ? 'show' : '' }}" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Transaksi {{ $transaksiTerpilih['id'] ?? '' }}</h3>
                <button class="modal-close" wire:click="tutupModal">&times;</button>
            </div>
            <div class="modal-body">
                @if($transaksiTerpilih)
                <div class="transaction-info">
                    <div class="info-row">
                        <span class="info-label">ID Transaksi:</span>
                        <span class="info-value">{{ $transaksiTerpilih['id'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value">{{ $transaksiTerpilih['tanggal'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pelanggan:</span>
                        <span class="info-value">{{ $transaksiTerpilih['pelanggan'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="status-badge success">{{ $transaksiTerpilih['status']
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
                            @foreach($transaksiTerpilih['items'] as $item)
                            <tr>
                                <td>{{ $item['produk'] }}</td>
                                <td>{{ $item['harga'] }}</td>
                                <td>{{ $item['jumlah'] }}</td>
                                <td>{{ $item['subtotal'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 500;">Total:</td>
                                <td style="font-weight: 500;">{{ $transaksiTerpilih['total'] }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="window.print()">Print</button>
                <button class="btn btn-primary" wire:click="tutupModal">Tutup</button>
            </div>
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
                    data: @json($this->ambilDataPendapatan()),
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
                plugins: { legend: { display: false } },
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
                    data: @json($this->ambilDataProdukTerlaris()),
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
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // opsi: jika nanti kamu emit event dari component untuk update chart, bisa handle di sini
        Livewire.on('tahunPendapatanDiubah', () => {
            revenueChart.data.datasets[0].data = @json($this->ambilDataPendapatan());
            revenueChart.update();
        });

        Livewire.on('bulanProdukTerlarisDiubah', () => {
            bestSellerChart.data.datasets[0].data = @json($this->ambilDataProdukTerlaris());
            bestSellerChart.update();
        });
    });

    // Export to Excel function (JS)
    function exportToExcel() {
        const wb = XLSX.utils.book_new();
        const wsData = [
            ["ID Transaksi", "Tanggal", "Pelanggan", "Total", "Status"],
            ["TRX-20250628-001", "28 Jun 2025", "Pelanggan 1", "Rp 125.000", "Selesai"],
            ["TRX-20250628-002", "28 Jun 2025", "Pelanggan 2", "Rp 85.000", "Selesai"]
        ];
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, "Laporan Transaksi");
        XLSX.writeFile(wb, "Laporan_Transaksi_Cafe_Suki.xlsx");
    }
</script>