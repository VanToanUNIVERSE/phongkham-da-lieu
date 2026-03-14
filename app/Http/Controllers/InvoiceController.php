<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Patient;

class InvoiceController extends Controller
{
    public function index()
    {
        $medical_records = MedicalRecord::with(['patient', 'prescription'])->orderBy('id', 'desc')->get();
        return view('admin.invoice_mg', compact('medical_records'));
    }

    public function loadData(Request $request)
    {
        $invoices = Invoice::with(['patient', 'medical_record.prescription'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($invoice) {
                return [
                    'id'              => $invoice->id,
                    'patient_name'    => $invoice->patient->full_name,
                    'total_amount'    => $invoice->total_amount,
                    'examination_fee' => $invoice->examination_fee,
                    'medicine_fee'    => $invoice->medicine_fee,
                    'status'          => $invoice->status,
                    'payment_method'  => $invoice->payment_method ?? 'N/A',
                    'created_at'      => $invoice->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json($invoices);
    }

    public function calculateCost($medicalRecordId)
    {
        $medicalRecord = MedicalRecord::with('prescription.items.medicine')->findOrFail($medicalRecordId);
        $medicineFee = 0;

        if ($medicalRecord->prescription) {
            $medicineFee = $medicalRecord->prescription->total_cost ?? 0;
        }

        return response()->json(['medicine_fee' => $medicineFee]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'examination_fee'   => 'required|numeric|min:0',
            'status'            => 'required',
        ]);

        $medicalRecord = MedicalRecord::with('prescription.items.medicine')->findOrFail($request->medical_record_id);

        $medicineFee = 0;
        if ($medicalRecord->prescription) {
            $medicineFee = $medicalRecord->prescription->total_cost ?? 0;
        }

        $totalAmount = $medicineFee + $request->examination_fee;

        Invoice::create([
            'patient_id'       => $medicalRecord->patient_id,
            'medical_record_id'=> $medicalRecord->id,
            'examination_fee'  => $request->examination_fee,
            'medicine_fee'     => $medicineFee,
            'total_amount'     => $totalAmount,
            'status'           => $request->status,
            'payment_method'   => $request->payment_method,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Tạo hóa đơn thành công!']);
    }

    public function show($id)
    {
        $invoice = Invoice::with('medical_record')->findOrFail($id);
        return response()->json($invoice);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $isReceptionist = in_array(auth()->user()->role->name ?? '', ['Lễ tân']);

        if ($isReceptionist) {
            $request->validate(['status' => 'required']);

            $invoice->update([
                'status'         => $request->status,
                'payment_method' => $request->payment_method,
            ]);
        } else {
            $request->validate([
                'medical_record_id' => 'required|exists:medical_records,id',
                'examination_fee'   => 'required|numeric|min:0',
                'status'            => 'required',
            ]);

            $medicalRecord = MedicalRecord::with('prescription.items.medicine')->findOrFail($request->medical_record_id);

            $medicineFee = 0;
            if ($medicalRecord->prescription) {
                $medicineFee = $medicalRecord->prescription->total_cost ?? 0;
            }

            $totalAmount = $medicineFee + $request->examination_fee;

            $invoice->update([
                'patient_id'        => $medicalRecord->patient_id,
                'medical_record_id' => $medicalRecord->id,
                'examination_fee'   => $request->examination_fee,
                'medicine_fee'      => $medicineFee,
                'total_amount'      => $totalAmount,
                'status'            => $request->status,
                'payment_method'    => $request->payment_method,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Cập nhật hóa đơn thành công!']);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->json(['status' => 'success']);
    }
}
