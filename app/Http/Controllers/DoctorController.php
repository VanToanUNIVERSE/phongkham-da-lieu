<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    /**
     * Lấy Doctor record của user đang đăng nhập.
     * Nếu chưa có thì tự động tạo (để tránh lỗi 404).
     */
    private function getDoctor()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialty' => 'Chưa cập nhật',
                'is_free' => 1
            ]);
        }

        return $doctor;
    }

    /**
     * Dashboard tổng quan bác sĩ.
     */
    public function index()
    {
        $doctor = $this->getDoctor();
        $today  = Carbon::today();

        // 1. Số ca khám hôm nay
        $todayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->count();

        // 2. Tổng bệnh nhân đã khám (distinct) — dựa trên MedicalRecord
        $totalPatients = MedicalRecord::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        // 3. Hồ sơ tạo tháng này
        $recordsThisMonth = MedicalRecord::where('doctor_id', $doctor->id)
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        // 4. Lịch khám hôm nay (sắp đến)
        $upcomingAppointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->get();

        return view('doctor.dashboard', compact(
            'doctor',
            'todayCount',
            'totalPatients',
            'recordsThisMonth',
            'upcomingAppointments'
        ));
    }

    /**
     * Trang lịch khám của bác sĩ.
     */
    public function appointments()
    {
        $doctor    = $this->getDoctor();
        $medicines = Medicine::where('is_active', 1)->orderBy('name')->get();
        return view('doctor.appointments', compact('doctor', 'medicines'));
    }

    /**
     * API: Load danh sách lịch hẹn của bác sĩ đang đăng nhập.
     */
    public function loadAppointments(Request $request)
    {
        $doctor = $this->getDoctor();
        $search = $request->query('search');
        $date = $request->query('date');

        $appointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctor->id)
            ->when($date, function($query) use ($date) {
                $query->whereDate('date', $date);
            })
            ->when($search, function($query) use ($search) {
                $query->whereHas('patient', function($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%");
                });
            })
            ->orderByRaw("FIELD(status, 'pending', 'inprocess', 'complete')")
            ->orderBy('date', 'desc')
            ->orderBy('time', 'asc')
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * API: Cập nhật trạng thái lịch hẹn.
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $doctor = $this->getDoctor();

        // Đảm bảo bác sĩ chỉ sửa lịch của mình
        if ($appointment->doctor_id !== $doctor->id) {
            return response()->json(['status' => 'fail', 'message' => 'Không có quyền'], 403);
        }

        $request->validate(['status' => 'required|in:pending,inprocess,complete']);
        $appointment->update(['status' => $request->status]);

        return response()->json(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công']);
    }

    /**
     * Trang hồ sơ khám bệnh.
     */
    public function medicalRecords()
    {
        $doctor   = $this->getDoctor();
        $patients = Patient::all();
        // Lịch hẹn của bác sĩ này (để chọn khi tạo hồ sơ)
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('doctor.medical_records', compact('doctor', 'patients', 'appointments'));
    }

    /**
     * API: Load danh sách hồ sơ của bác sĩ đang đăng nhập.
     */
    public function loadMedicalRecords(Request $request)
    {
        $doctor = $this->getDoctor();
        $search = $request->query('search');

        $records = MedicalRecord::with(['patient', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->when($search, function($query) use ($search) {
                $query->whereHas('patient', function($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%");
                })->orWhere('diagnosis', 'like', "%$search%");
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['medical_records' => $records]);
    }

    /**
     * API: Tạo hồ sơ khám mới.
     */
    public function storeMedicalRecord(Request $request)
    {
        try {
            $doctor = $this->getDoctor();

            $data = $request->validate([
                'appointment_id'     => 'required|exists:appointments,id',
                'patient_id'         => 'required|exists:patients,id',
                'diagnosis'          => 'required|string',
                'examination_result' => 'required|string',
            ]);

            $data['doctor_id'] = $doctor->id;

            $record = MedicalRecord::create($data);

            // Cập nhật trạng thái lịch hẹn -> inprocess
            Appointment::where('id', $data['appointment_id'])
                ->update(['status' => 'inprocess']);

            return response()->json(['status' => 'success', 'message' => 'Tạo hồ sơ thành công', 'record' => $record]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Lỗi nhập liệu'], 422);
        }
    }

    /**
     * API: Cập nhật hồ sơ khám.
     */
    public function updateMedicalRecord(Request $request, MedicalRecord $medical_record)
    {
        try {
            $doctor = $this->getDoctor();

            if ($medical_record->doctor_id !== $doctor->id) {
                return response()->json(['status' => 'fail', 'message' => 'Không có quyền'], 403);
            }

            $data = $request->validate([
                'appointment_id'     => 'required|exists:appointments,id',
                'patient_id'         => 'required|exists:patients,id',
                'diagnosis'          => 'required|string',
                'examination_result' => 'required|string',
            ]);

            $medical_record->update($data);

            return response()->json(['status' => 'success', 'message' => 'Cập nhật hồ sơ thành công']);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Lỗi nhập liệu'], 422);
        }
    }

    /**
     * Trang quản lý đơn thuốc của bác sĩ.
     */
    public function prescriptions()
    {
        $doctor  = $this->getDoctor();
        // Hồ sơ của bác sĩ này (để chọn khi tạo đơn)
        $myRecords = MedicalRecord::with(['patient', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('created_at')
            ->get();
        $medicines = Medicine::where('is_active', 1)->orderBy('name')->get();
        return view('doctor.prescriptions', compact('doctor', 'myRecords', 'medicines'));
    }

    /**
     * API: Load đơn thuốc của bác sĩ (qua hồ sơ của bác sĩ này).
     */
    public function loadPrescriptions(Request $request)
    {
        $doctor = $this->getDoctor();
        $search = $request->query('search');

        // Lấy tất cả medical_record_id của bác sĩ này
        $recordIds = MedicalRecord::where('doctor_id', $doctor->id)->pluck('id');

        $prescriptions = Prescription::with(['medical_record.patient', 'items.medicine'])
            ->whereIn('medical_record_id', $recordIds)
            ->when($search, function($query) use ($search) {
                $query->whereHas('medical_record.patient', function($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['prescriptions' => $prescriptions]);
    }
    /**
     * API: Load lịch sử khám của bệnh nhân.
     */
    public function getPatientHistory($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['status' => 'fail', 'message' => 'Bệnh nhân không tồn tại'], 404);
        }

        $history = MedicalRecord::with(['doctor.user', 'prescription.items.medicine'])
            ->where('patient_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['history' => $history]);
    }
}
