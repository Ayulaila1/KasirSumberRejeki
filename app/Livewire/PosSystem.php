<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PosSystem extends Component
{
    // Product data
    public $products = [
        ['id' => 1, 'name' => "Cappuccino", 'price' => 25000, 'category' => "Minuman", 'image' => "https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 2, 'name' => "Teh Tarik", 'price' => 15000, 'category' => "Minuman", 'image' => "https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dGVoJTIwdGFyaWt8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 3, 'name' => "Kopi Susu", 'price' => 20000, 'category' => "Minuman", 'image' => "https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 4, 'name' => "Jus Mangga", 'price' => 18000, 'category' => "Minuman", 'image' => "https://images.unsplash.com/photo-1551029506-0807df4e2031?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8bWFuZ29qfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", 'isNew' => true],
        ['id' => 5, 'name' => "Nasi Goreng Spesial", 'price' => 30000, 'category' => "Makanan", 'image' => "https://images.unsplash.com/photo-1630917765361-5e3f8a8a3b0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fG5hc2klMjBnb3Jlbmd8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 6, 'name' => "Mie Goreng Jawa", 'price' => 28000, 'category' => "Makanan", 'image' => "https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bWllJTIwZ29yZW5nfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", 'isHot' => true],
        ['id' => 7, 'name' => "Roti Bakar Coklat Keju", 'price' => 22000, 'category' => "Makanan", 'image' => "https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cm90aSUyMGJha2FyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"],
        ['id' => 8, 'name' => "Kentang Goreng", 'price' => 25000, 'category' => "Snack", 'image' => "https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a2VudGFuZyUyMGdvcmVuZ3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 9, 'name' => "Pancake Maple", 'price' => 28000, 'category' => "Snack", 'image' => "https://images.unsplash.com/photo-1558312651-5b0c0c4a5b0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFuY2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60", 'isPromo' => true],
        ['id' => 10, 'name' => "Donat Glaze", 'price' => 18000, 'category' => "Snack", 'image' => "https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8ZG9udXR8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"],
        ['id' => 11, 'name' => "Red Velvet Cake", 'price' => 36000, 'originalPrice' => 45000, 'category' => "Promo", 'image' => "https://images.unsplash.com/photo-1510626176961-4b57d4fbad03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Y2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60", 'discount' => "20%"],
        ['id' => 12, 'name' => "Burger + Kentang", 'price' => 45000, 'originalPrice' => 55000, 'category' => "Promo", 'image' => "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YnVyZ2VyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", 'isCombo' => true]
    ];

    // Cart and order data
    public $cart = [];
    public $heldOrders = [];
    public $currentCategory = "Semua";
    public $searchTerm = '';
    public $selectedPaymentMethod = 'cash';
    public $discount = 0;
    public $discountType = 'amount'; // 'amount' or 'percentage'
    public $discountCode = '';
    public $discountInfo = '';
    public $showDiscountInfo = false;
    public $cashAmount = 0;
    public $changeResult = '';
    public $tableNumber = '';
    public $customerName = '';
    public $orderNotes = '';
    public $showReceiptModal = false;
    public $showHeldOrdersModal = false;
    public $receiptData = [];
    public $transactionCounter = 1;

    // Format currency
    private function formatCurrency($amount)
    {
        return "Rp " . number_format($amount, 0, ',', '.');
    }

    // Add to cart
    public function addToCart($productId)
    {
        $existingItemIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['id'] == $productId;
        });

        if ($existingItemIndex !== false) {
            $this->cart[$existingItemIndex]['quantity'] += 1;
        } else {
            $this->cart[] = [
                'id' => $productId,
                'quantity' => 1
            ];
        }

        $this->emit('cartUpdated');
    }

    // Update quantity
    public function updateQuantity($productId, $change)
    {
        $itemIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['id'] == $productId;
        });

        if ($itemIndex !== false) {
            $this->cart[$itemIndex]['quantity'] += $change;

            if ($this->cart[$itemIndex]['quantity'] < 1) {
                $this->cart[$itemIndex]['quantity'] = 1;
            }

            if ($this->cart[$itemIndex]['quantity'] == 0) {
                unset($this->cart[$itemIndex]);
                $this->cart = array_values($this->cart);
            }
        }
    }

    // Set quantity
    public function setQuantity($productId, $quantity)
    {
        $itemIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['id'] == $productId;
        });

        if ($itemIndex !== false) {
            $newQuantity = intval($quantity);

            if ($newQuantity >= 1) {
                $this->cart[$itemIndex]['quantity'] = $newQuantity;
            } else {
                $this->cart[$itemIndex]['quantity'] = 1;
            }
        }
    }

    // Remove from cart
    public function removeFromCart($productId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
            return $item['id'] == $productId;
        })->values()->all();
    }

    // Clear cart
    public function clearCart()
    {
        if (count($this->cart)) {
            $this->cart = [];
            $this->discount = 0;
            $this->discountType = 'amount';
            $this->discountCode = '';
            $this->showDiscountInfo = false;
        }
    }


    // Filter by category
    public function filterByCategory($category)
    {
        $this->currentCategory = $category;
    }

    // Apply discount
    public function applyDiscount()
    {
        if ($this->discountCode === 'DISKON10') {
            $this->discount = 10;
            $this->discountType = 'percentage';
            $this->showDiscountInfo = true;
            $this->discountInfo = 'Diskon 10% berhasil diterapkan';
        } elseif ($this->discountCode === 'DISKON5K') {
            $this->discount = 5000;
            $this->discountType = 'amount';
            $this->showDiscountInfo = true;
            $this->discountInfo = 'Diskon Rp 5.000 berhasil diterapkan';
        } elseif ($this->discountCode) {
            $this->showDiscountInfo = true;
            $this->discountInfo = 'Kode diskon tidak valid';
            $this->discount = 0;
        } else {
            $this->discount = 0;
            $this->showDiscountInfo = false;
        }
    }

    // Select payment method
    public function selectPaymentMethod($method)
    {
        $this->selectedPaymentMethod = $method;
    }

    // Calculate change
    public function calculateChange()
    {
        $cashAmount = floatval($this->cashAmount);
        $total = $this->getTotalAmount();

        if (!$cashAmount) {
            $this->changeResult = 'Masukkan jumlah uang';
            return;
        }

        if ($cashAmount >= $total) {
            $change = $cashAmount - $total;
            $this->changeResult = 'Kembalian: ' . $this->formatCurrency($change);
        } else {
            $this->changeResult = 'Uang kurang: ' . $this->formatCurrency($total - $cashAmount);
        }
    }

    // Get total amount
    public function getTotalAmount()
    {
        $subtotal = collect($this->cart)->reduce(function ($sum, $item) {
            $product = collect($this->products)->firstWhere('id', $item['id']);
            $itemPrice = $product['originalPrice'] ?? $product['price'];
            return $sum + ($itemPrice * $item['quantity']);
        }, 0);

        $totalDiscount = $this->discountType === 'percentage' ?
            $subtotal * ($this->discount / 100) :
            $this->discount;

        $afterDiscount = $subtotal - $totalDiscount;
        $tax = $afterDiscount * 0.1; // Pajak 10%
        return $afterDiscount + $tax;
    }

    // Hold order
    public function holdOrder()
    {
        if (!count($this->cart)) {
            $this->emit('showAlert', 'Tidak ada pesanan untuk dihold', 'danger');
            return;
        }

        $this->heldOrders[] = [
            'id' => now()->timestamp,
            'table' => $this->tableNumber ?: 'Tanpa Meja',
            'customer' => $this->customerName,
            'items' => $this->cart,
            'time' => now()->format('H:i:s'),
            'subtotal' => collect($this->cart)->reduce(function ($sum, $item) {
                $product = collect($this->products)->firstWhere('id', $item['id']);
                $itemPrice = $product['originalPrice'] ?? $product['price'];
                return $sum + ($itemPrice * $item['quantity']);
            }, 0)
        ];

        $this->clearCart();
        $this->emit('showAlert', 'Pesanan berhasil dihold', 'success');
    }

    // Load held order
    public function loadHeldOrder($index)
    {
        if (isset($this->heldOrders[$index])) {
            $order = $this->heldOrders[$index];

            $this->tableNumber = $order['table'] === 'Tanpa Meja' ? '' : $order['table'];
            $this->customerName = $order['customer'];
            $this->cart = $order['items'];

            unset($this->heldOrders[$index]);
            $this->heldOrders = array_values($this->heldOrders);

            $this->showHeldOrdersModal = false;
            $this->emit('showAlert', 'Pesanan berhasil dimuat ke keranjang', 'success');
        }
    }

    // Process payment
    public function processPayment()
    {
        if (!count($this->cart)) {
            $this->emit('showAlert', 'Keranjang kosong, tidak ada yang dibayar', 'danger');
            return;
        }

        if (!$this->selectedPaymentMethod) {
            $this->emit('showAlert', 'Silakan pilih metode pembayaran', 'danger');
            return;
        }

        if (!$this->tableNumber) {
            $this->emit('showAlert', 'Silakan masukkan nomor meja', 'danger');
            return;
        }

        if ($this->selectedPaymentMethod === 'cash') {
            $cashAmount = floatval($this->cashAmount);
            $total = $this->getTotalAmount();

            if ($cashAmount < $total) {
                $this->emit('showAlert', 'Jumlah uang tunai kurang', 'danger');
                return;
            }
        }

        $this->generateReceipt();
        $this->showReceiptModal = true;
    }

    // Generate receipt
    public function generateReceipt()
    {
        $now = now();
        $tableNumber = $this->tableNumber ?: '-';
        $customerName = $this->customerName ?: '-';

        // Generate receipt number
        $receiptNumber = 'TRX-' . $now->format('Ymd') . '-' . str_pad($this->transactionCounter, 3, '0', STR_PAD_LEFT);
        $this->transactionCounter++;

        // Prepare receipt data
        $this->receiptData = [
            'number' => $receiptNumber,
            'date' => $now->format('d/m/Y H:i:s'),
            'customer' => $tableNumber . (($customerName !== '-' && !empty($customerName)) ? " ($customerName)" : ''),
            'cashier' => auth()->check() ? auth()->user()->name : 'Admin',
            'items' => [],
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0,
            'paymentMethod' => '',
            'cash' => 0,
            'change' => 0,
        ];


        // Calculate items and totals
        $subtotal = collect($this->cart)->reduce(function ($sum, $item) {
            $product = collect($this->products)->firstWhere('id', $item['id']);
            $itemPrice = $product['originalPrice'] ?? $product['price'];
            $itemTotal = $itemPrice * $item['quantity'];

            $this->receiptData['items'][] = [
                'name' => $product['name'],
                'quantity' => $item['quantity'],
                'price' => $itemPrice,
                'total' => $itemTotal
            ];

            return $sum + $itemTotal;
        }, 0);

        $totalDiscount = $this->discountType === 'percentage' ?
            $subtotal * ($this->discount / 100) :
            $this->discount;

        $afterDiscount = $subtotal - $totalDiscount;
        $tax = $afterDiscount * 0.1;
        $total = $afterDiscount + $tax;

        $this->receiptData['subtotal'] = $subtotal;
        $this->receiptData['discount'] = $totalDiscount;
        $this->receiptData['tax'] = $tax;
        $this->receiptData['total'] = $total;

        // Set payment method
        $paymentMethods = [
            'cash' => 'Tunai',
            'debit' => 'Kartu Debit',
            'credit' => 'Kartu Kredit',
            'qris' => 'QRIS',
            'ewallet' => 'E-Wallet',
            'transfer' => 'Transfer Bank'
        ];

        $this->receiptData['paymentMethod'] = $paymentMethods[$this->selectedPaymentMethod] ?? '';

        if ($this->selectedPaymentMethod === 'cash') {
            $cashAmount = floatval($this->cashAmount);
            $this->receiptData['cash'] = $cashAmount;
            $this->receiptData['change'] = $cashAmount - $total;
        }
    }

    // Close receipt and clear cart
    public function closeReceipt()
    {
        $this->showReceiptModal = false;
        $this->clearCart();
    }

    // Get filtered products
    public function getFilteredProductsProperty()
    {
        return collect($this->products)
            ->when($this->currentCategory !== 'Semua', function ($collection) {
                return $collection->where('category', $this->currentCategory);
            })
            ->when($this->searchTerm, function ($collection) {
                return $collection->filter(function ($product) {
                    return str_contains(strtolower($product['name']), strtolower($this->searchTerm));
                });
            })
            ->values()
            ->all();
    }

    // Get cart summary
    public function getCartSummaryProperty()
    {
        $subtotal = collect($this->cart)->reduce(function ($sum, $item) {
            $product = collect($this->products)->firstWhere('id', $item['id']);
            $itemPrice = $product['originalPrice'] ?? $product['price'];
            return $sum + ($itemPrice * $item['quantity']);
        }, 0);

        $totalDiscount = $this->discountType === 'percentage' ?
            $subtotal * ($this->discount / 100) :
            $this->discount;

        $afterDiscount = $subtotal - $totalDiscount;
        $tax = $afterDiscount * 0.1;
        $total = $afterDiscount + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $totalDiscount,
            'tax' => $tax,
            'total' => $total
        ];
    }

    // Render the component
    public function render()
    {
        return view('livewire.pos-system');
    }
}
