<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineTransaction;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prescriptions = Prescription::with('user')->get();
        $medicines = Medicine::all();
        $medical_records = MedicalRecord::all();
        $users = User::all();
        return view('admin.prescription_mg', compact('medicines', 'prescriptions', 'medical_records', 'users'));
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
            'medical_record_id' => 'required|exists:medical_records,id',
            'dispensed_by' => 'required|exists:users,id',
            'dispense_status' => 'required',
            'content' => 'required',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage' => 'nullable|string',
            'items.*.usage' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {

            $prescription = Prescription::create([
                'medical_record_id' => $data['medical_record_id'],
                'dispensed_by' => null, // Sẽ được cập nhật khi Dược sĩ phát thuốc
                'content' => $data['content'],
                'dispense_status' => 'pending',
            ]);
            foreach ($data['items'] as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'dosage' => $item['dosage'] ?? null,
                    'usage' => $item['usage'] ?? null,
                ]);
            }

            // 🔥 Tự động cập nhật trạng thái lịch hẹn sang 'complete' (Hệ thống hóa theo yêu cầu: Lễ tân không chỉnh, BS kê toa xong là Hoàn thành)
            $record = MedicalRecord::find($data['medical_record_id']);
            if ($record && $record->appointment_id) {
                \App\Models\Appointment::where('id', $record->appointment_id)->update(['status' => 'complete']);
            }

        });

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo đơn thuốc thành công'
        ]);

    } catch (ValidationException $e) {

        return response()->json([
            'status' => 'fail',
            'errors' => $e->errors(),
            'message' => 'Lỗi nhập liệu'
        ], 422);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $prescription = Prescription::with(['items.medicine'])
        ->findOrFail($id);

    return response()->json([
        'status' => 'success',
        'data' => $prescription
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
   public function update(Request $request, $id)
{
    try {

        $data = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'dispensed_by' => 'required|exists:users,id',
            'dispense_status' => 'required',
            'content' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage' => 'nullable|string',
            'items.*.usage' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $id) {

            $prescription = Prescription::with('items')->findOrFail($id);

            // Xóa items cũ
            $prescription->items()->delete();

            // Update header
            $prescription->update([
                'medical_record_id' => $data['medical_record_id'],
                'content' => $data['content'],
            ]);

            // Thêm items mới
            foreach ($data['items'] as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'dosage' => $item['dosage'] ?? null,
                    'usage' => $item['usage'] ?? null,
                ]);
            }

        });

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật đơn thuốc thành công'
        ]);

    } catch (ValidationException $e) {

        return response()->json([
            'status' => 'fail',
            'errors' => $e->errors(),
            'message' => 'Lỗi nhập liệu'
        ], 422);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => 'Có lỗi xảy ra'
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function loadData() {
        $prescriptions = Prescription::with('user')->get();
        $medicines = Medicine::all();
        $medical_records = MedicalRecord::all();
        $users = User::all();
        return response()->json([
            'prescriptions' => $prescriptions,
            'medicines' => $medicines,
            'medical_records' => $medical_records,
            'users' => $users
        ]);
    }
}
