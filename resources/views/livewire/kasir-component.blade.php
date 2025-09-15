<div>
    <div class="d-flex flex-wrap gap-3 align-items-start">
        <div class="product-section" style="flex: 2.5;">
            <div class="header">
                <h2><i class="fas fa-mug-hot"></i> Menu Cafe Suki</h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <!-- Kotak Search -->
                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 shadow-sm">
                        <i class="fas fa-search text-muted me-2"></i>
                        <input type="text" wire:model.live="search" placeholder="Cari menu..." id="search-input"
                            class="form-control form-control-sm border-0 bg-transparent shadow-none" />
                    </div>

                    <!-- Tombol ke Dashboard -->
                    <a class="btn btn-sm btn-primary fw-bold d-flex align-items-center justify-content-center"
                        href="/dashboard">
                        <i class="fa-solid fa-house-chimney"></i>
                    </a>
                </div>

            </div>

            <div class="category-tabs">
                @foreach($categories as $category)
                <div class="category-tab {{ $selectedCategory === $category ? 'active' : '' }}"
                    wire:click="filterByCategory('{{ $category }}')">
                    {{ $category }}
                </div>
                @endforeach
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3" id="product-grid">
                @forelse($products as $product)
                <div class="col">
                    <div class="product-card h-100" wire:click="addToCart('{{ $product->idproduk }}')">
                        <div class="product-image">
                            <img src="{{ asset('storage/image-website/'. $product->image) }}" alt="{{ $product->nama }}"
                                class="img-fluid rounded">
                        </div>
                        <div class="product-info mt-2">
                            <div class="product-name fw-semibold">{{ $product->nama }}</div>
                            <div class="product-price text-muted">Rp {{ number_format($product->harga_jual, 0, ',', '.')
                                }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center p-5">
                    <p>Menu tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="cart-section" style="flex: 1.5; margin-left: -10px;">
            <div class="cart-body">
                <div class="customer-info">
                    <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                    <input type="text" wire:model="tableNumber" class="customer-input" placeholder="Nomor Meja">
                    <input type="text" wire:model="customerName" class="customer-input"
                        placeholder="Nama Pelanggan (Opsional)">
                </div>

                {{-- <div class="payment-methods-section">

                    <div class="payment-methods">
                    </div>
                </div> --}}

                <div class="notes-section">
                    <h4><i class="fas fa-sticky-note"></i> Catatan</h4>
                    <textarea wire:model="orderNotes" placeholder="Catatan untuk pesanan..."></textarea>
                </div>

                <div class="cart-items">
                    @forelse($cart as $id => $item)
                    <div class="cart-item py-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <!-- Info Produk -->
                            <div class="cart-item-info">
                                <div class="cart-item-name fw-semibold">{{ $item['product']->nama ?? $item['name'] }}
                                </div>
                                <div class="cart-item-price text-muted small">
                                    Rp {{ number_format($item['harga_jual'] ?? $item['price'], 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Tombol Kontrol -->
                            <div class="cart-item-controls d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary px-2"
                                    wire:click="updateQty('{{ $id }}', -1)">-</button>
                                <span>{{ $item['jumlah'] ?? $item['quantity'] }}</span>
                                <button class="btn btn-sm btn-outline-secondary px-2"
                                    wire:click="updateQty('{{ $id }}', 1)">+</button>
                                <button class="btn btn-sm btn-danger" wire:click="removeItem('{{ $id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="summary-row d-flex justify-content-between small text-muted">
                            <span>Rp {{ number_format(($item['jumlah'] ?? $item['quantity']) * ($item['harga_jual'] ??
                                $item['price']),
                                0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-cart text-center py-4">
                        <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada pesanan</p>
                    </div>
                    @endforelse

                    <!-- TOTAL -->
                    <div class="summary-row total-row mt-3 border-top pt-2 d-flex justify-content-between">
                        <span style="font-size: 14px;" class="fw-bold">Total:</span>
                        <span style="font-size: 14px;" class="fw-bold">Rp {{ number_format($this->getTotal(), 0, ',',
                            '.') }}</span>
                    </div>

                    <!-- Pembayaran -->
                    <input type="number" wire:model="cashAmount" placeholder="Masukkan uang tunai..."
                        class="form-control" />

                    <p>Kembalian: Rp {{ number_format(max($cashAmount - $this->getTotal(), 0), 0, ',', '.') }}</p>

                    <!-- Tombol Bayar -->
                    <div class="action-buttons mt-3">
                        <button class="btn btn-success w-100" wire:click="processPayment()">
                            <i class="fas fa-print"></i> Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showReceipt)
    <div class="receipt-modal" style="display: flex;">
        <div class="receipt-content">
            <div class="close-receipt" wire:click="closeReceipt">&times;</div>
            <div class="receipt-header">
                <h3>Struk Pembayaran</h3>
                <p>Cafe Suki</p>
            </div>
            <div class="receipt-items">
                @foreach($receiptData['items'] as $item)
                <div class="receipt-item">
                    <div>{{ $item['name'] }} x {{ $item['quantity'] }}</div>
                    <div>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            <div class="receipt-total">
                <div class="receipt-total-row">
                    <span>Total:</span>
                    <span>Rp {{ number_format($receiptData['total'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>