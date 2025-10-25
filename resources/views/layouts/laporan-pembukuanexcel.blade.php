<table>
    <tr>
        <th colspan="2" style="font-size:16px; text-align:center;">
            LAPORAN PEMBUKUAN KEUANGAN
        </th>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td>Shift</td>
        <td>{{ $shift }}</td>
    </tr>
</table>

<br>

<table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <th>Keterangan</th>
        <th>Jumlah (Rp)</th>
    </tr>
    <tr>
        <td>Total Penjualan</td>
        <td>{{ number_format($total_penjualan,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Total Modal</td>
        <td>{{ number_format($total_modal,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Laba Kotor</td>
        <td>{{ number_format($laba,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Kas Masuk</td>
        <td>{{ number_format($kas_masuk,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Kas Keluar</td>
        <td>{{ number_format($kas_keluar,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Kas Seharusnya di Laci</td>
        <td>{{ number_format($expected_cash,0,',','.') }}</td>
    </tr>
    @if($closure)
    <tr>
        <td>Kas Fisik</td>
        <td>{{ number_format($closure->kas_fisik,0,',','.') }}</td>
    </tr>
    <tr>
        <td>Selisih</td>
        <td>{{ number_format($closure->selisih,0,',','.') }}</td>
    </tr>
    @endif
</table>

<br><br>

{{-- DETAIL PENJUALAN --}}
<table border="1" cellspacing="0" cellpadding="4">
    <tr style="background:#ddd;">
        <th colspan="7">Detail Barang Terjual</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Qty</th>
        <th>Harga Beli</th>
        <th>Harga Jual</th>
        <th>Subtotal</th>
        <th>Laba Kotor</th>
    </tr>
    @foreach($penjualan as $key => $p)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->qty }}</td>
        <td>{{ $p->harga_beli }}</td>
        <td>{{ $p->harga_jual }}</td>
        <td>{{ $p->subtotal }}</td>
        <td>{{ $p->laba_kotor }}</td>
    </tr>
    @endforeach
</table>

<br><br>

{{-- DETAIL PENGELUARAN --}}
<table border="1" cellspacing="0" cellpadding="4">
    <tr style="background:#ddd;">
        <th colspan="6">Detail Pengeluaran</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Nama Bahan</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Total</th>
        <th>Keterangan</th>
    </tr>
    @foreach($pengeluaran as $key => $pg)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $pg->nama }}</td>
        <td>{{ $pg->jumlah }}</td>
        <td>{{ $pg->harga }}</td>
        <td>{{ $pg->total }}</td>
        <td>{{ $pg->keterangan }}</td>
    </tr>
    @endforeach
</table>