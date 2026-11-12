<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationTemplate extends Model
{
    protected $fillable = [
        'name', 'category', 'symptoms',
        'treatment', 'observations', 'condition'
    ];
}
