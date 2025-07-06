<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Export PDF - {{ now()->format('Y-m-d H:i') }}</title>
</head>

<style>
    body {
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
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
        border-bottom: 1px solid #ddd;
        padding-bottom: 6px;
    }

    .logo {
        width: 80px;
        height: auto;
    }

    .title {
        font-size: 14px;
        font-weight: bold;
    }

    .meta {
        font-style: italic;
        font-size: 10px;
    }

    .text-right {
        text-align: right;
    }

    .mt-1 {
        margin-top: 4px;
    }

    .mt-2 {
        margin-top: 8px;
    }
</style>

<body>

    <!-- Header with Logo & Title -->
    <div class="header">
        <div>
            <img src="{{ public_path('images/logo-cafe.png') }}" class="logo" alt="Logo Cafe">
        </div>
        <div class="title">Laporan {{ $title ?? '' }}</div>
        <div class="text-right meta">
            Diunduh oleh: {{ auth()->user()->name ?? 'Admin' }}<br>
            Tanggal: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <!-- Informasi Rentang -->
    @if($tglstart ?? '' && $tglend ?? '')
    <div class="text-right meta mt-1">Range Data: {{ $tglstart }} - {{ $tglend }}</div>
    @endif

    @if($tahun ?? '' && $bulan ?? '')
    <div class="text-right meta mt-1">Tahun: {{ $tahun }} | Bulan: {{ $bulan }}</div>
    @endif

    @if($tglnow ?? '')
    <div class="text-right meta mt-1">Tanggal: {{ $tglnow }}</div>
    @endif

    <!-- Table Data -->
    <table class="mt-2">
        <thead>
            <tr>
                <th style="width: 5%; text-align:center">No</th>
                @foreach ($headers as $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }}</td>
                @foreach ($row as $value)
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>