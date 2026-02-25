<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        'unit',
        'stock',
        'price',
        'expiry_date',
        'description',
        'is_active'
    ];

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(MedicineTransaction::class);
    }
}
