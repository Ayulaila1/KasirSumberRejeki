<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Calibri', sans-serif;
        font-size: 12px;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 6px 8px;
    }

    th {
        background-color: #f3f6fb;
        font-weight: bold;
        text-align: center;
    }

    .no-border td {
        border: none !important;
    }

    .section-title {
        background-color: #cfe2f3;
        font-weight: bold;
        text-align: left;
        padding: 8px;
    }

    .summary th {
        background-color: #e2efda;
    }

    .summary td {
        text-align: right;
    }

    .highlight {
        background-color: #fce4d6;
        font-weight: bold;
    }

    .subheader {
        background-color: #d9e1f2;
        font-weight: bold;
        text-align: left;
    }
</style>

{{-- === HEADER LAPORAN === --}}
<table class="no-border">
    <tr>
        <td colspan="6" style="font-size:16px; font-weight:bold; text-align:center; border:none;">
            LAPORAN PEMBUKUAN KEUANGAN
        </td>
    </tr>
    <tr>
        <td colspan="6" style="height:6px;"></td>
    </tr>
    <tr>
        <td style="font-weight:bold; width:100px;">Tanggal</td>
        <td>{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</td>
        <td></td>
        <td style="font-weight:bold;">Shift</td>
        <td colspan="2">{{ $shift }}</td>
    </tr>
</table>

<br>

{{-- === RINGKASAN === --}}
<table class="summary">
    <thead>
        <tr class="subheader">
            <th style="text-align:left;">Keterangan</th>
            <th style="text-align:right;">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Penjualan</td>
            <td>{{ number_format($total_penjualan,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Total Modal</td>
            <td>{{ number_format($total_modal,0,',','.') }}</td>
        </tr>
        <tr class="highlight">
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
        <tr style="background-color:#fff2cc;">
            <td><strong>Kas Seharusnya di Laci</strong></td>
            <td><strong>{{ number_format($expected_cash,0,',','.') }}</strong></td>
        </tr>
        @if($closure)
        <tr>
            <td>Kas Fisik</td>
            <td>{{ number_format($closure->kas_fisik ?? 0,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Selisih</td>
            <td>{{ number_format($closure->selisih ?? 0,0,',','.') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<br>

{{-- === DETAIL PENJUALAN === --}}
<table>
    <thead>
        <tr class="section-title">
            <th colspan="7">📦 Detail Barang Terjual</th>
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
    </thead>
    <tbody>
        @forelse($penjualan as $key => $p)
        <tr>
            <td style="text-align:center;">{{ $key+1 }}</td>
            <td>{{ $p->nama }}</td>
            <td style="text-align:right;">{{ number_format($p->qty,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($p->harga_beli,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($p->harga_jual,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($p->subtotal,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($p->laba_kotor,0,',','.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center; color:#777;">Tidak ada data penjualan.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<br>

{{-- === DETAIL PENGELUARAN === --}}
<table>
    <thead>
        <tr class="section-title">
            <th colspan="6">💸 Detail Pengeluaran</th>
        </tr>
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
        @forelse($pengeluaran as $key => $pg)
        <tr>
            <td style="text-align:center;">{{ $key+1 }}</td>
            <td>{{ $pg->nama ?? $pg->bahan_nama ?? '-' }}</td>
            <td style="text-align:right;">{{ number_format($pg->jumlah ?? 0,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($pg->harga ?? 0,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($pg->total ?? 0,0,',','.') }}</td>
            <td>{{ $pg->keterangan ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center; color:#777;">Tidak ada data pengeluaran.</td>
        </tr>
        @endforelse
    </tbody>
</table>