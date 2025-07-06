<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class PenggunaComponent extends Component
{
    use WithPagination;

    public $name, $email, $password, $password_confirmation;
    public $userId;
    public $isEdit = false;
    public $showModal = false;
    public $showDeleteModal = false;
    public $userToDelete;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    public function render()
    {
        $users = User::latest()->paginate(10);
        return view('livewire.pengguna-component', compact('users'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEdit = true;
        $this->showModal = true;

        // Reset password validation rules for edit
        $this->rules['password'] = 'nullable|min:6|confirmed';
        $this->rules['email'] = ['required', 'email', Rule::unique('users')->ignore($user->id)];
    }

    public function saveUser()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->isEdit) {
            User::find($this->userId)->update($data);
            session()->flash('message', 'Pengguna berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('message', 'Pengguna berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function confirmDelete($id)
    {
        $this->userToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        User::find($this->userToDelete)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'userId']);
        $this->resetErrorBag();
        $this->rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
