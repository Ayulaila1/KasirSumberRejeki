<table>
    <tr>
        <th colspan="6">LAPORAN KAS MUTASI {{ $tanggal_awal }} - {{ $tanggal_akhir }}</th>
    </tr>
</table>

<table border="1" style="border-collapse: collapse;">
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Shift</th>
        <th>Jenis</th>
        <th>Nominal</th>
        <th>Keterangan</th>
        <th>Petugas</th>
    </tr>
    @foreach ($kasMutasi as $i => $item)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $item->tanggal }}</td>
        <td>{{ $item->shift }}</td>
        <td>{{ ucfirst($item->jenis) }}</td>
        <td>{{ $item->nominal }}</td>
        <td>{{ $item->keterangan }}</td>
        <td>{{ $item->user }}</td>
    </tr>
    @endforeach
</table>