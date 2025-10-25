<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kas Mutasi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 0;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>LAPORAN KAS MUTASI</h2>
    <p class="text-center">Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }} | Shift: {{ $shift ?? 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->tanggal }}</td>
                <td>{{ $d->shift }}</td>
                <td>{{ ucfirst($d->jenis) }}</td>
                <td class="text-end">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                <td class="text-start">{{ $d->keterangan }}</td>
                <td>{{ $d->user }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <p class="text-center">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
</body>

</html>