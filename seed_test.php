<?php

// Find a real doctor
$doctor = App\Models\Doctor::first();
if (!$doctor) {
    // If no doctor, find any user to make a doctor
    $user = App\Models\User::where('role_id', '!=', null)->first();
    if(!$user) {
        $role = App\Models\Role::firstOrCreate(['name' => 'Doctor']);
        $user = App\Models\User::create(['full_name' => 'Dr Test', 'email' => 'drtest@example.com', 'password' => bcrypt('password'), 'role_id' => $role->id, 'phone' => '111', 'gender' => 1, 'address' => 'HN']);
    }
    $doctor = App\Models\Doctor::create(['user_id' => $user->id, 'specialty' => 'Da lieu', 'experience' => '5']);
}

// Find a patient
$patient = App\Models\Patient::first(); 
if(!$patient) { 
    $patient = App\Models\Patient::create(['full_name' => 'John Doe', 'gender' => 1, 'phone' => '0123', 'address' => 'HN']); 
}

// Find an appointment
$appointment = App\Models\Appointment::first();
if (!$appointment) {
    $appointment = App\Models\Appointment::create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id, 'date' => date('Y-m-d'), 'time' => '08:00', 'status' => 'pending']);
}

$m = App\Models\MedicalRecord::create(['patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'appointment_id' => $appointment->id, 'diagnosis' => 'Flu', 'examination_result' => 'Need rest']); 

$med = App\Models\Medicine::first(); 
if(!$med) { 
    $med = App\Models\Medicine::create(['name' => 'Panadol', 'unit' => 'Vien', 'price' => 5000, 'stock' => 100, 'is_active' => 1]); 
} 

$pr = App\Models\Prescription::create(['medical_record_id' => $m->id, 'dispensed_by' => $doctor->user_id, 'content' => 'Take rest']); 
App\Models\PrescriptionItem::create(['prescription_id' => $pr->id, 'medicine_id' => $med->id, 'quantity' => 10, 'dosage' => '1', 'usage' => 'Oral']); 

echo "Medical record created with ID " . $m->id;
