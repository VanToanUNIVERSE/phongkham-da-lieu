<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'quantity',
        'dosage',
        'usage'
    ];
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
