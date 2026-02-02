<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorRole = Role::where("name", "Bác sĩ")->first();
        $doctors = User::where("role_id", $doctorRole->id)->get();
        foreach($doctors as $doctor) {
            Doctor::firstOrCreate(
                ["user_id" => $doctor->id],
                [
                    "specialty" => "Da lieu tong hop",
                    "is_free" => true
                ]
            );
            
        }
    }
}
