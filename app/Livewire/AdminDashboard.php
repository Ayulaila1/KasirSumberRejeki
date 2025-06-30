<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    use WithFileUploads;

    public $currentPage = 'dashboard';
    public $showSidebar = false;
    public $showDropdown = false;
    public $showTransactionModal = false;
    public $selectedTransaction = null;
    public $revenueYear = '2025';
    public $bestSellerMonth = 'current';

    protected $listeners = ['closeModal'];

    public function render()
    {
        return view('livewire.admin-dashboard.compone');
    }

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function navigateTo($page)
    {
        $this->currentPage = $page;
        $this->showSidebar = false;
    }

    public function viewTransaction($transactionId)
    {
        $this->selectedTransaction = $this->getTransactionDetails($transactionId);
        $this->showTransactionModal = true;
    }

    public function closeModal()
    {
        $this->showTransactionModal = false;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    protected function getTransactionDetails($id)
    {
        // In a real app, you would fetch this from the database
        $transactions = [
            'TRX-20250628-001' => [
                'id' => 'TRX-20250628-001',
                'date' => '28 Jun 2025',
                'customer' => 'Pelanggan 1',
                'status' => 'Selesai',
                'items' => [
                    ['product' => 'Cappuccino', 'price' => 'Rp 25.000', 'qty' => 2, 'subtotal' => 'Rp 50.000'],
                    ['product' => 'Teh Tarik', 'price' => 'Rp 15.000', 'qty' => 1, 'subtotal' => 'Rp 15.000'],
                    ['product' => 'Nasi Goreng Spesial', 'price' => 'Rp 30.000', 'qty' => 2, 'subtotal' => 'Rp 60.000'],
                ],
                'total' => 'Rp 125.000'
            ],
            // Add other transactions as needed
        ];

        return $transactions[$id] ?? null;
    }

    public function getRevenueData()
    {
        return $this->revenueYear === '2024'
            ? [7200000, 7800000, 8200000, 8800000, 9500000, 10200000, 9800000, 9200000, 8900000, 8500000, 9200000, 9700000]
            : [8500000, 9200000, 10500000, 9800000, 11200000, 12450000, 0, 0, 0, 0, 0, 0];
    }

    public function getBestSellerData()
    {
        return $this->bestSellerMonth === 'last'
            ? [38, 32, 25, 18, 12]
            : [45, 38, 28, 22, 18];
    }
}
