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
                'dispensed_by' => $data['dispensed_by'],
                'content' => $data['content'],
                'dispense_status' => $data['dispense_status'],
            ]);

            foreach ($data['items'] as $item) {

                $medicine = Medicine::find($item['medicine_id']);

                // 🔥 kiểm tra tồn kho
                if ($medicine->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'stock' => 'Thuốc ' . $medicine->name . ' không đủ tồn kho'
                    ]);
                }

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'dosage' => $item['dosage'] ?? null,
                    'usage' => $item['usage'] ?? null,
                ]);

                // trừ kho
                $medicine->decrement('stock', $item['quantity']);

                // tạo transaction
                MedicineTransaction::create([
                    'medicine_id' => $medicine->id,
                    'type' => 'export',
                    'quantity' => $item['quantity'],
                    'note' => 'Phát thuốc theo đơn #' . $prescription->id,
                    'user_id' => $data['dispensed_by'],
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

            // 🔥 Hoàn lại stock từ item cũ
            foreach ($prescription->items as $oldItem) {

                $medicine = Medicine::find($oldItem->medicine_id);
                $medicine->increment('stock', $oldItem->quantity);
            }

            // Xóa items cũ
            $prescription->items()->delete();

            // Update header
            $prescription->update([
                'medical_record_id' => $data['medical_record_id'],
                'dispensed_by' => $data['dispensed_by'],
                'content' => $data['content'],
                'dispense_status' => $data['dispense_status'],
            ]);

            // Thêm items mới
            foreach ($data['items'] as $item) {

                $medicine = Medicine::find($item['medicine_id']);

                if ($medicine->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'stock' => 'Thuốc ' . $medicine->name . ' không đủ tồn kho'
                    ]);
                }

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'dosage' => $item['dosage'] ?? null,
                    'usage' => $item['usage'] ?? null,
                ]);

                $medicine->decrement('stock', $item['quantity']);

                MedicineTransaction::create([
                    'medicine_id' => $medicine->id,
                    'type' => 'export',
                    'quantity' => $item['quantity'],
                    'note' => 'Cập nhật đơn #' . $prescription->id,
                    'user_id' => $data['dispensed_by'],
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
