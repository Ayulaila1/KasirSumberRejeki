{{-- resources/views/livewire/partials/receipt.blade.php --}}

@if($printerType === 'bluetooth')
{{-- ====== STRUK PELANGGAN ====== --}}
<b>{{ $storeName }}</b>
<br>{{ $storeAddress }}
<br>Telp: {{ $storePhone }}
<br>==============================
<br>No. Trx : {{ $number }}
<br>Tanggal : {{ $date }}
<br>Meja : {{ $table }}
<br>Pelanggan: {{ $customer }}
<br>------------------------------
@foreach($items as $item)
{{ $item['name'] }}
<br> {{ $item['quantity'] }} x Rp {{ number_format($item['price'],0,',','.') }} = Rp {{
number_format($item['price']*$item['quantity'],0,',','.') }}
<br>
@endforeach
<br>------------------------------
<br>TOTAL : Rp {{ number_format($total,0,',','.') }}
<br>Bayar : Rp {{ number_format($cash,0,',','.') }}
<br>Kembali: Rp {{ number_format($change,0,',','.') }}
<br>==============================
Terima Kasih
<br>Semoga Puas dengan Layanan Kami

@else
{{-- ====== STRUK DAPUR ====== --}}
<b>*** PESANAN DAPUR ***</b>
<br>Meja : {{ $table }}
<br>Pelanggan: {{ $customer }}
<br>------------------------------
@foreach($items as $item)
{{ $item['name'] }} ({{ $item['quantity'] }})
<br>
@endforeach
<br>Catatan: {{ $notes ?? '-' }}
<br>==============================
@endif