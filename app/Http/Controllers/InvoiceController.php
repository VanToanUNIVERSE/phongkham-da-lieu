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

        // 1. Doanh thu tháng này vs tháng trước
        $revenueThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->sum('total_amount');

        $revenueLastMonth = Invoice::where('status', 'paid')
            ->whereMonth('created_at', \Carbon\Carbon::now()->subMonth()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->subMonth()->year)
            ->sum('total_amount');

        // 2. Doanh thu năm nay vs năm ngoái
        $revenueThisYear = Invoice::where('status', 'paid')
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->sum('total_amount');

        $revenueLastYear = Invoice::where('status', 'paid')
            ->whereYear('created_at', \Carbon\Carbon::now()->subYear()->year)
            ->sum('total_amount');

        // 3. Doanh thu hôm nay vs hôm qua
        $revenueToday = Invoice::where('status', 'paid')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->sum('total_amount');

        $revenueYesterday = Invoice::where('status', 'paid')
            ->whereDate('created_at', \Carbon\Carbon::yesterday())
            ->sum('total_amount');

        // Chart: Doanh thu 14 ngày gần nhất
        $dailyRevenue = collect(range(13, 0))->map(function ($daysAgo) {
            $date = \Carbon\Carbon::today()->subDays($daysAgo);
            return [
                'date'    => $date->format('d/m'),
                'revenue' => Invoice::where('status', 'paid')->whereDate('created_at', $date)->sum('total_amount'),
            ];
        });

        // Chart: Doanh thu 12 tháng gần nhất
        $monthlyRevenue = collect(range(11, 0))->map(function ($monthsAgo) {
            $date = \Carbon\Carbon::now()->subMonths($monthsAgo);
            return [
                'month'   => 'Thg ' . $date->format('n/Y'),
                'revenue' => Invoice::where('status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_amount'),
            ];
        });

        return view('admin.invoice_mg', compact(
            'medical_records',
            'revenueThisMonth', 'revenueLastMonth',
            'revenueThisYear', 'revenueLastYear',
            'revenueToday', 'revenueYesterday',
            'dailyRevenue', 'monthlyRevenue'
        ));
    }

    public function loadData(Request $request)
    {
        $query = Invoice::with(['patient', 'medical_record.prescription']);

        // Lọc theo từ khóa (Tên bệnh nhân hoặc Mã hóa đơn)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($pq) use ($search) {
                      $pq->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('id', 'desc')
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

    public function exportTxt($id)
    {
        $invoice = Invoice::with(['patient', 'medical_record.appointment.doctor.user', 'medical_record.prescription.items.medicine'])->findOrFail($id);
        
        $patient = $invoice->patient;
        $record = $invoice->medical_record;
        $apt = $record->appointment;
        $doctorName = $apt->doctor->user->full_name ?? 'N/A';
        
        $content = "HÓA ĐƠN THANH TOÁN\n";
        $content .= "Phòng Khám Da Liễu Cao Cấp\n";
        $content .= "------------------------------------------\n";
        $content .= "Mã hóa đơn: #{$invoice->id}\n";
        $content .= "Ngày lập: " . $invoice->created_at->format('d/m/Y H:i') . "\n";
        $content .= "------------------------------------------\n";
        $content .= "THÔNG TIN BỆNH NHÂN\n";
        $content .= "Họ tên: {$patient->full_name}\n";
        $content .= "SĐT: {$patient->phone}\n";
        $content .= "Năm sinh: {$patient->birth_year}\n";
        $content .= "Giới tính: " . ($patient->gender == 1 ? 'Nam' : 'Nữ') . "\n";
        $content .= "Địa chỉ: {$patient->address}\n";
        $content .= "------------------------------------------\n";
        $content .= "CHI TIẾT DỊCH VỤ\n";
        $content .= "Bác sĩ khám: BS. {$doctorName}\n";
        $content .= "------------------------------------------\n";
        $content .= "KẾT QUẢ KHÁM BỆNH\n";
        $content .= "Chẩn đoán: " . ($record->diagnosis ?? 'Đang cập nhật...') . "\n";
        if ($record->examination_results) {
            $content .= "Kết quả lâm sàng:\n" . wordwrap($record->examination_results, 40, "\n") . "\n";
        }
        $content .= "------------------------------------------\n";
        $content .= "CHI PHÍ THANH TOÁN\n";
        $content .= "Phí khám lâm sàng: " . number_format($invoice->examination_fee, 0, ',', '.') . " VNĐ\n";
        
        if ($invoice->medicine_fee > 0) {
            $content .= "Tiền thuốc: " . number_format($invoice->medicine_fee, 0, ',', '.') . " VNĐ\n";
            $content .= "Chi tiết đơn thuốc:\n";
            if ($record->prescription && $record->prescription->items) {
                foreach ($record->prescription->items as $item) {
                    $content .= "  - {$item->medicine->name}: {$item->quantity} ({$item->dosage})\n";
                }
            }
        }
        
        $content .= "------------------------------------------\n";
        $content .= "TỔNG THANH TOÁN: " . number_format($invoice->total_amount, 0, ',', '.') . " VNĐ\n";
        $content .= "Phương thức: " . ($invoice->payment_method == 'cash' ? 'Tiền mặt' : ($invoice->payment_method == 'bank_transfer' ? 'Chuyển khoản' : 'Thẻ/Ví')) . "\n";
        $content .= "Trạng thái: " . ($invoice->status == 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán') . "\n";
        $content .= "------------------------------------------\n";
        $content .= "Cảm ơn quý khách đã tin tưởng!\n";
        $content .= "Hẹn gặp lại quý khách.\n";

        $fileName = "HoaDon_" . $invoice->id . "_" . date('Ymd_His') . ".txt";
        
        return response($content)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
    }

    public function print($id)
    {
        $invoice = Invoice::with(['patient', 'medical_record.appointment.doctor.user', 'medical_record.prescription.items.medicine'])->findOrFail($id);
        return view('reception.print', compact('invoice'));
    }
}
