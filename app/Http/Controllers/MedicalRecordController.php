<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medical_records = MedicalRecord::with(['doctor.user', 'patient'])->get();
        $doctors = Doctor::with('user')->get();
        $patients = Patient::all();
        $appointments = Appointment::all();
        return view('admin.medical_record_mg', compact('medical_records', 'doctors', 'patients', 'appointments'));
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
                    $data = $request->validate([
                    'appointment_id' => 'required',
                    'doctor_id' => 'required',
                    'patient_id' => 'required',
                    'diagnosis' => 'required',
                    'examination_result' => 'required',
                ]);

                $medical_record = MedicalRecord::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Thêm lịch khám thành công',
                    'medical_record' => $medical_record
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
    public function show(MedicalRecord $medical_record)
    {
        return response()->json([
            'medical_record' => $medical_record
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
    public function update(Request $request, MedicalRecord $medical_record)
    {
        try {
                $data = $request->validate([
                'appointment_id' => 'required',
                    'doctor_id' => 'required',
                    'patient_id' => 'required',
                    'diagnosis' => 'required',
                    'examination_result' => 'required',
            ]);

            $medical_record->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Sửa khám thành công',
                'medical_record' => $medical_record
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
    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function loadData() {
        $medical_records = MedicalRecord::with(['doctor.user', 'patient'])->get();
        $doctors = Doctor::with('user')->get();
        $patients = Patient::all();
        $appointments = Appointment::all();
        return response()->json([
            'status' => 'success',
            'medical_records' => $medical_records,
            'doctors' => $doctors,
            'patients' => $patients,
            'appointments' => $appointments
        ]);
    }
}
