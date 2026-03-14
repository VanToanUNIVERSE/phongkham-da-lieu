<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineTransaction;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    /**
     * Dashboard – Trang chủ dược sĩ.
     */
    public function index()
    {
        $pendingCount  = Prescription::whereIn('dispense_status', [null, 'pending'])
            ->whereHas('medical_record.invoice', fn($q) => $q->where('status', 'paid'))
            ->count();

        $dispensedToday = Prescription::where('dispense_status', 'dispensed')
            ->whereDate('updated_at', today())
            ->count();
        $lowStockCount = Medicine::where('stock', '<=', 10)->where('is_active', true)->count();
        $totalMedicines = Medicine::where('is_active', true)->count();

        return view('pharmacy.dashboard', compact(
            'pendingCount', 'dispensedToday', 'lowStockCount', 'totalMedicines'
        ));
    }

    /**
     * Trang phát thuốc.
     */
    public function dispense()
    {
        return view('pharmacy.dispense');
    }

    /**
     * API: Tải danh sách đơn thuốc chờ phát (chỉ lấy khi đã thanh toán).
     */
    public function loadPrescriptions(Request $request)
    {
        $status = $request->status ?? 'pending'; // pending | dispensed | all
        $date   = $request->date ?? today()->toDateString();

        $query = Prescription::with([
            'medical_record.patient',
            'medical_record.appointment.doctor.user',
            'medical_record.invoice',
            'items.medicine',
            'user',
        ])->whereDate('created_at', $date);

        if ($status === 'pending') {
            $query->whereIn('dispense_status', [null, 'pending']);
        } elseif ($status === 'dispensed') {
            $query->where('dispense_status', 'dispensed');
        }
        // 'all' = no extra filter

        // Only show prescriptions from paid appointments
        $query->whereHas('medical_record.invoice', function ($q) {
            $q->where('status', 'paid');
        });

        $prescriptions = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['prescriptions' => $prescriptions]);
    }

    /**
     * API: Xác nhận phát thuốc – trừ kho và ghi transaction.
     */
    public function confirmDispense(Prescription $prescription)
    {
        if ($prescription->dispense_status === 'dispensed') {
            return response()->json(['status' => 'fail', 'message' => 'Đơn này đã được phát rồi.'], 422);
        }

        DB::transaction(function () use ($prescription) {
            foreach ($prescription->items as $item) {
                $medicine = $item->medicine;

                // Deduct stock
                $medicine->decrement('stock', $item->quantity);

                // Record export transaction
                MedicineTransaction::create([
                    'medicine_id' => $medicine->id,
                    'type'        => 'export',
                    'quantity'    => $item->quantity,
                    'note'        => "Phát theo đơn #{$prescription->id}",
                    'user_id'     => auth()->id(),
                ]);
            }

            $prescription->update([
                'dispense_status' => 'dispensed',
                'dispensed_by'    => auth()->id(),
            ]);
        });

        return response()->json(['status' => 'success', 'message' => 'Phát thuốc thành công!']);
    }

    /**
     * Trang quản lý kho thuốc.
     */
    public function inventory()
    {
        return view('pharmacy.inventory');
    }

    /**
     * API: Tải danh sách thuốc trong kho.
     */
    public function loadInventory(Request $request)
    {
        $search = $request->search ?? '';

        $medicines = Medicine::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json(['medicines' => $medicines]);
    }

    /**
     * API: Nhập kho thuốc (import transaction).
     */
    public function importStock(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity'    => 'required|integer|min:1',
            'note'        => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $medicine->increment('stock', $request->quantity);

        MedicineTransaction::create([
            'medicine_id' => $medicine->id,
            'type'        => 'import',
            'quantity'    => $request->quantity,
            'note'        => $request->note ?? 'Nhập kho',
            'user_id'     => auth()->id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Đã nhập {$request->quantity} {$medicine->unit} {$medicine->name}.",
            'new_stock' => $medicine->stock,
        ]);
    }

    /**
     * API: Lịch sử giao dịch kho.
     */
    public function transactions(Request $request)
    {
        $transactions = MedicineTransaction::with(['medicine', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        return response()->json(['transactions' => $transactions]);
    }
}
