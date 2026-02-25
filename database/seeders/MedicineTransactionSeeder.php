<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineTransaction;

class MedicineTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            [
                'medicine_id' => 1,
                'type' => 'import',
                'quantity' => 200,
                'note' => 'Nhập kho ban đầu',
                'user_id' => 15,
            ],
            [
                'medicine_id' => 2,
                'type' => 'import',
                'quantity' => 150,
                'note' => 'Nhập kho ban đầu',
                'user_id' => 15,
            ],
            [
                'medicine_id' => 1,
                'type' => 'export',
                'quantity' => 5,
                'note' => 'Phát thuốc test',
                'user_id' => 6,
            ],
        ];

        foreach ($transactions as $data) {
            MedicineTransaction::firstOrCreate(
                [
                    'medicine_id' => $data['medicine_id'],
                    'type' => $data['type'],
                    'quantity' => $data['quantity'],
                    'note' => $data['note'],
                ],
                $data
            );
        }
    }
}
