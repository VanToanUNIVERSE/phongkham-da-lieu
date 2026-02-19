<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $doctorRole = Role::where('name', 'Bác sĩ')->first();
        $MedicalDispenserRole = Role::where('name', 'Nhân viên phát thuốc')->first();
        User::firstOrCreate([
            "username" => "admin"
        ],
        [
            "username" => "admin",
            "password" => Hash::make("admin123"),
            "gender" => 1,
            "birth_year" => 2004,
            "phone" => 0336112240,
            "role_id" => $adminRole->id,
            "full_name" => "Nguyễn Văn Toàn"
        ]);
        User::firstOrCreate([
            "username" => "doctor1"
        ],
        [
            "username" => "doctor1",
            "password" => Hash::make("doctor123"),
            "gender" => 1,
            "birth_year" => 2004,
            "phone" => '03453253653',
            "role_id" => $doctorRole->id,
            "full_name" => "Nguyễn Văn A"
        ]);
        User::firstOrCreate([
            "username" => "medicalDispenser"
        ],
        [
            "username" => "medicalDispenser",
            "password" => Hash::make("medicalDispenser123"),
            "gender" => 0,
            "birth_year" => 2004,
            "phone" => '0998332212',
            "role_id" => $MedicalDispenserRole->id,
            "full_name" => "Nguyễn Văn B"
        ]);
    }
}
