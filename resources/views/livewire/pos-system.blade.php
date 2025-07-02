<div>
    <!-- Include CSS -->
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <div class="pos-container">
        <!-- Product Section -->
        <div class="product-section">
            <div class="header">
                <h2><i class="fas fa-mug-hot"></i> Menu Cafe Suki</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari menu..." wire:model.debounce.300ms="searchTerm">
                </div>
            </div>

            <div class="category-tabs">
                <div class="category-tab {{ $currentCategory === 'Semua' ? 'active' : '' }}"
                    wire:click="filterByCategory('Semua')">Semua</div>
                <div class="category-tab {{ $currentCategory === 'Minuman' ? 'active' : '' }}"
                    wire:click="filterByCategory('Minuman')"><i class="fas fa-coffee"></i> Minuman</div>
                <div class="category-tab {{ $currentCategory === 'Makanan' ? 'active' : '' }}"
                    wire:click="filterByCategory('Makanan')"><i class="fas fa-utensils"></i> Makanan</div>
                <div class="category-tab {{ $currentCategory === 'Snack' ? 'active' : '' }}"
                    wire:click="filterByCategory('Snack')"><i class="fas fa-cookie"></i> Snack</div>
                <div class="category-tab {{ $currentCategory === 'Promo' ? 'active' : '' }}"
                    wire:click="filterByCategory('Promo')"><i class="fas fa-tag"></i> Promo</div>
            </div>

            <div class="product-grid">
                @foreach($filteredProducts as $product)
                <div class="product-card" wire:click="addToCart({{ $product['id'] }})" data-id="{{ $product['id'] }}"
                    data-category="{{ $product['category'] }}">
                    <div class="product-image">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $product['name'] }}</div>
                        <div class="product-price">
                            @isset($product['originalPrice'])
                            <span style="text-decoration: line-through; color: #999; font-size: 13px;">{{
                                formatCurrency($product['originalPrice']) }}</span>
                            @endisset
                            {{ formatCurrency($product['price']) }}
                        </div>
                    </div>
                    @isset($product['isNew'])
                    <div class="product-badge">New!</div>
                    @endisset
                    @isset($product['isHot'])
                    <div class="product-badge">Hot!</div>
                    @endisset
                    @isset($product['isPromo'])
                    <div class="product-badge">Promo</div>
                    @endisset
                    @isset($product['isCombo'])
                    <div class="product-badge">Combo</div>
                    @endisset
                    @isset($product['discount'])
                    <div class="product-badge">-{{ $product['discount'] }}</div>
                    @endisset
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
                    <input type="text" class="customer-input" placeholder="Nomor Meja" wire:model="tableNumber">
                    <input type="text" class="customer-input" placeholder="Nama Pelanggan (Opsional)"
                        wire:model="customerName">
                </div>

                <div class="discount-section">
                    <h4><i class="fas fa-tag"></i> Diskon</h4>
                    <div class="discount-input">
                        <input type="text" wire:model="discountCode" placeholder="Kode diskon">
                        <button wire:click="applyDiscount">Terapkan</button>
                    </div>
                    @if($showDiscountInfo)
                    <div
                        style="margin-top: 10px; color: {{ str_contains($discountInfo, 'tidak valid') ? 'var(--danger)' : 'var(--success)' }}; font-size: 13px;">
                        {{ $discountInfo }}
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
                            <input type="number" wire:model="cashAmount" placeholder="Jumlah uang">
                            <button wire:click="calculateChange">Hitung</button>
                        </div>
                        @if($changeResult)
                        <div style="margin-top: 10px; font-size: 14px;">
                            {{ $changeResult }}
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
                    @php
                    $product = collect($products)->firstWhere('id', $item['id']);
                    $itemPrice = $product['originalPrice'] ?? $product['price'];
                    @endphp
                    <div class="cart-item" data-id="{{ $item['id'] }}">
                        <div class="cart-item-info">
                            <div class="cart-item-name">{{ $product['name'] }}</div>
                            <div class="cart-item-price">{{ formatCurrency($itemPrice) }}</div>
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn minus"
                                wire:click="updateQuantity({{ $item['id'] }}, -1)">-</button>
                            <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1"
                                wire:change="setQuantity({{ $item['id'] }, $event.target.value)">
                            <button class="quantity-btn plus"
                                wire:click="updateQuantity({{ $item['id'] }}, 1)">+</button>
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
                    <span id="subtotal">{{ formatCurrency($cartSummary['subtotal']) }}</span>
                </div>
                <div class="summary-row">
                    <span>Diskon:</span>
                    <span id="discount-amount">{{ formatCurrency($cartSummary['discount']) }}</span>
                </div>
                <div class="summary-row">
                    <span>Pajak (10%):</span>
                    <span id="tax">{{ formatCurrency($cartSummary['tax']) }}</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total:</span>
                    <span id="total">{{ formatCurrency($cartSummary['total']) }}</span>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-secondary" wire:click="clearCart">
                        <i class="fas fa-trash"></i> Kosongkan
                    </button>
                    <button class="btn btn-warning" wire:click="holdOrder">
                        <i class="fas fa-pause"></i> Hold
                    </button>
                    <button class="btn btn-success" wire:click="processPayment">
                        <i class="fas fa-print"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification badge for held orders -->
    @if(count($heldOrders) > 0)
    <div class="notification-badge" id="heldOrdersBadge" wire:click="$set('showHeldOrdersModal', true)">
        <i class="fas fa-pause"></i>
        <span id="heldOrdersCount"
            style="position: absolute; font-size: 12px; bottom: -5px; right: -5px; background: var(--danger); width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            {{ count($heldOrders) }}
        </span>
    </div>
    @endif

    <!-- Held Orders Modal -->
    @if($showHeldOrdersModal)
    <div class="receipt-modal">
        <div class="receipt-content" style="max-width: 500px;">
            <div class="close-receipt" wire:click="$set('showHeldOrdersModal', false)">&times;</div>
            <div class="receipt-header">
                <h3><i class="fas fa-pause"></i> Pesanan Tertahan</h3>
                <p>Daftar pesanan yang sedang dihold</p>
            </div>

            <div id="held-orders-list" style="max-height: 60vh; overflow-y: auto;">
                @foreach($heldOrders as $index => $order)
                <div class="held-order" wire:click="loadHeldOrder({{ $index }})"
                    style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: all 0.3s;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <strong>{{ $order['customer'] ? $order['customer'] : 'Pelanggan' }} - Meja {{ $order['table']
                            }}</strong>
                        <span style="color: #666; font-size: 13px;">{{ $order['time'] }}</span>
                    </div>
                    <div style="font-size: 13px; color: #666; margin-bottom: 5px;">
                        {{ count($order['items']) }} item - {{ formatCurrency($order['subtotal']) }}
                    </div>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        @foreach(array_slice($order['items'], 0, 3) as $item)
                        @php
                        $product = collect($products)->firstWhere('id', $item['id']);
                        @endphp
                        <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">
                            {{ $product['name'] }} x{{ $item['quantity'] }}
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
    @endif

    <!-- Receipt Modal -->
    @if($showReceiptModal)
    <div class="receipt-modal">
        <div class="receipt-content">
            <div class="close-receipt" wire:click="closeReceipt">&times;</div>
            <div class="receipt-header">
                <img src="https://via.placeholder.com/200x60?text=Cafe+Suki&font=poppins" alt="Cafe Suki">
                <h3>Struk Pembayaran</h3>
                <p>Jl. Contoh No. 123, Kota</p>
                <p>Telp: 08123456789</p>
            </div>

            <div class="receipt-details">
                <div><strong>No. Transaksi:</strong> {{ $receiptData['number'] }}</div>
                <div><strong>Tanggal:</strong> {{ $receiptData['date'] }}</div>
                <div><strong>Pelanggan:</strong> {{ $receiptData['customer'] }}</div>
                <div><strong>Kasir:</strong> {{ $receiptData['cashier'] }}</div>
            </div>

            <div class="receipt-items">
                <div style="border-bottom: 1px dashed #333; padding-bottom: 5px; margin-bottom: 5px;">
                    <div style="display: flex; justify-content: space-between;">
                        <div><strong>Item</strong></div>
                        <div><strong>Total</strong></div>
                    </div>
                </div>
                @foreach($receiptData['items'] as $item)
                <div class="receipt-item">
                    <div>{{ $item['name'] }} x{{ $item['quantity'] }}</div>
                    <div>{{ formatCurrency($item['total']) }}</div>
                </div>
                @endforeach
            </div>

            <div class="receipt-total">
                <div style="display: flex; justify-content: space-between;">
                    <div>Subtotal:</div>
                    <div>{{ formatCurrency($receiptData['subtotal']) }}</div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Diskon:</div>
                    <div>- {{ formatCurrency($receiptData['discount']) }}</div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Pajak (10%):</div>
                    <div>{{ formatCurrency($receiptData['tax']) }}</div>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <div>Total:</div>
                    <div>{{ formatCurrency($receiptData['total']) }}</div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div>Pembayaran:</div>
                    <div>{{ $receiptData['paymentMethod'] }}</div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Tunai:</div>
                    <div>{{ $receiptData['cash'] ? formatCurrency($receiptData['cash']) : '-' }}</div>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <div>Kembalian:</div>
                    <div>{{ $receiptData['change'] ? formatCurrency($receiptData['change']) : '-' }}</div>
                </div>
            </div>

            <div class="receipt-footer">
                <p>Terima kasih telah berkunjung ke Cafe Suki</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScript -->
    <script>
        // Format currency helper (available globally)
        function formatCurrency(amount) {
            return "Rp " + amount.toLocaleString("id-ID");
        }

        // Listen for Livewire events
        document.addEventListener('livewire:load', function() {
            // Show alert notification
            Livewire.on('showAlert', (message, type) => {
                const alert = document.createElement('div');
                alert.innerHTML = `
                    <div style="position: fixed; bottom: 20px; left: 20px; background: var(--${type}); color: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 100; animation: fadeInRight 0.3s ease;">
                        ${message}
                    </div>
                `;
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.style.animation = 'fadeOutRight 0.3s ease';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 3000);
            });

            // Cart updated animation
            Livewire.on('cartUpdated', () => {
                const cartItems = document.getElementById('cart-items');
                if (cartItems) {
                    cartItems.style.animation = 'none';
                    void cartItems.offsetWidth; // Trigger reflow
                    cartItems.style.animation = 'fadeIn 0.3s ease';
                }
            });
        });
    </script>
</div>