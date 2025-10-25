<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin12@gmail.com',
            'password' => Hash::make('rumitsekali'),
            'role' => 'admin',
            'shift' => null, // admin tidak terikat shift
        ]);

        // 🔹 Kasir Shift 1
        User::create([
            'name' => 'Kasir Shift 1',
            'email' => 'shift1@gmail.com',
            'password' => Hash::make('shiftsr1'),
            'role' => 'kasir',
            'shift' => 1,
        ]);

        // 🔹 Kasir Shift 2
        User::create([
            'name' => 'Kasir Shift 2',
            'email' => 'shift2@gmail.com',
            'password' => Hash::make('shiftsr2'),
            'role' => 'kasir',
            'shift' => 2,
        ]);

        // 🔹 Kasir Shift 3
        User::create([
            'name' => 'Kasir Shift 3',
            'email' => 'shift3@gmail.com',
            'password' => Hash::make('shiftsr3'),
            'role' => 'kasir',
            'shift' => 3,
        ]);
    }
}
