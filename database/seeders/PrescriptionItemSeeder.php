<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrescriptionItem;

class PrescriptionItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'prescription_id' => 4,
                'medicine_id' => 1,
                'quantity' => 10,
                'dosage' => '1 viên',
                'usage' => 'Ngày 2 lần sau ăn',
            ],
            [
                'prescription_id' => 4,
                'medicine_id' => 3,
                'quantity' => 5,
                'dosage' => '1 viên',
                'usage' => 'Ngày 1 lần',
            ],
        ];

        foreach ($items as $data) {
            PrescriptionItem::firstOrCreate(
                [
                    'prescription_id' => $data['prescription_id'],
                    'medicine_id' => $data['medicine_id'],
                ],
                $data
            );
        }
    }
}
