<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_record_id',
        'total_amount',
        'examination_fee',
        'medicine_fee',
        'status',
        'payment_method'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medical_record()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
