<div class="container-fluid mb-5" style="margin-top: 40px;">
    @if($halamanSekarang === 'dashboard')
    <div id="dashboardPage">

        <!-- HEADER DASHBOARD -->
        <div class="page-header bg-white p-4 rounded shadow-sm mb-4">
            <div>
                <h3 class="fw-bold mb-1">📊 Dashboard</h3>
                <p class="text-muted mb-0">
                    <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                    Menu ini digunakan untuk melihat admin dashboard harian.
                </p>
            </div>
            <button class="btn btn-primary d-flex align-items-center" id="exportReport" onclick="exportToExcel()">
                <i class="fas fa-download me-2"></i> Export Laporan
            </button>
        </div>

        <!-- KARTU STATISTIK -->
        <div class="dashboard-cards">
            <!-- Total Pendapatan -->
            <div class="card card-clickable" wire:click="tampilkanData('total-pendapatan')">
                <div class="card-header">
                    <div>
                        <p class="card-title">Total Pendapatan</p>
                        <p class="card-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        <p class="card-footer {{ $persen >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas {{ $persen >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ abs(round($persen, 2)) }}% dari bulan lalu
                        </p>
                    </div>
                    <div class="card-icon primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="card card-clickable" wire:click="tampilkanData('total-produk')">
                <div class="card-header">
                    <div>
                        <p class="card-title">Total Produk</p>
                        <p class="card-value">{{ $this->totalProduk() }}</p>
                        <p class="card-footer positive">
                            <i class="fas fa-arrow-up"></i> {{ $this->totalProduk() }} produk baru
                        </p>
                    </div>
                    <div class="card-icon success">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="card card-clickable" wire:click="tampilkanData('stok-menipis')">
                <div class="card-header">
                    <div>
                        <p class="card-title">Stok Menipis</p>
                        <p class="card-value">{{ count($produkMenipis) }}</p>
                        @if(count($produkMenipis) > 0)
                        <p class="card-footer negative">
                            <i class="fas fa-exclamation-circle"></i> Perlu restock
                        </p>
                        @else
                        <p class="card-footer positive">
                            <i class="fas fa-check-circle"></i> Stok aman
                        </p>
                        @endif
                    </div>
                    <div class="card-icon warning">
                        <i class="fas {{ count($produkMenipis) > 0 ? 'fa-exclamation-triangle' : 'fa-thumbs-up' }}"></i>
                    </div>
                </div>
            </div>

            <!-- Total Supplier -->
            <div class="card card-clickable" wire:click="tampilkanData('total-supplier')">
                <div class="card-header">
                    <div>
                        <p class="card-title">Total Supplier</p>
                        <p class="card-value">{{ $this->totalSupplier() }}</p>
                        <p class="card-footer">
                            <i class="fas fa-info-circle"></i> {{ $this->totalSupplier() }} supplier aktif
                        </p>
                    </div>
                    <div class="card-icon danger">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRANSAKSI TERAKHIR -->
        <div class="page-header bg-white p-4 rounded shadow-sm mt-5 mb-3"
            wire:click="tampilkanData('transaksi-terakhir')" style="cursor:pointer;">
            <div>
                <h3 class="fw-bold mb-1">🧾 Transaksi Terakhir</h3>
                <p class="text-muted mb-0">
                    <i class="fa-regular fa-circle-question me-1 text-danger"></i>
                    Melihat dan mengunduh laporan transaksi terakhir.
                </p>
            </div>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-list"></i> Lihat Semua
            </a>
        </div>

        <!-- TABEL TRANSAKSI (ringkasan) -->
        <div class="table-container p-3">
            <div class="table-responsive">
                <table>
                    <thead style="background-color: var(--primary); color: white;">
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
                        @forelse ($transaksiTerakhir as $transaksi)
                        <tr>
                            <td>TRX-{{ $transaksi->created_at->format('Ymd') }}-{{ str_pad($transaksi->idpenjualan, 3,
                                '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $transaksi->created_at->format('d M Y') }}</td>
                            <td>{{ $transaksi->nama_pelanggan ?? 'Umum' }}</td>
                            <td>Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if($transaksi->status == 'lunas')
                                <span class="status-badge success">Selesai</span>
                                @else
                                <span class="status-badge warning">{{ ucfirst($transaksi->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/laporan-penjualan">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">Belum ada transaksi terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div> {{-- end dashboardPage --}}

    @elseif($halamanSekarang === 'detail')
    <div class="bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">📑 {{ $judulTabel }}</h3>
            <button class="btn btn-secondary" wire:click="kembaliDashboard">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        @if(count($dataTabel) > 0)
                        @foreach(array_keys($dataTabel->first()->getAttributes()) as $kolom)
                        <th>{{ ucfirst(str_replace('_', ' ', $kolom)) }}</th>
                        @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataTabel as $baris)
                    <tr>
                        @foreach($baris->getAttributes() as $nilai)
                        <td>{{ $nilai }}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="text-center text-muted p-3">Tidak ada data ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>