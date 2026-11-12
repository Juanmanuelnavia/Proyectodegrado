<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'name', 'category', 'unit', 'stock',
        'min_stock', 'expiry_date', 'price',
        'supplier', 'description'
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }
}
