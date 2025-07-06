<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class PengaturanComponent extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';
    public $storeSettings = [
        'nama_toko' => 'Toko Saya',
        'alamat_toko' => 'Jl. Contoh No. 123',
        'telepon' => '08123456789',
        'email' => 'toko@example.com',
        'logo' => null,
    ];
    public $notificationSettings = [
        'stok_minimum' => '1',
        'email_notifikasi' => 'admin@example.com',
    ];
    public $productSettings = [
        'satuan_default' => 'pcs',
        'stok_minimum_default' => 5,
        'kategori_produk' => 'Sachet, Racikan, Bahan Baku',
    ];
    public $supplierSettings = [
        'notifikasi_restock' => '1',
        'email_supplier_default' => 'supplier@example.com',
    ];
    public $newUser = [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => '',
        'role' => 'kasir',
    ];
    public $users;
    public $logoPreview;
    public $backupStatus = '29 Juni 2025, 15:30 WIB';

    protected $rules = [
        'storeSettings.nama_toko' => 'required|string|max:100',
        'storeSettings.alamat_toko' => 'required|string|max:255',
        'storeSettings.telepon' => 'required|string|max:20',
        'storeSettings.email' => 'required|email|max:100',
        'storeSettings.logo' => 'nullable|image|max:2048',
        'notificationSettings.stok_minimum' => 'required|in:1,0',
        'notificationSettings.email_notifikasi' => 'required|email|max:100',
        'productSettings.satuan_default' => 'required|in:pcs,kg,gr,ml,l',
        'productSettings.stok_minimum_default' => 'required|integer|min:1',
        'productSettings.kategori_produk' => 'required|string',
        'supplierSettings.notifikasi_restock' => 'required|in:1,0',
        'supplierSettings.email_supplier_default' => 'required|email|max:100',
        'newUser.name' => 'required|string|max:255',
        'newUser.email' => 'required|email|unique:users,email|max:255',
        'newUser.password' => 'required|string|min:8|confirmed',
        'newUser.role' => 'required|in:admin,kasir,gudang',
    ];

    public function mount()
    {
        $this->users = User::all();
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function saveStoreSettings()
    {
        $this->validate([
            'storeSettings.nama_toko' => 'required|string|max:100',
            'storeSettings.alamat_toko' => 'required|string|max:255',
            'storeSettings.telepon' => 'required|string|max:20',
            'storeSettings.email' => 'required|email|max:100',
            'storeSettings.logo' => 'nullable|image|max:2048',
        ]);

        if ($this->storeSettings['logo']) {
            $logoPath = $this->storeSettings['logo']->store('public/logos');
            $this->storeSettings['logo'] = Storage::url($logoPath);
            $this->logoPreview = $this->storeSettings['logo'];
        }

        // Save to database or config here
        session()->flash('message', 'Pengaturan toko berhasil disimpan');
    }

    public function saveNotificationSettings()
    {
        $this->validate([
            'notificationSettings.stok_minimum' => 'required|in:1,0',
            'notificationSettings.email_notifikasi' => 'required|email|max:100',
        ]);

        // Save to database or config here
        session()->flash('message', 'Pengaturan notifikasi berhasil disimpan');
    }

    public function saveProductSettings()
    {
        $this->validate([
            'productSettings.satuan_default' => 'required|in:pcs,kg,gr,ml,l',
            'productSettings.stok_minimum_default' => 'required|integer|min:1',
            'productSettings.kategori_produk' => 'required|string',
        ]);

        // Save to database or config here
        session()->flash('message', 'Pengaturan produk berhasil disimpan');
    }

    public function saveSupplierSettings()
    {
        $this->validate([
            'supplierSettings.notifikasi_restock' => 'required|in:1,0',
            'supplierSettings.email_supplier_default' => 'required|email|max:100',
        ]);

        // Save to database or config here
        session()->flash('message', 'Pengaturan supplier berhasil disimpan');
    }

    public function createUser()
    {
        $this->validate([
            'newUser.name' => 'required|string|max:255',
            'newUser.email' => 'required|email|unique:users,email|max:255',
            'newUser.password' => 'required|string|min:8|confirmed',
            'newUser.role' => 'required|in:admin,kasir,gudang',
        ]);

        User::create([
            'name' => $this->newUser['name'],
            'email' => $this->newUser['email'],
            'password' => bcrypt($this->newUser['password']),
            'role' => $this->newUser['role'],
        ]);

        $this->reset('newUser');
        $this->users = User::all();
        session()->flash('message', 'Pengguna berhasil ditambahkan');
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $this->users = $this->users->filter(function ($user) use ($userId) {
                return $user->id != $userId;
            });
            session()->flash('message', 'Pengguna berhasil dihapus');
        }
    }

    public function createBackup()
    {
        // Implement backup logic here
        $this->backupStatus = now()->format('d F Y, H:i') . ' WIB';
        session()->flash('message', 'Backup berhasil dibuat');
    }

    public function restoreBackup()
    {
        // Implement restore logic here
        session()->flash('message', 'Restore berhasil dilakukan');
    }

    public function render()
    {
        return view('livewire.pengaturan-component');
    }
}
