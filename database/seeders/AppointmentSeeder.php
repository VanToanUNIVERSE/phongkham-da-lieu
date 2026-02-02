<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        foreach($doctors as $doctor) {
            foreach($patients as $patient) {
                Appointment::firstOrCreate(
                    [
                        "doctor_id" => $doctor->id,
                        "patient_id" => $patient->id
                    ],
                    [
                        "doctor_id" => $doctor->id,
                        "patient_id" => $patient->id,
                        "date" => now(),
                        "time" => sprintf('%02d:00:00', rand(8, 16))
                    ]
                );
            }
        }
    }
}
