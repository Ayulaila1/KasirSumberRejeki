<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;
use App\Models\Penjualan;
use Illuminate\Support\Str;
use App\Models\PenjualanDtl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    // public $discountCode = '';
    // public $discount = 0;
    // public $discountType = 'amount'; // 'amount' atau 'percentage'
    public $notes = '';
    public $showReceipt = false;
    public $receiptData = [];
    // public $heldOrders = [];
    // public $showHeldOrdersModal = false;

    protected $listeners = ['produkDipilih' => 'addToCart'];

    public function render()
    {
        $products = Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory !== 'Semua', function ($query) {
                $query->where('kategori', $this->selectedCategory);
            })
            ->get();

        $categories = ['Semua'] + Produk::select('kategori')->distinct()->pluck('kategori')->toArray();

        $this->updateCartTotals();

        return view('livewire.kasir-component', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.kasirlayout');
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
            ];
        }

        $this->dispatch('cartUpdated');
    }


    public function updateQty($productId, $delta = 1)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] += $delta;

            if ($this->cart[$productId]['quantity'] < 1) {
                unset($this->cart[$productId]);
            }
        }
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
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

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
    }

    // public function selectPaymentMethod($method)
    // {
    //     $this->paymentMethod = $method;
    // }

    public function calculateChange()
    {
        $total = $this->getTotal();
        $cash = floatval($this->cashAmount);

        if ($cash >= $total) {
            $this->change = $cash - $total;
        } else {
            $this->change = 0;
            $this->dispatch('showAlert', 'Jumlah uang tunai kurang', 'danger');
        }
    }

    public function processPayment()
    {
        // dd("test");
        // if (empty($this->cart)) {
        //     $this->dispatch('showAlert', 'Keranjang kosong, tidak ada yang dibayar', 'danger');
        //     return;
        // }

        // if (empty($this->tableNumber)) {
        //     $this->dispatch('showAlert', 'Silakan masukkan nomor meja', 'danger');
        //     return;
        // }

        // if ($this->cashAmount < $this->getTotal()) {
        //     $this->dispatch('showAlert', 'Jumlah uang tunai kurang', 'danger');
        //     return;
        // }

        try {
            DB::beginTransaction();

            // Buat penjualan
            $penjualan = Penjualan::create([
                'kode_penjualan' => 'TRX-' . now()->format('Ymd') . '-' . Str::random(4),
                'tanggal' => now()->format('Y-m-d'),
                'total' => $this->getTotal(),
                'bayar' => $this->cashAmount,
                'kembalian' => $this->cashAmount - $this->getTotal(),
                'user_id' => Auth::user()->id,
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

                // Tambahkan logika pengurangan stok jika perlu

                $produk = Produk::where('idproduk', $item['id'])->first();


                // Jika racikan, ambil bahan-bahannya
                $bahanList = DB::table('produk_racikans')
                    ->where('produk_idproduk', $produk->idproduk)
                    ->get();

                foreach ($bahanList as $bahan) {
                    // Hitung stok yang harus dikurangkan = jumlah * takaran
                    $jumlahBahan = $item['quantity'] * $bahan->takaran;

                    DB::table('bahans')
                        ->where('idbahan', $bahan->bahan_idbahan)
                        ->decrement('stok', $jumlahBahan);
                }

            }

            DB::commit(); // Simpan transaksi

            // Siapkan data struk
            $this->receiptData = [
                'number' => $penjualan->kode_penjualan,
                'date' => now()->format('d/m/Y H:i:s'),
                'customer' => $this->customerName ?: '-',
                'table' => $this->tableNumber,
                'items' => $this->cart,
                'subtotal' => $this->getTotal(),
                'total' => $this->getTotal(),
                'paymentMethod' => 'Tunai',
                'cash' => $this->cashAmount,
                'change' => $this->cashAmount - $this->getTotal(),
            ];

            $this->showReceipt();
            $this->clearCart();

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Gagal memproses pembayaran: ' . $e->getMessage());
            $this->dispatch('showAlert', 'Terjadi kesalahan saat memproses pembayaran.', 'danger');
        }
    }


    public function showReceipt()
    {
        $this->showReceipt = true;
    }

    public function closeReceipt()
    {
        $this->showReceipt = false;
    }

    protected function updateCartTotals()
    {
        $this->dispatch('updateCartTotals', [
            'subtotal' => $this->getTotal(),
            // 'discount' => $this->getDiscountAmount(),
            // 'tax' => $this->getTaxAmount(),
            'total' => $this->getTotal(),
        ]);
    }

    public function getTotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
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

    // public function removeFromCart($productId)
    // {
    //     $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
    //         return $item['id'] == $productId;
    //     })->values()->toArray();

    //     $this->dispatch('cartUpdated');
    // }

    // public function clearCart()
    // {
    //     if (empty($this->cart)) {
    //         return; //
    //     }

    //     $this->cart = [];
    //     $this->discount = 0;
    //     $this->discountCode = '';
    //     // $this->dispatch('cartUpdated');
    // }

    // public function applyDiscount()
    // {
    //     // Contoh logika diskon sederhana
    //     if ($this->discountCode === 'DISKON10') {
    //         $this->discount = 10;
    //         $this->discountType = 'percentage';
    //         $this->dispatch('showAlert', 'Diskon 10% berhasil diterapkan', 'success');
    //     } elseif ($this->discountCode === 'DISKON5K') {
    //         $this->discount = 5000;
    //         $this->discountType = 'amount';
    //         $this->dispatch('showAlert', 'Diskon Rp 5.000 berhasil diterapkan', 'success');
    //     } elseif ($this->discountCode) {
    //         $this->dispatch('showAlert', 'Kode diskon tidak valid', 'danger');
    //         $this->discount = 0;
    //     } else {
    //         $this->discount = 0;
    //     }

    //     $this->updateCartTotals();
    // }

    // public function holdOrder()
    // {
    //     if (empty($this->cart)) {
    //         $this->dispatch('showAlert', 'Tidak ada pesanan untuk dihold', 'danger');
    //         return;
    //     }

    //     if (empty($this->tableNumber)) {
    //         $this->dispatch('showAlert', 'Silakan masukkan nomor meja', 'danger');
    //         return;
    //     }

    //     $this->heldOrders[] = [
    //         'id' => uniqid(),
    //         'table' => $this->tableNumber,
    //         'customer' => $this->customerName,
    //         'items' => $this->cart,
    //         'time' => now()->format('H:i:s'),
    //         'subtotal' => $this->getTotal(),
    //     ];

    //     $this->clearCart();
    //     $this->dispatch('showAlert', 'Pesanan berhasil dihold', 'success');
    // }

    // public function loadHeldOrder($index)
    // {
    //     if (isset($this->heldOrders[$index])) {
    //         $order = $this->heldOrders[$index];

    //         $this->tableNumber = $order['table'];
    //         $this->customerName = $order['customer'];
    //         $this->cart = $order['items'];

    //         // Remove from held orders
    //         unset($this->heldOrders[$index]);
    //         $this->heldOrders = array_values($this->heldOrders);

    //         $this->showHeldOrdersModal = false;
    //         $this->dispatch('cartUpdated');
    //         $this->dispatch('showAlert', 'Pesanan berhasil dimuat ke keranjang', 'success');
    //     }
    // }

    // public function toggleHeldOrdersModal()
    // {
    //     $this->showHeldOrdersModal = !$this->showHeldOrdersModal;
    // }

    // public function getTotal()
    // {
    //     return $this->getTotal();

    // }
}

