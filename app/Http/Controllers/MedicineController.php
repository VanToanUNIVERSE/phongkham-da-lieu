<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::all();
        return view('admin.medicine_mg', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
                    $data = $request->validate([
                    'name' => 'required',
                    'unit' => 'required',
                    'stock' => 'required|min:1',
                    'price' => 'required|min:1',
                    'expiry_date'=> 'required',
                    'description' => 'required',
                    'is_active' => 'required'
                ]);

                $medicine = Medicine::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Thêm thuốc thành công',
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
    public function show(Medicine $medicine)
    {
        return response()->json([
            'medicine' => $medicine
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
    public function update(Request $request, Medicine $medicine)
    {
        try {
                    $data = $request->validate([
                    'name' => 'required',
                    'unit' => 'required',
                    'stock' => 'required|min:1',
                    'price' => 'required|min:1',
                    'expiry_date'=> 'required',
                    'description' => 'required',
                    'is_active' => 'required'
                ]);

                $medicine->update($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cập nhật thuốc thành công',
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
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa thành công thuốc '.$medicine->name
        ]);
    }

    public function loadData(Request $request) {
        $search = $request->query('search');
        $medicines = Medicine::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->get();
        return response()->json([
            'medicines' => $medicines
        ]);
    }
}
