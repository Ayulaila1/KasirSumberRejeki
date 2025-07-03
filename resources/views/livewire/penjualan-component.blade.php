<div class="pos-container">
    <!-- Product Section -->
    <div class="product-section">
        <div class="header">
            <h2><i class="fas fa-mug-hot"></i> Menu Cafe Suki</h2>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari menu...">
            </div>
        </div>

        <div class="category-tabs">
            <div class="category-tab {{ $currentCategory === 'Semua' ? 'active' : '' }}"
                wire:click="filterByCategory('Semua')">Semua</div>
            <div class="category-tab {{ $currentCategory === 'Minuman' ? 'active' : '' }}"
                wire:click="filterByCategory('Minuman')">
                <i class="fas fa-coffee"></i> Minuman
            </div>
            <div class="category-tab {{ $currentCategory === 'Makanan' ? 'active' : '' }}"
                wire:click="filterByCategory('Makanan')">
                <i class="fas fa-utensils"></i> Makanan
            </div>
            <div class="category-tab {{ $currentCategory === 'Snack' ? 'active' : '' }}"
                wire:click="filterByCategory('Snack')">
                <i class="fas fa-cookie"></i> Snack
            </div>
            <div class="category-tab {{ $currentCategory === 'Promo' ? 'active' : '' }}"
                wire:click="filterByCategory('Promo')">
                <i class="fas fa-tag"></i> Promo
            </div>
        </div>

        <div class="product-grid">
            @foreach($filteredProducts as $product)
            <div class="product-card" wire:click="addToCart({{ $product['id'] }})" data-id="{{ $product['id'] }}"
                data-category="{{ $product['category'] }}">
                <div class="product-image">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                    @if($product['stok'] <= 0) <div class="product-badge" style="background-color: var(--danger);">Habis
                </div>
                @endif
            </div>
            <div class="product-info">
                <div class="product-name">{{ $product['name'] }}</div>
                <div class="product-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                @if($product['stok'] > 0)
                <div style="font-size: 11px; color: #666;">Stok: {{ $product['stok'] }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Cart Section -->
<div class="cart-section">
    <div class="cart-header">
        <h2><i class="fas fa-shopping-cart"></i> Pesanan</h2>
    </div>

    <div class="cart-body">
        <div class="customer-info">
            <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
            <input wire:model="tableNumber" type="text" class="customer-input" placeholder="Nomor Meja">
            <input wire:model="customerName" type="text" class="customer-input" placeholder="Nama Pelanggan (Opsional)">
        </div>

        <div class="discount-section">
            <h4><i class="fas fa-tag"></i> Diskon</h4>
            <div class="discount-input">
                <input wire:model="discountCode" type="text" placeholder="Kode diskon">
                <button wire:click="applyDiscount">Terapkan</button>
            </div>
            @if($discount > 0)
            <div style="margin-top: 10px; color: var(--success); font-size: 13px;">
                Diskon {{ $discountType === 'percentage' ? $discount.'%' : 'Rp '.number_format($discount, 0, ',', '.')
                }} diterapkan
            </div>
            @endif
        </div>

        <div class="payment-methods-section">
            <h4><i class="fas fa-credit-card"></i> Metode Pembayaran</h4>
            <div class="payment-methods">
                <div class="payment-method {{ $selectedPaymentMethod === 'cash' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('cash')">
                    <i class="fas fa-money-bill-wave"></i>
                    <div>Tunai</div>
                </div>
                <div class="payment-method {{ $selectedPaymentMethod === 'debit' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('debit')">
                    <i class="fas fa-credit-card"></i>
                    <div>Kartu Debit</div>
                </div>
                <div class="payment-method {{ $selectedPaymentMethod === 'credit' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('credit')">
                    <i class="far fa-credit-card"></i>
                    <div>Kartu Kredit</div>
                </div>
                <div class="payment-method {{ $selectedPaymentMethod === 'qris' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('qris')">
                    <i class="fas fa-qrcode"></i>
                    <div>QRIS</div>
                </div>
                <div class="payment-method {{ $selectedPaymentMethod === 'ewallet' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('ewallet')">
                    <i class="fas fa-wallet"></i>
                    <div>E-Wallet</div>
                </div>
                <div class="payment-method {{ $selectedPaymentMethod === 'transfer' ? 'active' : '' }}"
                    wire:click="selectPaymentMethod('transfer')">
                    <i class="fas fa-exchange-alt"></i>
                    <div>Transfer</div>
                </div>
            </div>

            @if($selectedPaymentMethod === 'cash')
            <div class="change-section">
                <h5><i class="fas fa-calculator"></i> Pembayaran Tunai</h5>
                <div class="cash-input">
                    <input wire:model="cashAmount" type="number" placeholder="Jumlah uang">
                    <button wire:click="calculateChange">Hitung</button>
                </div>
                @if($cashAmount > 0)
                <div style="margin-top: 10px; font-size: 14px;">
                    @if($change !== false)
                    <strong>Kembalian:</strong> Rp {{ number_format($change, 0, ',', '.') }}
                    @else
                    <strong style="color: var(--danger)">Uang kurang:</strong> Rp {{ number_format($getTotalAmount() -
                    $cashAmount, 0, ',', '.') }}
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="notes-section">
            <h4><i class="fas fa-sticky-note"></i> Catatan</h4>
            <textarea wire:model="orderNotes"
                placeholder="Catatan untuk pesanan (contoh: pedas, tidak pakai bawang, dll)"></textarea>
        </div>

        <!-- Cart Items -->
        <div id="cart-items">
            @if(count($cart) === 0)
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Belum ada pesanan</p>
                <p style="font-size: 14px; margin-top: 5px;">Klik item menu untuk menambahkan ke keranjang</p>
            </div>
            @else
            @foreach($cart as $item)
            <div class="cart-item" wire:key="cart-item-{{ $item['id'] }}">
                <div class="cart-item-info">
                    <div class="cart-item-name">{{ $item['name'] }}</div>
                    <div class="cart-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="cart-item-controls">
                    <button class="quantity-btn minus" wire:click="updateQuantity({{ $item['id'] }}, -1)">-</button>
                    <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1"
                        wire:change="setQuantity({{ $item['id'] }}, $event.target.value)">
                    <button class="quantity-btn plus" wire:click="updateQuantity({{ $item['id'] }}, 1)">+</button>
                    <button class="remove-btn" wire:click="removeFromCart({{ $item['id'] }})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="cart-summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Diskon:</span>
            <span>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Pajak (10%):</span>
            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total-row">
            <span>Total:</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <div class="action-buttons">
            <button class="btn btn-secondary" wire:click="clearCart" @if(count($cart)===0) disabled @endif>
                <i class="fas fa-trash"></i> Kosongkan
            </button>
            <button class="btn btn-warning" wire:click="holdOrder" @if(count($cart)===0) disabled @endif>
                <i class="fas fa-pause"></i> Hold
            </button>
            <button class="btn btn-success" wire:click="processPayment" @if(count($cart)===0) disabled @endif>
                <i class="fas fa-print"></i> Bayar
            </button>
        </div>
    </div>
</div>
</div>

<!-- Notification badge for held orders -->
@if(count($heldOrders) > 0)
<div class="notification-badge" wire:click="$dispatch('show-held-orders')">
    <i class="fas fa-pause"></i>
    <span
        style="position: absolute; font-size: 12px; bottom: -5px; right: -5px; background: var(--danger); width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
        {{ count($heldOrders) }}
    </span>
</div>
@endif

<!-- Held Orders Modal -->
<div class="receipt-modal" x-data="{ showHeldOrders: false }" x-show="showHeldOrders"
    @show-held-orders.window="showHeldOrders = true" @close-held-orders.window="showHeldOrders = false"
    style="display: none;">
    <div class="receipt-content" style="max-width: 500px;">
        <div class="close-receipt" @click="$dispatch('close-held-orders')">&times;</div>
        <div class="receipt-header">
            <h3><i class="fas fa-pause"></i> Pesanan Tertahan</h3>
            <p>Daftar pesanan yang sedang dihold</p>
        </div>

        <div id="held-orders-list" style="max-height: 60vh; overflow-y: auto;">
            @foreach($heldOrders as $index => $order)
            <div class="held-order" wire:click="loadHeldOrder({{ $index }})"
                style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: all 0.3s;"
                @mouseenter="this.style.background = 'rgba(122, 75, 71, 0.05)'"
                @mouseleave="this.style.background = ''">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <strong>{{ $order['customer'] ? $order['customer'] : 'Pelanggan' }} - Meja {{ $order['table']
                        }}</strong>
                    <span style="color: #666; font-size: 13px;">{{ $order['time'] }}</span>
                </div>
                <div style="font-size: 13px; color: #666; margin-bottom: 5px;">
                    {{ count($order['items']) }} item - Rp {{ number_format($order['subtotal'], 0, ',', '.') }}
                </div>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    @foreach(array_slice($order['items'], 0, 3) as $item)
                    <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">
                        {{ $item['name'] }} x{{ $item['quantity'] }}
                    </span>
                    @endforeach
                    @if(count($order['items']) > 3)
                    <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">
                        +{{ count($order['items']) - 3 }} lagi
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="receipt-footer">
            <p>Klik pesanan untuk memuatnya kembali ke keranjang</p>
        </div>
    </div>
</div>

<!-- Customer Receipt Modal -->
<div class="receipt-modal" wire:ignore.self x-data="{ showReceipt: @entangle('showReceipt') }" x-show="showReceipt"
    style="display: none;">
    <div class="receipt-content">
        <div class="close-receipt" @click="showReceipt = false; $wire.closeReceipt()">&times;</div>
        <div class="receipt-header">
            <img src="{{ asset('images/logo.png') }}" alt="Cafe Suki" style="max-width: 180px;">
            <h3>Struk Pembayaran</h3>
            <p>Jl. Contoh No. 123, Kota</p>
            <p>Telp: 08123456789</p>
        </div>

        <div class="receipt-details">
            <div><strong>No. Transaksi:</strong> {{ $receiptData['number'] ?? '' }}</div>
            <div><strong>Tanggal:</strong> {{ $receiptData['date'] ?? '' }}</div>
            <div><strong>Pelanggan:</strong> {{ $receiptData['customer'] ?? '' }}</div>
            <div><strong>Kasir:</strong> {{ $receiptData['cashier'] ?? '' }}</div>
        </div>

        <div class="receipt-items">
            <div style="border-bottom: 1px dashed #333; padding-bottom: 5px; margin-bottom: 5px;">
                <div style="display: flex; justify-content: space-between;">
                    <div><strong>Item</strong></div>
                    <div><strong>Total</strong></div>
                </div>
            </div>
            <div id="receipt-items-list">
                @if(isset($receiptData['items']))
                @foreach($receiptData['items'] as $item)
                <div class="receipt-item">
                    <div>{{ $item['name'] }} x{{ $item['quantity'] }}</div>
                    <div>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="receipt-total">
            <div style="display: flex; justify-content: space-between;">
                <div>Subtotal:</div>
                <div>Rp {{ number_format($receiptData['subtotal'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div>Diskon:</div>
                <div>- Rp {{ number_format($receiptData['discount'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div>Pajak (10%):</div>
                <div>Rp {{ number_format($receiptData['tax'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                <div>Total:</div>
                <div>Rp {{ number_format($receiptData['total'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <div>Pembayaran:</div>
                <div>{{ $receiptData['payment_method'] ?? '' }}</div>
            </div>
            @if($selectedPaymentMethod === 'cash')
            <div style="display: flex; justify-content: space-between;">
                <div>Tunai:</div>
                <div>Rp {{ number_format($receiptData['cash'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                <div>Kembalian:</div>
                <div>Rp {{ number_format($receiptData['change'] ?? 0, 0, ',', '.') }}</div>
            </div>
            @endif
        </div>

        <div class="receipt-footer">
            <p>Terima kasih telah berkunjung ke Cafe Suki</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>
</div>

<!-- Kitchen Receipt Modal -->
<div class="receipt-modal" wire:ignore.self x-data="{ showKitchenReceipt: @entangle('showKitchenReceipt') }"
    x-show="showKitchenReceipt" style="display: none;">
    <div class="receipt-content">
        <div class="close-receipt" @click="showKitchenReceipt = false; $wire.closeReceipt()">&times;</div>
        <div class="receipt-header">
            <img src="{{ asset('images/logo.png') }}" alt="Cafe Suki" style="max-width: 180px;">
            <h3>Order Dapur</h3>
            <p>No. {{ $kitchenReceiptData['number'] ?? '' }}</p>
            <p>Meja: {{ $kitchenReceiptData['table'] ?? '' }}</p>
        </div>

        <div class="receipt-details">
            <div><strong>Waktu:</strong> {{ $kitchenReceiptData['time'] ?? '' }}</div>
            <div><strong>Pelanggan:</strong> {{ $kitchenReceiptData['customer'] ?? '-' }}</div>
            <div><strong>Catatan:</strong> {{ $kitchenReceiptData['notes'] ?? '-' }}</div>
        </div>

        <div class="receipt-items">
            <div style="border-bottom: 1px dashed #333; padding-bottom: 5px; margin-bottom: 5px;">
                <div style="display: flex; justify-content: space-between;">
                    <div><strong>Item</strong></div>
                    <div><strong>Qty</strong></div>
                </div>
            </div>
            <div id="kitchen-receipt-items-list">
                @if(isset($kitchenReceiptData['items']))
                @foreach($kitchenReceiptData['items'] as $item)
                <div class="receipt-item">
                    <div>{{ $item['name'] }}</div>
                    <div>x{{ $item['quantity'] }}</div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="receipt-footer">
            <p>Harap segera diproses</p>
            <p>Terima kasih</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Print receipts when shown
        Livewire.on('showReceipt', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    });
</script>
@endpush