<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'unit' => 'viên',
                'price' => 1500,
                'expiry_date' => '2027-12-31',
                'description' => 'Giảm đau hạ sốt',
                'is_active' => true,
                'stock' => 200,
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'unit' => 'viên',
                'price' => 2500,
                'expiry_date' => '2027-08-30',
                'description' => 'Kháng sinh',
                'is_active' => true,
                'stock' => 150,
            ],
            [
                'name' => 'Vitamin C',
                'unit' => 'viên',
                'price' => 1000,
                'expiry_date' => '2028-01-01',
                'description' => 'Tăng đề kháng',
                'is_active' => true,
                'stock' => 300,
            ],
        ];

        foreach ($medicines as $data) {
            Medicine::firstOrCreate(
                ['name' => $data['name']], // điều kiện tìm
                $data                       // dữ liệu tạo mới
            );
        }
    }
}
