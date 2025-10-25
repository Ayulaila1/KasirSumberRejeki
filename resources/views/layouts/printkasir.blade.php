<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 10px;
            white-space: pre-wrap;
        }

        b {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    @if($printerType === 'bluetooth')
    {{-- ====== STRUK PELANGGAN ====== --}}
    <div class="center">
        <b>{{ $storeName ?? 'Nama Toko' }}</b><br>
        {{ $storeAddress ?? '-' }}<br>
        Telp: {{ $storePhone ?? '-' }}<br>
        ==============================<br>
    </div>

    No. Trx : {{ $number ?? '-' }}<br>
    Tanggal : {{ $date ?? now()->format('d/m/Y H:i') }}<br>
    Meja : {{ $table ?? '-' }}<br>
    Pelanggan: {{ $customer ?? '-' }}<br>
    ------------------------------<br>

    @foreach($items as $item)
    {{ $item['name'] }}<br>
    {{ $item['quantity'] }} x Rp {{ number_format($item['price'],0,',','.') }}
    = Rp {{ number_format($item['price']*$item['quantity'],0,',','.') }}<br>
    @endforeach

    ------------------------------<br>
    TOTAL : Rp {{ number_format($total ?? 0,0,',','.') }}<br>
    Bayar : Rp {{ number_format($cash ?? 0,0,',','.') }}<br>
    Kembali: Rp {{ number_format($change ?? 0,0,',','.') }}<br>
    ==============================<br>
    <div class="center">
        Terima Kasih<br>
        Semoga Puas dengan Layanan Kami
        <div class="center" style="margin-top:8px;">
            Dicetak oleh: {{ Auth::user()->name ?? 'Kasir' }} (Shift {{ Auth::user()->shift ?? '-' }})
        </div>
    </div>

    @else
    {{-- ====== STRUK DAPUR ====== --}}
    <div class="center">
        <b>*** PESANAN DAPUR ***</b><br>
        ==============================<br>
    </div>
    Meja : {{ $table ?? '-' }}<br>
    Pelanggan: {{ $customer ?? '-' }}<br>
    Kasir: {{ Auth::user()->name ?? 'Kasir' }} (Shift {{ Auth::user()->shift ?? '-' }})<br>
    ------------------------------<br>
    @foreach($items as $item)
    {{ $item['name'] }} ({{ $item['quantity'] }})<br>
    @endforeach
    ------------------------------<br>
    Catatan: {{ $notes ?? '-' }}<br>
    ==============================<br>
    @endif
</body>

</html>