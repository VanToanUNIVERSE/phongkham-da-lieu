<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {
            MedicalRecord::firstOrCreate(
                [
                    'appointment_id' => $appointment->id,
                ],
                [
                    'doctor_id'  => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'diagnosis'  => 'Viêm da dị ứng',
                    'examination_result' => 'Tình trạng da ổn định, cần theo dõi',
                ]
            );
        }
    }
}
