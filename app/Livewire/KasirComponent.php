<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Hold;
use App\Models\Bahan;
use App\Models\Produk;
use Livewire\Component;
use App\Models\Penjualan;
// use Mike42\Escpos\Printer;
use Illuminate\Support\Str;
use App\Models\PenjualanDtl;
use App\Models\ProdukRacikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class KasirComponent extends Component
{
    // ========== STATE ==========
    public $cart = []; // associative keyed by productId: [productId => ['id'=>..., 'name'=>..., 'price'=>..., 'quantity'=>...], ...]
    public $holds = []; // optional, for UI list of holds

    public $search = '';
    public $selectedCategory = 'Semua';
    public $customerName = '';
    public $catatan;
    public $products = [];
    public $tableNumber = '';
    public $paymentMethod = 'cash';
    public $cashAmount;
    public $cashFormatted;
    public $change = 0;
    public $orderNotes = '';

    public $showModal = false;
    public $selectedProduk;
    public $ingredients = [];

    public $showConfirmModal = false; // modal konfirmasi bayar
    public $showReceipt = false;      // tampilkan struk
    public $receiptData = [];

    protected $listeners = [
        'produkDipilih' => 'addToCart',
        'resumeFromHold' => 'resumeFromHold',
        'deleteHold',
    ];

    // ========== PRINTER (tetap seperti sebelumnya) ==========
    protected function printReceipt($receiptData)
    {
        try {
            // 1️⃣ Render HTML struk
            $strukHTML = view('layouts.printkasir', $receiptData)->render();

            // 2️⃣ Simpan ke folder public (bisa dibuka browser)
            $fileName = 'struk-' . now()->format('YmdHis') . '.html';
            $filePath = storage_path('app/public/struk/' . $fileName);

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            file_put_contents($filePath, $strukHTML);

            // 3️⃣ Buat URL publik ke struk
            $publicUrl = asset('storage/struk/' . $fileName);

            // 4️⃣ Arahkan browser (bisa ke aplikasi PrinterA atau tab baru)
            $this->dispatch('redirectToPrinterA', $publicUrl);

        } catch (\Exception $e) {
            logger()->error("❌ Gagal buat struk manual: " . $e->getMessage());
            $this->dispatch('showAlert', 'Gagal menyiapkan struk.', 'danger');
        }
    }


    // ========== LIFECYCLE ==========
    public function mount($id = null)
    {
        // load produk awal
        $this->products = Produk::all();
        $this->cart = $this->cart ?? [];

        // jika mount dengan id hold (opsional), isi cart dari hold tanpa mengurangi stok
        if ($id) {
            $this->resumeFromHold($id);
        }
    }

    // ========== UTILS ==========
    public function updatedCashFormatted($value)
    {
        $numeric = preg_replace('/[^0-9]/', '', $value);
        $this->cashAmount = (int) $numeric;
        $this->cashFormatted = $this->formatRupiah($numeric);
    }

    private function formatRupiah($angka)
    {
        if (!$angka)
            return '';
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function showIngredients($produkId)
    {
        $this->selectedProduk = Produk::find($produkId);
        $this->ingredients = ProdukRacikan::with('bahan')
            ->where('produk_idproduk', $produkId)
            ->get();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduk = null;
        $this->ingredients = [];
    }

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

        $categories = ['Semua'] + Produk::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        $this->updateCartTotals();

        return view('livewire.kasir-component', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.kasirlayout');
    }

    // ========== CART & STOCK LOGIC ==========

    /**
     * Tambah produk ke cart dan reserve stock langsung.
     * Jika produk racikan: kurangi stok bahan sesuai takaran.
     */
    public function addToCart($productId, $quantity = 1)
    {
        $product = Produk::with('produkDetails.bahan')->find($productId);
        if (!$product)
            return;

        // hitung stok tersedia (accessor atau kolom stok)
        $stokTersisa = $product->stok_tersedia ?? $product->stok;
        $existingQty = isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] : 0;
        $requestedTotal = $existingQty + $quantity;

        if ($requestedTotal > $stokTersisa) {
            $this->dispatch('showAlert', 'Stok tidak cukup', 'danger');
            return;
        }

        // update cart
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] += $quantity;
        } else {
            $this->cart[$productId] = [
                'id' => $product->idproduk,
                'name' => $product->nama,
                'price' => $product->harga_jual ?? 0,
                'quantity' => $quantity,
            ];
        }

        // reserve stock immediately
        $this->kurangiStokForItem(['id' => $productId, 'quantity' => $quantity]);

        // refresh products (so accessor badges update)
        $this->products = Produk::all();
        $this->dispatch('cartUpdated');
    }

    /**
     * Ubah quantity: delta bisa positif (tambah) atau negatif (kurangi).
     * Jika naik, cek stok lalu reserve tambahan. Jika turun, restore stok.
     */
    public function updateQty($productId, $delta = 1)
    {
        if (!isset($this->cart[$productId]))
            return;
        $delta = intval($delta);
        if ($delta === 0)
            return;

        $product = Produk::with('produkDetails.bahan')->find($productId);
        if (!$product)
            return;

        if ($delta > 0) {
            $stokTersisa = $product->stok_tersedia ?? $product->stok;
            $currentQty = $this->cart[$productId]['quantity'];
            if ($currentQty + $delta > $stokTersisa) {
                $this->dispatch('showAlert', 'Stok tidak cukup untuk menambah quantity.', 'danger');
                return;
            }
            $this->cart[$productId]['quantity'] += $delta;
            $this->kurangiStokForItem(['id' => $productId, 'quantity' => $delta]);
        } else {
            $abs = abs($delta);
            $currentQty = $this->cart[$productId]['quantity'];
            $newQty = $currentQty - $abs;
            if ($newQty <= 0) {
                // hapus item & restore stok penuh
                $this->restoreStockForItem($this->cart[$productId]);
                unset($this->cart[$productId]);
            } else {
                // kurangi qty di cart & restore sebagian stok
                $this->cart[$productId]['quantity'] = $newQty;
                $this->restorePartialStock($product, $abs);
            }
        }

        $this->products = Produk::all();
        $this->dispatch('cartUpdated');
    }

    /**
     * Hapus satu item dari cart lalu restore stok sesuai quantity yang dihapus.
     */
    public function removeItem($productId)
    {
        if (!isset($this->cart[$productId]))
            return;
        $item = $this->cart[$productId];
        $this->restoreStockForItem($item);
        unset($this->cart[$productId]);
        $this->products = Produk::all();
        $this->dispatch('cartUpdated');
    }

    /**
     * Kembalikan stok penuh untuk 1 item cart (dipakai saat hapus / batalkan).
     * Item format: ['id'=>..., 'quantity'=>...]
     */
    protected function restoreStockForItem($item)
    {
        $product = Produk::with('produkDetails.bahan')->find($item['id']);
        if (!$product)
            return;
        $qty = $item['quantity'] ?? 0;
        if ($qty <= 0)
            return;

        if ($product->produkDetails->isEmpty()) {
            $product->stok += $qty;
            $product->save();
        } else {
            foreach ($product->produkDetails as $detail) {
                if ($detail->bahan && $detail->takaran > 0) {
                    $detail->bahan->stok += ($detail->takaran * $qty);
                    $detail->bahan->save();
                }
            }
        }
    }

    /**
     * Restore sebagian stok untuk product object (dipakai saat mengurangi qty).
     */
    protected function restorePartialStock($product, $qty)
    {
        if ($product->produkDetails->isEmpty()) {
            $product->stok += $qty;
            $product->save();
        } else {
            foreach ($product->produkDetails as $detail) {
                if ($detail->bahan && $detail->takaran > 0) {
                    $detail->bahan->stok += ($detail->takaran * $qty);
                    $detail->bahan->save();
                }
            }
        }
    }

    /**
     * Kurangi stok untuk sebuah item (reserve / consume).
     * Item: ['id'=>..., 'quantity'=>...]
     */
    protected function kurangiStokForItem($item)
    {
        $id = $item['id'];
        $qty = $item['quantity'] ?? 1;
        $product = Produk::with('produkDetails.bahan')->find($id);
        if (!$product)
            return;

        if ($product->produkDetails->isEmpty()) {
            $product->stok = max(0, $product->stok - $qty);
            $product->save();
        } else {
            foreach ($product->produkDetails as $detail) {
                if ($detail->bahan && $detail->takaran > 0) {
                    $detail->bahan->stok = max(0, $detail->bahan->stok - ($detail->takaran * $qty));
                    $detail->bahan->save();
                }
            }
        }
    }

    public function setQuantity($productId, $quantity)
    {
        $quantity = max(1, intval($quantity));
        if (!isset($this->cart[$productId]))
            return;
        $current = $this->cart[$productId]['quantity'];
        $diff = $quantity - $current;
        if ($diff === 0)
            return;
        $this->updateQty($productId, $diff);
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
    }

    // ========== PAYMENT FLOW ==========

    public function openConfirmModal()
    {
        if (empty($this->cart)) {
            $this->dispatch('showAlert', 'Keranjang masih kosong', 'danger');
            return;
        }
        $this->showConfirmModal = true;
    }

    public function confirmPayment()
    {
        if (empty($this->cart)) {
            $this->dispatch('showAlert', 'Keranjang masih kosong', 'danger');
            return;
        }

        $this->showConfirmModal = false;

        $penjualan = $this->processPayment();

        if ($penjualan) {
            // Siapkan data untuk struk
            $this->receiptData = [
                'storeName' => 'Cafe Sumber Rejeki',
                'storeAddress' => 'Jl. Mawar No.10, Bandung',
                'storePhone' => '0812-3456-7890',
                'number' => $penjualan->kode_penjualan,
                'date' => now()->format('d/m/Y H:i:s'),
                'customer' => $this->customerName ?: '-',
                'table' => $this->tableNumber ?: '-',
                'notes' => $this->orderNotes ?: '-',
                'items' => array_values($this->cart),
                'total' => $this->getTotal(),
                'cash' => $this->cashAmount,
                'change' => $this->cashAmount - $this->getTotal(),
            ];

            // 1️⃣ Cetak struk manual (buka ke PrinterA)
            $this->printReceipt($this->receiptData);

            // 2️⃣ Tampilkan notifikasi sukses
            $this->dispatch('showAlert', 'Pembayaran berhasil dan struk siap dicetak.', 'success');

            // 3️⃣ Bersihkan cart
            $this->clearCart(false);
        } else {
            $this->dispatch('showAlert', 'Terjadi kesalahan saat menyimpan transaksi.', 'danger');
        }
    }

    /**
     * Proses penyimpanan transaksi final (penjualan).
     * Stok tidak dikurangi di sini karena sudah di-reserve di addToCart/hold.
     */
    public function processPayment()
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            $penjualan = Penjualan::create([
                'kode_penjualan' => 'TRX-' . now()->format('Ymd') . '-' . Str::random(4),
                'customer_name' => $this->customerName,
                'no_meja' => $this->tableNumber,
                'catatan' => $this->orderNotes,
                'tanggal' => now()->format('Y-m-d'),
                'total' => $this->getTotal(),
                'bayar' => $this->cashAmount,
                'kembalian' => $this->cashAmount - $this->getTotal(),
                'user_iduser' => $user->iduser ?? $user->id, // otomatis isi user aktif
                'shift' => $user->shift ?? null,              // otomatis isi shift user login
            ]);


            foreach ($this->cart as $item) {
                PenjualanDtl::create([
                    'penjualan_idpenjualan' => $penjualan->idpenjualan,
                    'produk_idproduk' => $item['id'],
                    'qty' => $item['quantity'],
                    'harga_jual' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            $this->receiptData = [
                'number' => $penjualan->kode_penjualan,
                'date' => now()->format('d/m/Y H:i:s'),
                'customer_name' => $this->customerName ?: '-',
                'no_meja' => $this->tableNumber ?: '-',
                'catatan' => $this->orderNotes ?: '-',
                'items' => array_values($this->cart),
                'total' => $this->getTotal(),
                'cash' => $this->cashAmount,
                'change' => $this->cashAmount - $this->getTotal(),
            ];

            // Clear cart tanpa restore stok (stok sudah dikurangi di addToCart/hold)
            $this->clearCart(false);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Gagal memproses pembayaran: ' . $e->getMessage());
            $this->dispatch('showAlert', 'Terjadi kesalahan saat memproses pembayaran.', 'danger');
        }
    }

    public function closeReceipt()
    {
        $this->showReceipt = false;
    }

    protected function updateCartTotals()
    {
        $this->dispatch('updateCartTotals', [
            'total' => $this->getTotal(),
        ]);
    }

    public function getTotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    /**
     * Clear cart
     * $restoreStock = true -> restore stok untuk semua item (dipakai saat cancel)
     * $restoreStock = false -> kosongkan tanpa restore (dipakai setelah payment finalize atau setelah hold)
     */
    public function clearCart($restoreStock = true)
    {
        if ($restoreStock) {
            foreach ($this->cart as $item) {
                $this->restoreStockForItem($item);
            }
        }

        $this->cart = [];
        $this->cashAmount = 0;
        $this->cashFormatted = '';
        $this->change = 0;
        $this->customerName = '';
        $this->tableNumber = '';
        $this->orderNotes = '';

        $this->products = Produk::all();
        $this->dispatch('cartUpdated');
    }

    // ========== HOLD FLOW ==========

    /**
     * Hold: simpan transaksi di tabel holds.
     * IMPORTANT: stok sudah berkurang saat addToCart, jadi di sini kita TIDAK mengembalikan stok.
     */
    public function hold()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Tidak ada item di keranjang.');
            return;
        }

        $kode = 'TRX-' . now()->format('Ymd') . '-' . Str::random(4);
        $total = collect($this->cart)->sum(fn($item) => ($item['quantity'] ?? 1) * ($item['price'] ?? 0));

        $user = Auth::user();

        Hold::create([
            'kode_transaksi' => $kode,
            'customer' => $this->customerName ?: '-',
            'table_number' => $this->tableNumber ?: '-',
            'notes' => $this->orderNotes ?: '-',
            'items' => array_values($this->cart),
            'total' => $total,
            'user_iduser' => $user->iduser ?? $user->id,
            'shift' => $user->shift ?? null,
        ]);

        // kosongkan cart tanpa restore stok (karena stok sudah dikurangi waktu addToCart)
        $this->clearCart(false);

        session()->flash('success', 'Transaksi berhasil di-hold (stok dipertahankan).');
    }

    /**
     * Resume dari hold: load cart dari hold tanpa mengurangi stok lagi.
     * Catatan: fungsi ini menghapus record hold setelah dimuat.
     */
    public function resumeFromHold($id)
    {
        $hold = Hold::findOrFail($id);
        $items = is_string($hold->items) ? json_decode($hold->items, true) : $hold->items;

        // isi cart langsung tanpa memanggil addToCart (agar stok tidak dikurangi lagi)
        $this->cart = [];
        foreach ($items as $item) {
            $this->cart[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ];
        }

        $this->customerName = $hold->customer;
        $this->tableNumber = $hold->table_number;
        $this->orderNotes = $hold->notes;

        // hapus hold (opsional: kalau mau simpan hingga pembayaran selesai, jangan hapus di sini)
        $hold->delete();

        $this->dispatch('cartUpdated');
        session()->flash('success', 'Transaksi hold berhasil dilanjutkan.');
    }

    /**
     * Batalkan hold: kembalikan stok jika transaksi dibatalkan, lalu hapus record hold.
     */
    public function deleteHold($holdId)
    {
        $hold = Hold::findOrFail($holdId);
        $items = is_string($hold->items) ? json_decode($hold->items, true) : $hold->items;

        // restore stok karena hold dibatalkan
        foreach ($items as $item) {
            $this->restoreStockForItem($item);
        }

        $hold->delete();
        $this->products = Produk::all();
        session()->flash('success', 'Hold dibatalkan dan stok dikembalikan.');
    }
}
