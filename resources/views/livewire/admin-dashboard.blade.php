<div>
    <!-- Konten Utama -->
    <div class="main-content {{ $tampilSidebar ? 'sidebar-open' : '' }}" id="mainContent">
        <!-- Area Konten -->
        <div class="container-fluid mb-5" style="margin-top: 40px">
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
                    {{-- 🔽 KARTU 1: Total Pendapatan (Dibuat bisa diklik) 🔽 --}}
                    <div class="card card-clickable" wire:click="pindahHalaman('laporan-pendapatan')"
                        style="cursor: pointer;">
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

                    {{-- 🔽 KARTU 2: Total Produk (Dibuat bisa diklik) 🔽 --}}
                    <div class="card card-clickable" wire:click="pindahHalaman('produk')" style="cursor: pointer;">
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

                    {{-- 🔽 KARTU 3: Stok Menipis (Dibuat bisa diklik) 🔽 --}}
                    <div class="card card-clickable" wire:click="pindahHalaman('produk')" style="cursor: pointer;">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Stok Menipis</div>
                                <div class="card-value">{{ count($produkMenipis) }}</div>
                                @if(count($produkMenipis) > 0)
                                <div class="card-footer negative">
                                    <i class="fas fa-exclamation-circle"></i> Perlu restock
                                </div>
                                @else
                                <div class="card-footer positive">
                                    <i class="fas fa-check-circle"></i> Stok aman
                                </div>
                                @endif
                            </div>
                            <div class="card-icon warning">
                                @if(count($produkMenipis) > 0)
                                <i class="fas fa-exclamation-triangle"></i>
                                @else
                                <i class="fas fa-thumbs-up"></i>
                                @endif
                            </div>
                        </div>
                        @if(count($produkMenipis) > 0)
                        {{-- <div class="card-body">
                            <ul>
                                @foreach($produkMenipis as $produk)
                                <li>{{ $produk->nama }} - Stok tersisa: {{ $produk->stok_tersedia }}</li>
                                @endforeach
                            </ul>
                        </div> --}}
                        @endif
                    </div>

                    {{-- 🔽 KARTU 4: Total Supplier (Dibuat bisa diklik) 🔽 --}}
                    <div class="card card-clickable" wire:click="pindahHalaman('supplier')" style="cursor: pointer;">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Total Supplier</div>
                                <div class="card-value">{{ $this->totalSupplier() }}</div>
                                <div class="card-footer">
                                    <i class="fas fa-info-circle"></i> {{ $this->totalSupplier() }} supplier aktif
                                </div>
                            </div>
                            <div class="card-icon danger">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
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
                            @forelse ($transaksiTerakhir as $transaksi)
                            <tr>
                                {{-- ID Transaksi (Contoh format: TRX-20251020-057) --}}
                                <td>TRX-{{ $transaksi->created_at->format('Ymd') }}-{{
                                    str_pad($transaksi->idpenjualan, 3, '0', STR_PAD_LEFT) }}</td>

                                {{-- Tanggal Transaksi --}}
                                <td>{{ $transaksi->created_at->format('d M Y') }}</td>

                                {{-- Nama Pelanggan (Asumsi ada kolom 'nama_pelanggan' atau relasi) --}}
                                <td>{{ $transaksi->nama_pelanggan ?? 'Umum' }}</td>

                                {{-- Total Harga (Asumsi ada kolom 'total_harga') --}}
                                <td>Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>

                                {{-- Status (Asumsi ada kolom 'status', misal: 'lunas', 'pending') --}}
                                <td>
                                    @if($transaksi->status == 'lunas')
                                    <span class="status-badge success">Selesai</span>
                                    @else
                                    <span class="status-badge warning">{{ ucfirst($transaksi->status) }}</span>
                                    @endif
                                </td>

                                {{-- Tombol Aksi --}}
                                <td>
                                    {{-- 🔽 PERUBAHAN DI SINI 🔽 --}}
                                    <a class="btn btn-sm btn-primary" href="/laporan-penjualan">
                                        <i class="fas fa-eye"></i>Lihat</a>
                                </td>
                            </tr>
                            @empty
                            {{-- Tampilan jika tidak ada transaksi --}}
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">
                                    Belum ada transaksi terbaru.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
<!-- Tambahkan setelah semua HTML dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
    document.addEventListener("livewire:load", () => {
    const ctx = document.getElementById('chartPendapatan');
    let chart;

    // ✅ Render awal waktu halaman pertama kali dibuka
    Livewire.emit('renderChartPendapatan', @json($dataPendapatan));

    Livewire.on('renderChartPendapatan', (data) => {
        if (chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
});
</script> --}}