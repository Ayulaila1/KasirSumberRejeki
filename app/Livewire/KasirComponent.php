<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDtl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KasirComponent extends Component
{
    public $cart = [];
    public $search = '';
    public $selectedCategory = 'Semua';
    public $customerName = '';
    public $tableNumber = '';
    public $paymentMethod = 'cash';
    public $cashAmount = 0;
    public $change = 0;
    public $discountCode = '';
    public $discount = 0;
    public $discountType = 'amount'; // 'amount' atau 'percentage'
    public $notes = '';
    public $showReceipt = false;
    public $receiptData = [];
    public $heldOrders = [];
    public $showHeldOrdersModal = false;

    protected $listeners = ['produkDipilih' => 'addToCart'];

    public function render()
    {
        $products = Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory !== 'Semua', function ($query) {
                $query->where('jenisproduk', $this->selectedCategory);
            })
            ->get();

        $categories = ['Semua', 'racikan', 'sachet', 'Titipan'];

        $this->updateCartTotals();

        return view('livewire.kasir-component', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function addToCart($productId)
    {
        $product = Produk::find($productId);

        if (!$product) {
            return;
        }

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
                'id' => $product->idproduk,
                'name' => $product->nama,
                'price' => $product->harga_jual ?? 0,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($productId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
            return $item['id'] == $productId;
        })->values()->toArray();

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
        $quantity = max(1, intval($quantity));

        $this->cart = collect($this->cart)->map(function ($item) use ($productId, $quantity) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
            }
            return $item;
        })->toArray();

        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        if (empty($this->cart)) {
            return;
        }

        $this->cart = [];
        $this->discount = 0;
        $this->discountCode = '';
        $this->dispatch('cartUpdated');
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function selectPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function applyDiscount()
    {
        // Contoh logika diskon sederhana
        if ($this->discountCode === 'DISKON10') {
            $this->discount = 10;
            $this->discountType = 'percentage';
            $this->dispatch('showAlert', 'Diskon 10% berhasil diterapkan', 'success');
        } elseif ($this->discountCode === 'DISKON5K') {
            $this->discount = 5000;
            $this->discountType = 'amount';
            $this->dispatch('showAlert', 'Diskon Rp 5.000 berhasil diterapkan', 'success');
        } elseif ($this->discountCode) {
            $this->dispatch('showAlert', 'Kode diskon tidak valid', 'danger');
            $this->discount = 0;
        } else {
            $this->discount = 0;
        }

        $this->updateCartTotals();
    }

    public function calculateChange()
    {
        $total = $this->getTotalAmount();
        $cash = floatval($this->cashAmount);

        if ($cash >= $total) {
            $this->change = $cash - $total;
        } else {
            $this->change = 0;
            $this->dispatch('showAlert', 'Jumlah uang tunai kurang', 'danger');
        }
    }

    public function holdOrder()
    {
        if (empty($this->cart)) {
            $this->dispatch('showAlert', 'Tidak ada pesanan untuk dihold', 'danger');
            return;
        }

        if (empty($this->tableNumber)) {
            $this->dispatch('showAlert', 'Silakan masukkan nomor meja', 'danger');
            return;
        }

        $this->heldOrders[] = [
            'id' => uniqid(),
            'table' => $this->tableNumber,
            'customer' => $this->customerName,
            'items' => $this->cart,
            'time' => now()->format('H:i:s'),
            'subtotal' => $this->getSubtotal(),
        ];

        $this->clearCart();
        $this->dispatch('showAlert', 'Pesanan berhasil dihold', 'success');
    }

    public function loadHeldOrder($index)
    {
        if (isset($this->heldOrders[$index])) {
            $order = $this->heldOrders[$index];

            $this->tableNumber = $order['table'];
            $this->customerName = $order['customer'];
            $this->cart = $order['items'];

            // Remove from held orders
            unset($this->heldOrders[$index]);
            $this->heldOrders = array_values($this->heldOrders);

            $this->showHeldOrdersModal = false;
            $this->dispatch('cartUpdated');
            $this->dispatch('showAlert', 'Pesanan berhasil dimuat ke keranjang', 'success');
        }
    }

    public function processPayment()
    {
        if (empty($this->cart)) {
            $this->dispatch('showAlert', 'Keranjang kosong, tidak ada yang dibayar', 'danger');
            return;
        }

        if (empty($this->paymentMethod)) {
            $this->dispatch('showAlert', 'Silakan pilih metode pembayaran', 'danger');
            return;
        }

        if (empty($this->tableNumber)) {
            $this->dispatch('showAlert', 'Silakan masukkan nomor meja', 'danger');
            return;
        }

        if ($this->paymentMethod === 'cash' && $this->cashAmount < $this->getTotalAmount()) {
            $this->dispatch('showAlert', 'Jumlah uang tunai kurang', 'danger');
            return;
        }

        DB::transaction(function () {
            // Buat penjualan
            $penjualan = Penjualan::create([
                'kode_penjualan' => 'TRX-' . now()->format('Ymd') . '-' . Str::random(4),
                'tanggal' => now()->format('Y-m-d'),
                'total' => $this->getTotalAmount(),
                'bayar' => $this->paymentMethod === 'cash' ? $this->cashAmount : $this->getTotalAmount(),
                'kembalian' => $this->paymentMethod === 'cash' ? $this->change : 0,
                'user_id' => auth()->id(),
            ]);

            // Buat detail penjualan
            foreach ($this->cart as $item) {
                PenjualanDtl::create([
                    'penjualan_idpenjualan' => $penjualan->idpenjualan,
                    'produk_idproduk' => $item['id'],
                    'qty' => $item['quantity'],
                    'harga_jual' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stok produk jika perlu
                // ...
            }

            // Set receipt data
            $this->receiptData = [
                'number' => $penjualan->kode_penjualan,
                'date' => now()->format('d/m/Y H:i:s'),
                'customer' => $this->customerName ?: '-',
                'table' => $this->tableNumber,
                'items' => $this->cart,
                'subtotal' => $this->getSubtotal(),
                'discount' => $this->getDiscountAmount(),
                'tax' => $this->getTaxAmount(),
                'total' => $this->getTotalAmount(),
                'paymentMethod' => $this->getPaymentMethodName(),
                'cash' => $this->paymentMethod === 'cash' ? $this->cashAmount : 0,
                'change' => $this->paymentMethod === 'cash' ? $this->change : 0,
            ];

            $this->showReceipt();
        });

        $this->clearCart();
    }

    public function showReceipt()
    {
        $this->showReceipt = true;
    }

    public function closeReceipt()
    {
        $this->showReceipt = false;
    }

    public function toggleHeldOrdersModal()
    {
        $this->showHeldOrdersModal = !$this->showHeldOrdersModal;
    }

    protected function updateCartTotals()
    {
        $this->dispatch('updateCartTotals', [
            'subtotal' => $this->getSubtotal(),
            'discount' => $this->getDiscountAmount(),
            'tax' => $this->getTaxAmount(),
            'total' => $this->getTotalAmount(),
        ]);
    }

    protected function getSubtotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    protected function getDiscountAmount()
    {
        if ($this->discountType === 'percentage') {
            return $this->getSubtotal() * ($this->discount / 100);
        }
        return $this->discount;
    }

    protected function getTaxAmount()
    {
        return ($this->getSubtotal() - $this->getDiscountAmount()) * 0.1; // Pajak 10%
    }

    protected function getTotalAmount()
    {
        return ($this->getSubtotal() - $this->getDiscountAmount()) + $this->getTaxAmount();
    }

    protected function getPaymentMethodName()
    {
        $methods = [
            'cash' => 'Tunai',
            'debit' => 'Kartu Debit',
            'credit' => 'Kartu Kredit',
            'qris' => 'QRIS',
            'ewallet' => 'E-Wallet',
            'transfer' => 'Transfer Bank',
        ];

        return $methods[$this->paymentMethod] ?? 'Unknown';
    }
}
