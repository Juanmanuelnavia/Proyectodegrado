<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OwnerSeeder::class,
            PetSeeder::class,
            AppointmentSeeder::class,
            InventorySeeder::class,
        ]);
    }
}