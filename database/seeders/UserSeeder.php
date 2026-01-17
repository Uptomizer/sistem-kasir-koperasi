<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_user' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'nama_user' => 'Kasir',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir'
            ]
        );
    }
}

