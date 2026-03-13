<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReceptionController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayAppointmentsCount = Appointment::whereDate('date', $today)->count();

        $newPatientsThisMonth = Patient::whereMonth('created_at', $today->month)
                                     ->whereYear('created_at', $today->year)
                                     ->count();

        $invoicesTodayCount = Invoice::whereDate('created_at', $today)->count();

        $upcomingAppointments = Appointment::with(['patient', 'doctor.user'])
                                           ->whereDate('date', $today)
                                           ->orderBy('time', 'asc')
                                           ->take(5)
                                           ->get();

        return view('reception.dashboard', compact(
            'todayAppointmentsCount',
            'newPatientsThisMonth',
            'invoicesTodayCount',
            'upcomingAppointments'
        ));
    }

    /**
     * Trang lịch khám all-in-one cho Lễ tân.
     */
    public function appointments()
    {
        $doctors  = Doctor::with('user')->get();
        $patients = Patient::orderBy('full_name')->get();
        return view('reception.appointments', compact('doctors', 'patients'));
    }

    /**
     * API: Load tất cả lịch hẹn (theo ngày).
     */
    public function loadAppointments(Request $request)
    {
        $date = $request->date ?? today()->toDateString();
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('date', $date)
            ->orderBy('time', 'asc')
            ->get();
        return response()->json(['appointments' => $appointments]);
    }

    /**
     * API: Tạo lịch hẹn mới.
     */
    public function storeAppointment(Request $request)
    {
        try {
            $data = $request->validate([
                'doctor_id'  => 'required|exists:doctors,id',
                'patient_id' => 'required|exists:patients,id',
                'date'       => 'required|date',
                'time'       => 'required',
            ]);
            $data['status'] = 'pending';
            $apt = Appointment::create($data);
            return response()->json(['status' => 'success', 'message' => 'Tạo lịch hẹn thành công', 'appointment' => $apt]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Lỗi nhập liệu'], 422);
        }
    }

    /**
     * API: Cập nhật trạng thái lịch hẹn.
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,inprocess,complete']);
        $appointment->update(['status' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công']);
    }

    /**
     * API: Tạo bệnh nhân mới nhanh.
     */
    public function storePatient(Request $request)
    {
        try {
            $data = $request->validate([
                'full_name'  => 'required|string',
                'phone'      => 'nullable|string',
                'birth_year' => 'nullable|integer|min:1900|max:2026',
                'gender'     => 'nullable|boolean',
                'address'    => 'nullable|string',
            ]);
            $patient = Patient::create($data);
            return response()->json(['status' => 'success', 'message' => 'Đăng ký bệnh nhân thành công', 'patient' => $patient]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Lỗi nhập liệu'], 422);
        }
    }

    /**
     * API: Get invoice for appointment (via medical record).
     */
    public function getAppointmentInvoice(Appointment $appointment)
    {
        $record  = MedicalRecord::where('appointment_id', $appointment->id)->first();
        $invoice = null;
        $medicineFee = 0;

        if ($record) {
            $invoice = Invoice::where('medical_record_id', $record->id)->first();
            if ($record->prescriptions) {
                $medicineFee = $record->prescriptions->total_cost ?? 0;
            }
        }

        return response()->json([
            'record'      => $record,
            'invoice'     => $invoice,
            'medicine_fee' => $medicineFee,
        ]);
    }
}
