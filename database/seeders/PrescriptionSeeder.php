<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicalDispenser = User::where("role_id", 4)->first();
        $medicalRecords = MedicalRecord::all();
        foreach($medicalRecords as $s) {
            Prescription::firstOrCreate(
            [
                "medical_record_id" => $s->id
            ],
            [
                "medical_record_id" => $s->id,
                "dispensed_by" => $medicalDispenser->id,
                "dispense_status" => "Chưa phát",
                "content" => "Thuốc chữa cho da nhạy "
            ]
        );
        }
        
    }
}
