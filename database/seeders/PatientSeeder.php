<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Patient::firstOrCreate(
            ['phone' => '0901234567'],
            [
                'full_name'  => 'Nguyễn Văn A',
                'gender'     => 1,
                'birth_year' => 1998,
                'address'    => 'TP. Hồ Chí Minh',
            ]
        );

        Patient::firstOrCreate(
            ['phone' => '0901230432'],
            [
                'full_name'  => 'Nguyễn Văn B',
                'gender'     => 1,
                'birth_year' => 1997,
                'address'    => 'Hà Nội',
            ]
        );

        Patient::firstOrCreate(
            ['phone' => '0909684325'],
            [
                'full_name'  => 'Nguyễn Thị C',
                'gender'     => 0,
                'birth_year' => 2001,
                'address'    => 'TP. Hồ Chí Minh',
            ]
        );
    }

}
