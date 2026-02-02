<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        "doctor_id",
        "patient_id",
        "date",
        "time",
        "status"
    ];
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
    public function patient() {
        return $this->belongsTo(Patient::class);
    }
    public function medicalRecords()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
