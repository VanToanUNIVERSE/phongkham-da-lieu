<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patient_mg', compact('patients'));
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
                'full_name' => 'required',
                'phone' => 'nullable',
                'gender' => 'required',
                'birth_year' => 'required',
                'address' => 'nullable'
            ]);
            $patient = Patient::create($data);

            // Tự động tạo tài khoản nếu có số điện thoại
            if ($patient->phone) {
                $existingUser = User::where('username', $patient->phone)->first();
                if (!$existingUser) {
                    $rolePatient = Role::where('name', 'Bệnh nhân')->first();
                    if ($rolePatient) {
                        $newUser = User::create([
                            'username' => $patient->phone,
                            'full_name' => $patient->full_name,
                            'password' => Hash::make($patient->phone),
                            'role_id' => $rolePatient->id,
                        ]);
                        $patient->update(['user_id' => $newUser->id]);
                    }
                } else {
                    $patient->update(['user_id' => $existingUser->id]);
                }
            }

            return response()->json([
                'message' => 'Thêm bệnh nhân thành công. Tài khoản đã được tự động tạo (Mật khẩu là SĐT).',
                'status' => 'success'
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
    public function show(Patient $patient)
    {
        return response()->json([
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {

        try {
                $data = $request->validate([
                'full_name' => 'required',
                'phone' => 'nullable',
                'gender' => 'required',
                'birth_year' => 'required',
                'address' => 'nullable'
            ]);
            $patient->update($data);

            return response()->json([
                'message' => 'Cập nhật dữ liệu thành công',
                'status' => 'success'
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
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json([
        'status'=>'success',
        'message'=>'Xoá thành công'
    ]);
    }

    public function loadData(Request $request) {
        $search = $request->query('search');
        $patients = Patient::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->get();
        return response()->json([
            'patients' => $patients
        ]);
    }
}
