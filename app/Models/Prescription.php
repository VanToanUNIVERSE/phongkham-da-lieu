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
    public function medical_record() {
        return $this->belongsTo(MedicalRecord::class);
    }
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
    public function user() {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function getTotalCostAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->medicine->price;
        });
    }
}
