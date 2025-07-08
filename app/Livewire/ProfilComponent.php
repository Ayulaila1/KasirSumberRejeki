<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilComponent extends Component
{
    public $editMode = false;
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $confirm_password;
    public $role;
    public $join_date;
    public $last_login;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'current_password' => 'required_with:new_password|current_password',
        'new_password' => ['nullable', 'confirmed', Password::defaults()],
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->join_date = $user->created_at->format('d F Y');
        $this->last_login = now()->format('d F Y, H:i');
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function saveProfile()
    {
        $this->validate();

        $model = config('auth.providers.users.model');
        $user = $model::find(Auth::id());

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }

        $user->save();

        $this->editMode = false;
        session()->flash('message', 'Profile updated successfully!');
    }


    public function render()
    {
        return view('livewire.profil-component');
    }
}
