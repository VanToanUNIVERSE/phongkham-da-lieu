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
        $appointments = Appointment::with(['doctor.user', 'patient'])
            ->orderByRaw("
                CASE status
                    WHEN 'pending' THEN 1
                    WHEN 'confirmed' THEN 2
                    WHEN 'completed' THEN 3
                    WHEN 'cancelled' THEN 4
                    ELSE 5
                END ASC
            ")
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();
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
                'message' => 'Thêm lịch khám thành công'
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
    public function show(Appointment $appointment)
    {
        
        return response()->json([
            'appointment' => $appointment
        ]);
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
    public function update(Request $request, Appointment $appointment)
    {
        try {
                $data = $request->validate([
                'doctor_id' => 'required',
                'patient_id' => 'required',
                'date' => 'required',
                'time' => 'required',
                'status' => 'required'
            ]);

            $appointment->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Sửa khám thành công',
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
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    function loadData(Request $request) {
        $search = $request->query('search');
        $appointments = Appointment::with(['doctor.user', 'patient'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("
                CASE status
                    WHEN 'pending' THEN 1
                    WHEN 'confirmed' THEN 2
                    WHEN 'completed' THEN 3
                    WHEN 'cancelled' THEN 4
                    ELSE 5
                END ASC
            ")
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();
        return response()->json([
            'appointments' => $appointments
        ]);
    }
}
