<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'pet_id', 'owner_id', 'user_id',
        'scheduled_at', 'status', 'reason', 'notes'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function invoice()
{
    return $this->hasOne(Invoice::class);
}

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
