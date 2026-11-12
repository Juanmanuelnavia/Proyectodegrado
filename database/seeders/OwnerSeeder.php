<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            ['name' => 'Carlos Andrés Martínez', 'phone' => '3001234567', 'email' => 'carlos@gmail.com', 'address' => 'Calle 5 # 10-23, El Caney', 'document' => '1094567890'],
            ['name' => 'Laura Sofía Gómez', 'phone' => '3109876543', 'email' => 'laura@gmail.com', 'address' => 'Carrera 8 # 15-40, Ciudad Jardín', 'document' => '1094321456'],
            ['name' => 'Jorge Luis Pérez', 'phone' => '3205554433', 'email' => 'jorge@gmail.com', 'address' => 'Av. 6N # 25-10, Chipichape', 'document' => '1093456789'],
            ['name' => 'Valentina Torres', 'phone' => '3157778899', 'email' => 'valentina@gmail.com', 'address' => 'Calle 18 # 4-56, San Fernando', 'document' => '1092345678'],
            ['name' => 'Andrés Felipe Ruiz', 'phone' => '3002223344', 'email' => 'andres@gmail.com', 'address' => 'Carrera 15 # 8-90, Limonar', 'document' => '1091234567'],
            ['name' => 'María Camila López', 'phone' => '3118889900', 'email' => 'maria@gmail.com', 'address' => 'Calle 10 # 6-78, El Peñón', 'document' => '1097654321'],
            ['name' => 'Santiago Herrera', 'phone' => '3204445566', 'email' => 'santiago@gmail.com', 'address' => 'Av. Roosevelt # 20-15, Granada', 'document' => '1096543210'],
        ];

        foreach ($owners as $owner) {
            Owner::create($owner);
        }
    }
}
