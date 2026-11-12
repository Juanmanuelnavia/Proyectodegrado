<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@veterinaria.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '3001234567',
        ]);

        User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'veterinario@veterinaria.com',
            'password' => Hash::make('password123'),
            'role' => 'veterinario',
            'phone' => '3009876543',
        ]);

        User::create([
            'name' => 'María López',
            'email' => 'recepcionista@veterinaria.com',
            'password' => Hash::make('password123'),
            'role' => 'recepcionista',
            'phone' => '3005555555',
        ]);
    }
}
