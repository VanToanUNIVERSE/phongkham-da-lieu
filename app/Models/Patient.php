<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        "full_name",
        "gender",
        "birth_year",
        "phone",
        "address"
    ];
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
