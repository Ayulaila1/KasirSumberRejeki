<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Penjualandtl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PenjualanComponent extends Component
{
    public $products = [];
    public $cart = [];
    public $selectedPaymentMethod = 'cash';
    public $discount = 0;
    public $discountType = 'amount';
    public $heldOrders = [];
    public $currentCategory = "Semua";
    public $tableNumber = '';
    public $customerName = '';
    public $orderNotes = '';
    public $discountCode = '';
    public $cashAmount = 0;
    public $showReceipt = false;
    public $receiptData = [];
    public $showKitchenReceipt = false;
    public $kitchenReceiptData = [];

    protected $listeners = ['productSelected' => 'addToCart'];

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Produk::all()->map(function ($product) {
            return [
                'id' => $product->idproduk,
                'name' => $product->nama,
                'price' => $product->harga_jual ?? 0,
                'category' => $product->jenisproduk ?? 'Minuman',
                'image' => $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150',
                'stok' => $product->stok
            ];
        })->toArray();
    }

    public function addToCart($productId)
    {
        $product = collect($this->products)->firstWhere('id', $productId);

        if (!$product)
            return;

        $existingItem = collect($this->cart)->firstWhere('id', $productId);

        if ($existingItem) {
            $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
                if ($item['id'] == $productId) {
                    $item['quantity'] += 1;
                }
                return $item;
            })->toArray();
        } else {
            $this->cart[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }

        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($productId, $change)
    {
        $this->cart = collect($this->cart)->map(function ($item) use ($productId, $change) {
            if ($item['id'] == $productId) {
                $newQuantity = $item['quantity'] + $change;
                $item['quantity'] = max(1, $newQuantity);
            }
            return $item;
        })->toArray();

        $this->dispatch('cartUpdated');
    }

    public function setQuantity($productId, $quantity)
    {
        $quantity = max(1, (int) $quantity);

        $this->cart = collect($this->cart)->map(function ($item) use ($productId, $quantity) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
            }
            return $item;
        })->toArray();

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($productId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
            return $item['id'] == $productId;
        })->values()->toArray();

        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        if (empty($this->cart))
            return;

        $this->cart = [];
        $this->discount = 0;
        $this->discountCode = '';
        $this->dispatch('cartUpdated');
    }

    public function filterByCategory($category)
    {
        $this->currentCategory = $category;
    }

    public function selectPaymentMethod($method)
    {
        $this->selectedPaymentMethod = $method;
    }

    public function applyDiscount()
    {
        // Simple discount logic
        if ($this->discountCode === 'DISKON10') {
            $this->discount = 10;
            $this->discountType = 'percentage';
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Diskon 10% berhasil diterapkan']);
        } elseif ($this->discountCode === 'DISKON5K') {
            $this->discount = 5000;
            $this->discountType = 'amount';
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Diskon Rp 5.000 berhasil diterapkan']);
        } elseif ($this->discountCode) {
            $this->discount = 0;
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Kode diskon tidak valid']);
        } else {
            $this->discount = 0;
        }
    }

    public function calculateChange()
    {
        $cashAmount = (float) $this->cashAmount;
        $total = $this->getTotalAmount();

        if ($cashAmount >= $total) {
            return number_format($cashAmount - $total, 0, ',', '.');
        } else {
            return false;
        }
    }

    public function getTotalAmount()
    {
        $subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $totalDiscount = $this->discountType === 'percentage' ?
            $subtotal * ($this->discount / 100) :
            $this->discount;

        $afterDiscount = $subtotal - $totalDiscount;
        $tax = $afterDiscount * 0.1; // 10% tax
        return $afterDiscount + $tax;
    }

    public function holdOrder()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tidak ada pesanan untuk dihold']);
            return;
        }

        $this->heldOrders[] = [
            'id' => now()->timestamp,
            'table' => $this->tableNumber ?: 'Tanpa Meja',
            'customer' => $this->customerName,
            'items' => $this->cart,
            'time' => now()->format('H:i:s'),
            'subtotal' => collect($this->cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            })
        ];

        $this->clearCart();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Pesanan berhasil dihold']);
    }

    public function loadHeldOrder($index)
    {
        if (isset($this->heldOrders[$index])) {
            $order = $this->heldOrders[$index];
            $this->tableNumber = $order['table'];
            $this->customerName = $order['customer'];
            $this->cart = $order['items'];

            unset($this->heldOrders[$index]);
            $this->heldOrders = array_values($this->heldOrders);

            $this->dispatch('cartUpdated');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Pesanan berhasil dimuat ke keranjang']);
        }
    }

    public function processPayment()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Keranjang kosong, tidak ada yang dibayar']);
            return;
        }

        if (!$this->selectedPaymentMethod) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Silakan pilih metode pembayaran']);
            return;
        }

        if (!$this->tableNumber) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Silakan masukkan nomor meja']);
            return;
        }

        if ($this->selectedPaymentMethod === 'cash') {
            $cashAmount = (float) $this->cashAmount;
            $total = $this->getTotalAmount();

            if ($cashAmount < $total) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Jumlah uang tunai kurang']);
                return;
            }
        }

        // Save transaction to database
        $this->saveTransaction();

        // Prepare receipt data
        $this->prepareReceiptData();

        // Show receipts
        $this->showReceipt = true;
        $this->showKitchenReceipt = true;
    }

    protected function saveTransaction()
    {
        // Create penjualan header
        $penjualan = Penjualan::create([
            'kode_penjualan' => 'TRX-' . now()->format('Ymd') . '-' . Str::random(4),
            'tanggal' => now()->toDateString(),
            'total' => $this->getTotalAmount(),
            'bayar' => $this->selectedPaymentMethod === 'cash' ? $this->cashAmount : $this->getTotalAmount(),
            'kembalian' => $this->selectedPaymentMethod === 'cash' ? $this->calculateChange() : 0,
            'user_id' => Auth::id(),
            'meja' => $this->tableNumber,
            'pelanggan' => $this->customerName,
            'catatan' => $this->orderNotes,
            'metode_pembayaran' => $this->selectedPaymentMethod
        ]);

        // Create penjualan details
        foreach ($this->cart as $item) {
            Penjualandtl::create([
                'penjualan_idpenjualan' => $penjualan->idpenjualan,
                'produk_idproduk' => $item['id'],
                'qty' => $item['quantity'],
                'harga_jual' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);

            // Update product stock
            $product = Produk::find($item['id']);
            if ($product) {
                $product->stok -= $item['quantity'];
                $product->save();
            }
        }
    }

    protected function prepareReceiptData()
    {
        $now = now();
        $subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $totalDiscount = $this->discountType === 'percentage' ?
            $subtotal * ($this->discount / 100) :
            $this->discount;

        $afterDiscount = $subtotal - $totalDiscount;
        $tax = $afterDiscount * 0.1;
        $total = $afterDiscount + $tax;

        // Customer receipt data
        $this->receiptData = [
            'number' => 'TRX-' . $now->format('Ymd') . '-' . Str::random(4),
            'date' => $now->format('d/m/Y H:i:s'),
            'customer' => $this->tableNumber . ($this->customerName ? ' (' . $this->customerName . ')' : ''),
            'cashier' => Auth::user()->name,
            'items' => $this->cart,
            'subtotal' => $subtotal,
            'discount' => $totalDiscount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $this->getPaymentMethodName(),
            'cash' => $this->selectedPaymentMethod === 'cash' ? $this->cashAmount : 0,
            'change' => $this->selectedPaymentMethod === 'cash' ? $this->calculateChange() : 0,
            'notes' => $this->orderNotes
        ];

        // Kitchen receipt data (simplified version)
        $this->kitchenReceiptData = [
            'number' => $this->receiptData['number'],
            'table' => $this->tableNumber,
            'customer' => $this->customerName,
            'items' => $this->cart,
            'notes' => $this->orderNotes,
            'time' => $now->format('H:i:s')
        ];
    }

    protected function getPaymentMethodName()
    {
        $methods = [
            'cash' => 'Tunai',
            'debit' => 'Kartu Debit',
            'credit' => 'Kartu Kredit',
            'qris' => 'QRIS',
            'ewallet' => 'E-Wallet',
            'transfer' => 'Transfer Bank'
        ];

        return $methods[$this->selectedPaymentMethod] ?? 'Unknown';
    }

    public function closeReceipt()
    {
        $this->showReceipt = false;
        $this->showKitchenReceipt = false;
        $this->clearCart();
    }


    public function render()
    {
        $filteredProducts = collect($this->products);

        if ($this->currentCategory !== 'Semua') {
            $filteredProducts = $filteredProducts->filter(function ($product) {
                return $product['category'] === $this->currentCategory;
            });
        }

        return view('livewire.penjualan-component', [
            'filteredProducts' => $filteredProducts,
            'subtotal' => collect($this->cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
            'totalDiscount' => $this->discountType === 'percentage' ?
                collect($this->cart)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }) * ($this->discount / 100) :
                $this->discount,
            'tax' => (collect($this->cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }) - $this->discount) * 0.1,
            'total' => $this->getTotalAmount(),
            'change' => $this->calculateChange()
        ]);
    }
}
