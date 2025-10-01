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
                    <div class="product-card h-100 position-relative">

                        <!-- Gambar Produk -->
                        <div class="product-image position-relative">
                            <img src="{{ asset('storage/image-website/'. $product->image) }}" alt="{{ $product->nama }}"
                                class="img-fluid rounded {{ $product->stok_tersedia <= 0 ? 'grayscale' : '' }}">

                            <button type="button" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2"
                                style="z-index: 10" wire:click.prevent="showIngredients({{ $product->idproduk }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <!-- Info Produk -->
                        <div class="product-info mt-2" {{-- Kalau stok habis → tidak bisa diklik --}} @if($product->
                            stok_tersedia >
                            0)
                            wire:click="addToCart('{{ $product->idproduk }}')"
                            @endif
                            >
                            <div class="product-name fw-semibold">{{ $product->nama }}</div>

                            <div class="product-price d-flex justify-content-between align-items-center text-muted">
                                <!-- Harga -->
                                <span>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</span>

                                <!-- Stok -->
                                <span class="badge {{ $product->stok_tersedia > 0 ? 'bg-info' : 'bg-secondary' }}">
                                    Stok: {{ $product->stok_tersedia }}
                                </span>
                            </div>
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
                    <input type="text" wire:model.live="tableNumber" class="customer-input" placeholder="Nomor Meja">
                    <input type="text" wire:model.live="customerName" class="customer-input"
                        placeholder="Nama Pelanggan (Opsional)">
                </div>

                <div class="notes-section">
                    <h4><i class="fas fa-sticky-note"></i> Catatan</h4>
                    <textarea wire:model.live="orderNotes" placeholder="Catatan untuk pesanan..."></textarea>
                </div>

                <div class="cart-items">
                    @forelse($cart as $id => $item)
                    <div class="cart-item py-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <!-- Info Produk -->
                            <div class="cart-item-info">
                                <div class="cart-item-name fw-semibold">
                                    {{ $item['product']->nama ?? $item['name'] }}
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
                            <span>
                                Rp {{ number_format(($item['jumlah'] ?? $item['quantity']) * ($item['harga_jual'] ??
                                $item['price']), 0,
                                ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-cart text-center py-4">
                        <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada pesanan</p>
                    </div>
                    @endforelse

                    <div class="p-3 border-top mt-3">
                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Total :</strong>
                            <span class="fs-5 text-success fw-bold">Rp 0</span>
                        </div>

                        <!-- Bayar -->
                        <div class="row align-items-center mb-2">
                            <label class="col-4 col-form-label fw-semibold">Bayar (Tunai) :</label>
                            <div class="col-8">
                                <input type="text" wire:model.live="cashFormatted" placeholder="Masukkan uang tunai..."
                                    class="form-control text-end">
                            </div>
                        </div>

                        <!-- Kembalian -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Kembalian :</span>
                            <span class="fs-5 text-primary fw-bold">Rp 0</span>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-warning">
                                <i class="fas fa-pause"></i> Hold
                            </button>
                            <button class="btn btn-success">
                                <i class="fas fa-cash-register"></i> Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ingredient -->
        @if($showModal)
        <div class="custom-modal">
            <div class="custom-modal-dialog">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h5 class="custom-modal-title">Bahan {{ $selectedProduk?->nama }}</h5>
                        <button type="button" class="custom-modal-close"
                            wire:click="$set('showModal', false)">×</button>
                    </div>
                    <div class="custom-modal-body">
                        @if(count($ingredients) > 0)
                        <table class="ingredient-table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan</th>
                                    <th>Takaran</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingredients as $index => $item)
                                <tr style="animation-delay: {{ $index * 0.1 }}s;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->bahan?->nama }}</td>
                                    <td>{{ $item->takaran }}</td>
                                    <td>{{ $item->satuan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted text-center">Tidak ada bahan untuk produk ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <style>
            /* BACKDROP & MODAL */
            .custom-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                backdrop-filter: blur(4px);
                background-color: rgba(0, 0, 0, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1050;
                padding: 15px;
                animation: fadeIn 0.4s ease forwards;
            }

            .custom-modal-dialog {
                width: 100%;
                max-width: 500px;
            }

            /* CONTENT */
            .custom-modal-content {
                background: #fff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
                display: flex;
                flex-direction: column;
                max-height: 85vh;
                transform: scale(0.9) translateY(-15px);
                opacity: 0;
                animation: modalPop 0.4s forwards;
            }

            /* HEADER */
            .custom-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 20px;
                background: linear-gradient(90deg, #7a4b47, #ff7e5f);
                color: #fff;
                font-weight: 600;
                flex-shrink: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                letter-spacing: 0.5px;
                font-size: 1.1rem;
            }

            .custom-modal-close {
                background: transparent;
                border: none;
                font-size: 1.6rem;
                color: #fff;
                cursor: pointer;
                transition: transform 0.2s ease, color 0.2s ease;
            }

            .custom-modal-close:hover {
                color: #ffeb3b;
                transform: rotate(90deg) scale(1.2);
            }

            /* BODY */
            .custom-modal-body {
                padding: 20px;
                overflow-y: auto;
            }

            /* TABLE */
            .ingredient-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                font-size: 0.95rem;
            }

            .ingredient-table thead {
                background-color: #f8f8f8;
                color: #000;
            }

            .ingredient-table th,
            .ingredient-table td {
                padding: 10px 12px;
                border-bottom: 1px solid #ddd;
            }

            .ingredient-table tbody tr {
                opacity: 0;
                transform: translateX(-15px);
                animation: listFadeIn 0.4s forwards;
            }

            /* SCROLLBAR CUSTOM */
            .custom-modal-body::-webkit-scrollbar {
                width: 8px;
            }

            .custom-modal-body::-webkit-scrollbar-track {
                background: #f0f0f0;
                border-radius: 4px;
            }

            .custom-modal-body::-webkit-scrollbar-thumb {
                background: #7a4b47;
                border-radius: 4px;
            }

            /* ANIMATIONS */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes modalPop {
                from {
                    transform: scale(0.9) translateY(-15px);
                    opacity: 0;
                }

                to {
                    transform: scale(1) translateY(0);
                    opacity: 1;
                }
            }

            @keyframes listFadeIn {
                from {
                    opacity: 0;
                    transform: translateX(-15px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* RESPONSIVE */
            @media (max-width: 768px) {
                .custom-modal-content {
                    max-width: 95%;
                    border-radius: 10px;
                }

                .custom-modal-header {
                    font-size: 1rem;
                    padding: 12px 15px;
                }

                .custom-modal-body {
                    padding: 12px 15px;
                }

                .ingredient-table th,
                .ingredient-table td {
                    padding: 8px 10px;
                }
            }
        </style>


        @include('layouts.confirmPaymentModal')

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        window.addEventListener('close-detail-modal', event =>{
                $('#detailBahanModal').modal('hide');
                });
    </script>