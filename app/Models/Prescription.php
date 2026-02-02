<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = [
        "medical_record_id",
        "dispensed_by", 
        "content",
        "dispense_status"
    ];
    public function Medicaldispenser() {
        return $this->belongsTo(User::class);
    }
    public function medical_record() {
        return $this->belongsTo(MedicalRecord::class);
    }
}
