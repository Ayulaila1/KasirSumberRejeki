<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;
use App\Models\Penjualan;
use Illuminate\Support\Str;
use App\Models\PenjualanDtl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


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
    public $notes = '';

    // State modal
    public $showConfirmModal = false; // Modal Konfirmasi
    public $showReceipt = false;      // Modal Struk
    public $receiptData = [];

    protected $listeners = ['produkDipilih' => 'addToCart'];

    protected function printReceipt($receiptData)
    {
        try {
            // === KONEKSI PRINTER ===
            $connector = new WindowsPrintConnector("POS-58"); // ganti nama printer sesuai yg ada di Devices & Printers
            $printer = new Printer($connector);

            // === HEADER TOKO ===
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("Cafe Suki\n");
            $printer->setEmphasis(false);
            $printer->text("Jl. Contoh Alamat No. 123\n");
            $printer->text("Telp: 0812-3456-7890\n");
            $printer->text("==============================\n");

            // === INFO TRANSAKSI ===
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No. Trx : " . $receiptData['number'] . "\n");
            $printer->text("Tanggal : " . $receiptData['date'] . "\n");
            $printer->text("Meja    : " . $receiptData['table'] . "\n");
            $printer->text("Pelanggan: " . $receiptData['customer'] . "\n");
            $printer->text("------------------------------\n");

            // === ITEM LIST ===
            foreach ($receiptData['items'] as $item) {
                // Nama produk
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($item['name'] . "\n");

                // Qty x Harga
                $line = sprintf(
                    "  %2s x %-8s Rp %s",
                    $item['quantity'],
                    number_format($item['price'], 0, ',', '.'),
                    number_format($item['price'] * $item['quantity'], 0, ',', '.')
                );
                $printer->text($line . "\n");
            }

            $printer->text("------------------------------\n");

            // === TOTAL & PEMBAYARAN ===
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true);
            $printer->text("TOTAL : Rp " . number_format($receiptData['total'], 0, ',', '.') . "\n");
            $printer->setEmphasis(false);

            $printer->text("Bayar : Rp " . number_format($receiptData['cash'], 0, ',', '.') . "\n");
            $printer->text("Kembali: Rp " . number_format($receiptData['change'], 0, ',', '.') . "\n");
            $printer->text("==============================\n");

            // === FOOTER ===
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima Kasih\n");
            $printer->text("Semoga Puas dengan Layanan Kami\n");
            $printer->feed(3); // spasi kosong biar rapi

            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            logger()->error("Gagal cetak struk: " . $e->getMessage());
        }
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

    // ================= Alur Sesuai Diagram =================

    // Step 1: Buka Modal Konfirmasi
    public function openConfirmModal()
    {
        if (empty($this->cart)) {
            $this->dispatch('showAlert', 'Keranjang masih kosong', 'danger');
            return;
        }
        $this->showConfirmModal = true;
    }

    // Step 2: Klik Konfirmasi → Tutup Modal Konfirmasi → Lanjut proses
    public function confirmPayment()
    {
        $this->showConfirmModal = false;
        $this->processPayment();
        $this->showReceipt = true;

        // 🔥 Cetak otomatis ke printer thermal
        $this->printReceipt($this->receiptData);
    }

    // Step 3: Proses transaksi
    public function processPayment()
    {
        try {
            DB::beginTransaction();

            $penjualan = Penjualan::create([
                'kode_penjualan' => 'TRX-' . now()->format('Ymd') . '-' . Str::random(4),
                'tanggal' => now()->format('Y-m-d'),
                'total' => $this->getTotal(),
                'bayar' => $this->cashAmount,
                'kembalian' => $this->cashAmount - $this->getTotal(),
                'user_id' => Auth::user()->id,
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
                'customer' => $this->customerName ?: '-',
                'table' => $this->tableNumber,
                'items' => $this->cart,
                'total' => $this->getTotal(),
                'cash' => $this->cashAmount,
                'change' => $this->cashAmount - $this->getTotal(),
            ];

            $this->clearCart();

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

    public function clearCart()
    {
        $this->cart = [];
        $this->cashAmount = 0;
        $this->change = 0;
    }
}
