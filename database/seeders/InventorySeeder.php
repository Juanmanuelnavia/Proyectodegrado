<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryMovement;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Amoxicilina 500mg', 'category' => 'medicamento', 'unit' => 'frasco', 'stock' => 25, 'min_stock' => 10, 'expiry_date' => '2027-06-01', 'price' => 18000, 'supplier' => 'Laboratorios MK'],
            ['name' => 'Vacuna Rabia', 'category' => 'vacuna', 'unit' => 'ampolla', 'stock' => 3, 'min_stock' => 10, 'expiry_date' => '2026-12-01', 'price' => 35000, 'supplier' => 'Zoetis'],
            ['name' => 'Metronidazol 250mg', 'category' => 'medicamento', 'unit' => 'caja', 'stock' => 15, 'min_stock' => 5, 'expiry_date' => '2027-03-15', 'price' => 12000, 'supplier' => 'Genfar'],
            ['name' => 'Vacuna Parvovirus', 'category' => 'vacuna', 'unit' => 'ampolla', 'stock' => 4, 'min_stock' => 8, 'expiry_date' => '2026-09-30', 'price' => 42000, 'supplier' => 'Zoetis'],
            ['name' => 'Jeringas 5ml', 'category' => 'insumo', 'unit' => 'caja', 'stock' => 50, 'min_stock' => 20, 'expiry_date' => null, 'price' => 8000, 'supplier' => 'Nipro'],
            ['name' => 'Guantes látex', 'category' => 'insumo', 'unit' => 'caja', 'stock' => 8, 'min_stock' => 10, 'expiry_date' => null, 'price' => 15000, 'supplier' => 'Medline'],
            ['name' => 'Ivermectina 1%', 'category' => 'medicamento', 'unit' => 'frasco', 'stock' => 20, 'min_stock' => 5, 'expiry_date' => '2027-01-20', 'price' => 22000, 'supplier' => 'Laboratorios MK'],
            ['name' => 'Alimento Royal Canin', 'category' => 'alimento', 'unit' => 'unidad', 'stock' => 12, 'min_stock' => 5, 'expiry_date' => '2026-08-01', 'price' => 85000, 'supplier' => 'Royal Canin'],
        ];

        foreach ($items as $item) {
            $inventory = Inventory::create($item);
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'user_id'      => 1,
                'type'         => 'entrada',
                'quantity'     => $item['stock'],
                'reason'       => 'Stock inicial',
            ]);
        }
    }
}