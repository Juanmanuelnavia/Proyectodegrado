<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'medical_record_id', 'condition',
        'medication', 'dosage', 'next_visit', 'notes',
        'vaccine_name', 'vaccine_date', 'deworming_date'
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
