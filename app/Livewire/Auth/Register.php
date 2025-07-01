<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $username, $email, $password, $country, $agree = false;

    public function register()
    {
        $this->validate([
            'username' => 'required|min:3',
            // 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // dd($this->username);
        return redirect('/login_1')->with('success', 'Akun berhasil dibuat!');
    }
    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}
