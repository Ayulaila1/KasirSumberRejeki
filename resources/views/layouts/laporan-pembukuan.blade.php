<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembukuan - Shift {{ $shift }} - {{ $tanggal }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header h2 {
            margin: 0;
        }

        .info {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #444;
            padding: 5px;
        }

        table th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table td {
            border: none;
            padding: 3px 0;
        }

        .signature {
            margin-top: 30px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            padding-top: 50px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h2>📘 Laporan Pembukuan Keuangan</h2>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }} |
            <strong>Shift:</strong> {{ $shift }}
        </p>
    </div>

    {{-- RINGKASAN --}}
    <table class="summary-table">
        <tr>
            <td><strong>Total Penjualan</strong></td>
            <td class="text-end">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Modal</strong></td>
            <td class="text-end">Rp {{ number_format($total_modal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Laba Kotor</strong></td>
            <td class="text-end">Rp {{ number_format($laba, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kas Masuk</strong></td>
            <td class="text-end text-success">+ Rp {{ number_format($kas_masuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kas Keluar</strong></td>
            <td class="text-end text-danger">- Rp {{ number_format($kas_keluar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kas Seharusnya di Laci</strong></td>
            <td class="text-end fw-bold">Rp {{ number_format($expected_cash, 0, ',', '.') }}</td>
        </tr>
        @if($closure)
        <tr>
            <td><strong>Kas Fisik Saat Tutup Shift</strong></td>
            <td class="text-end">Rp {{ number_format($closure->kas_fisik ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Selisih</strong></td>
            <td class="text-end">
                {{ $closure->selisih >= 0 ? '+' : '-' }} Rp {{ number_format(abs($closure->selisih ?? 0), 0, ',', '.')
                }}
            </td>
        </tr>
        <tr>
            <td><strong>Ditutup Oleh</strong></td>
            <td class="text-end">{{ $closure->user_name ?? '-' }}</td>
        </tr>
        @endif
    </table>

    {{-- DETAIL PENJUALAN --}}
    <h4 style="margin-bottom: 5px;">📦 Detail Barang Terjual</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Subtotal</th>
                <th>Laba Kotor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualan as $key => $p)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $p->nama }}</td>
                <td class="text-end">{{ number_format($p->qty, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p->subtotal, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p->laba_kotor, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada penjualan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- DETAIL PENGELUARAN --}}
    <h4 style="margin-bottom: 5px;">💸 Detail Pengeluaran</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengeluaran as $key => $pg)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $pg->nama }}</td>
                <td class="text-end">{{ number_format($pg->jumlah, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($pg->harga, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($pg->total, 0, ',', '.') }}</td>
                <td>{{ $pg->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Tidak ada pengeluaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="signature">
        <tr>
            <td>Kasir</td>
            <td>Supervisor/Admin</td>
        </tr>
        <tr>
            <td>_________________________</td>
            <td>_________________________</td>
        </tr>
    </table>

</body>

</html>