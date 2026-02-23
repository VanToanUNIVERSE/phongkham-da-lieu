<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Database\Seeders\PatientSeeder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor.user', 'patient'])->get();
        $doctors = Doctor::with('user')->get();
        $patients = Patient::all();
        return view('admin.appointment_mg', compact('appointments', 'doctors', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
                $appointment = $request->validate([
                'doctor_id' => 'required',
                'patient_id' => 'required',
                'date' => 'required',
                'time' => 'required',
                'status' => 'required'
            ]);

            Appointment::create($appointment);

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm lịch khám thành công',
                'appointment' => $appointment
            ]);
        }
        catch(ValidationException $e){

        return response()->json([
            'status'=>'fail',
            'errors' => $e->errors(),
            'message' => 'Lỗi nhập liệu'
            ], 422);
        }   
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
