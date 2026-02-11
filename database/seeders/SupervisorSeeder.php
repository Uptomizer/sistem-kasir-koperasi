<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate([
            'username' => 'supervisor',
        ], [
            'nama_user' => 'Supervisor Toko',
            'password' => bcrypt('password'), // Pasword default
            'role' => 'supervisor',
        ]);
    }
}
