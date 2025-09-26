<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Export PDF' }} - {{ now()->format('Y-m-d H:i') }}</title>
    <style>
        body {
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table thead {
            background-color: #343a40;
            color: white;
        }

        table th,
        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #343a40;
            padding-bottom: 6px;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            flex: 1;
        }

        .meta {
            font-style: italic;
            font-size: 10px;
            text-align: right;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- Header dengan Logo & Judul -->
    <div class="header">
        <div>
            {{-- Logo cafe, pastikan path benar --}}
            <img src="{{ public_path('images/logo-cafe.png') }}" class="logo" alt="Logo Cafe">
        </div>
        <div class="title">Laporan {{ $title ?? '' }}</div>
        <div class="meta">
            Diunduh oleh: {{ auth()->user()->name ?? 'Admin' }}<br>
            Tanggal: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB
        </div>
    </div>

    <!-- Informasi Rentang/Tanggal -->
    @if(!empty($tglstart) && !empty($tglend))
    <div class="meta mt-1">Periode: {{ $tglstart }} s/d {{ $tglend }}</div>
    @endif

    @if(!empty($tahun) && !empty($bulan))
    <div class="meta mt-1">Tahun: {{ $tahun }} | Bulan: {{ $bulan }}</div>
    @endif

    @if(!empty($tglnow))
    <div class="meta mt-1">Tanggal: {{ $tglnow }}</div>
    @endif

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align:center">No</th>
                @foreach ($headers as $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $row)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }}</td>
                @foreach ($row as $value)
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($headers) + 1 }}" style="text-align:center; font-style:italic;">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>

        {{-- Tambahkan total kalau ada --}}
        @if(!empty($showTotal) && !empty($grandTotal))
        <tfoot>
            <tr>
                <td colspan="{{ count($headers) }}" class="text-right"><strong>Total Penjualan</strong></td>
                <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>

</html>