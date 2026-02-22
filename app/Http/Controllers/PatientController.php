<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::all();
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
            Patient::create($data);

            return response()->json([
                'message' => 'Thêm dữ liệu thành công',
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

    public function loadData() {
        $patients = Patient::all();
        return response()->json([
            'patients' => $patients
        ]);
    }
}
