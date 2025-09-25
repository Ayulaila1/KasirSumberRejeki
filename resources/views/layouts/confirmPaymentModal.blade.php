@if($showConfirmModal)
<div class="modal d-flex justify-content-center align-items-center" style="display:flex; position:fixed; top:0; left:0; width:100%; height:100%; 
           background:rgba(0,0,0,0.4); z-index:999;">
    <div class="modal-content bg-white p-4 rounded-4 shadow-lg" style="width:90%; max-width:420px; font-size:14px;">

        <!-- Header -->
        <h5 class="mb-3 fw-bold text-center text-success">
            💵 Konfirmasi Pembayaran
        </h5>

        <!-- Info pelanggan -->
        <div class="p-2 rounded bg-light mb-3">
            <div><strong>No. Meja:</strong> {{ $tableNumber ?: '-' }}</div>
            <div><strong>Nama Pelanggan:</strong> {{ $customerName ?: '-' }}</div>
            <div><strong>Catatan:</strong> {{ $notes ?: '-' }}</div>
        </div>

        <!-- Daftar item -->
        <div class="border-top pt-2 mt-2">
            @foreach($cart as $item)
            <div class="d-flex justify-content-between mb-1">
                <div>{{ $item['name'] }} <span class="text-muted">x {{ $item['quantity'] }}</span></div>
                <div class="fw-semibold text-end">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.')
                    }}</div>
            </div>
            @endforeach
        </div>

        <!-- Ringkasan pembayaran -->
        <div class="border-top pt-3 mt-3">
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">Total:</span>
                <span class="fw-bold text-success">Rp {{ number_format($this->getTotal(), 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Tunai:</span>
                <span class="text-end">Rp {{ number_format($cashAmount, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Kembalian:</span>
                <span class="fw-semibold text-primary text-end">
                    Rp {{ number_format(max($cashAmount - $this->getTotal(), 0), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Tombol aksi -->
        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-secondary px-4 rounded-3" wire:click="$set('showConfirmModal', false)">
                ❌ Batal
            </button>
            <button class="btn btn-success px-4 rounded-3" wire:click="confirmPayment" wire:loading.attr="disabled"
                wire:target="confirmPayment">
                <!-- Default -->
                <span wire:loading.remove wire:target="confirmPayment">✅ Konfirmasi</span>
                <!-- Saat loading -->
                <span wire:loading wire:target="confirmPayment">⏳ Memproses...</span>
            </button>
        </div>
    </div>
</div>
@endif