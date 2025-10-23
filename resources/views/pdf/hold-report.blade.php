<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi Hold</title>
    <style>
        body {
            font-family: 'sans-serif';
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .ps-3 {
            padding-left: 1rem;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .header-info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .group-header {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h1>Laporan Transaksi Hold</h1>
    <div class="header-info">
        <strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th class="text-center">Kode Transaksi</th>
                <th class="text-center">Total</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($groupedHolds as $customerName => $holds)

            <tr class="group-header">
                <td colspan="4" class="fw-bold ps-3">
                    Customer: {{ $customerName }}
                </td>
            </tr>

            @foreach($holds as $hold)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td class="text-center">{{ $hold->kode_transaksi }}</td>
                <td class="text-end">Rp {{ number_format($hold->total, 0, ',', '.') }}</td>
                <td class="text-center">{{ $hold->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data transaksi hold.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
