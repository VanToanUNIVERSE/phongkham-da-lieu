<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'status' => 'required|in:unconfirmed,pending,inprocess,complete,cancel',
        ]);
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

            // Tự động tạo tài khoản nếu có Số điện thoại
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

            return response()->json(['status' => 'success', 'message' => 'Đăng ký bệnh nhân thành công. Tài khoản đã được tự động khởi tạo (Mật khẩu là SĐT).', 'patient' => $patient]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Lỗi nhập liệu'], 422);
        }
    }

    /**
     * API: Get invoice for appointment (via medical record).
     */
    public function getAppointmentInvoice(Appointment $appointment)
    {
        $record = MedicalRecord::with(['prescription', 'doctor.user', 'patient'])
            ->where('appointment_id', $appointment->id)
            ->first();

        $invoice     = null;
        $medicineFee = 0;

        if ($record) {
            $invoice = Invoice::where('medical_record_id', $record->id)->first();
            if ($record->prescription) {
                $medicineFee = $record->prescription->total_cost ?? 0;
            }
        }

        return response()->json([
            'record'       => $record,
            'invoice'      => $invoice,
            'medicine_fee' => $medicineFee,
        ]);
    }

    /**
     * Quản lý hóa đơn - Trang danh sách.
     */
    public function invoices()
    {
        // 1. Lấy Doanh thu Tháng này
        $revenueThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // 2. Lấy Doanh thu Năm nay
        $revenueThisYear = Invoice::where('status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        return view('reception.invoices', compact('revenueThisMonth', 'revenueThisYear'));
    }

    /**
     * API: Load danh sách hóa đơn (lịch khám đã hoàn thành).
     */
    public function loadInvoices(Request $request)
    {
        $date = $request->date ?? today()->toDateString();

        $query = Appointment::with([
                'patient',
                'doctor.user',
                'medicalRecord.invoice',
                'medicalRecord.prescription',
            ])
            ->where('status', 'complete')
            ->whereDate('date', $date);

        // Lọc theo từ khóa (Tên bệnh nhân)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($pq) use ($search) {
                $pq->where('full_name', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái hóa đơn
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'none') {
                $query->whereDoesntHave('medicalRecord.invoice');
            } elseif ($request->status === 'pending') {
                $query->whereHas('medicalRecord.invoice', function($iq) {
                    $iq->where('status', 'pending');
                });
            } elseif ($request->status === 'paid') {
                $query->whereHas('medicalRecord.invoice', function($iq) {
                    $iq->where('status', 'paid');
                });
            }
        }

        $appointments = $query->orderBy('time', 'desc')
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Public: Khách hàng đặt lịch không cần đăng nhập.
     */
    public function publicBooking(Request $request)
    {
        try {
            $request->validate([
                'full_name'  => 'required|string|max:100',
                'phone'      => 'required|string|max:20',
                'doctor_id'  => 'nullable|exists:doctors,id',
                'date'       => 'required|date|after_or_equal:today',
                'time'       => 'required',
                'birth_year' => 'nullable|integer|min:1900|max:2099',
                'gender'     => 'nullable|in:0,1',
                'note'       => 'nullable|string|max:500',
            ]);

            // Tìm hoặc tạo bệnh nhân theo SĐT
            $patient = Patient::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'full_name'  => $request->full_name,
                    'birth_year' => $request->birth_year,
                    'gender'     => $request->gender ?? null,
                    'address'    => $request->address ?? null,
                ]
            );

            // Nếu bệnh nhân đã tồn tại, cập nhật tên (phòng trường hợp tên thay đổi)
            if (!$patient->wasRecentlyCreated && $patient->full_name !== $request->full_name) {
                $patient->update(['full_name' => $request->full_name]);
            }

            // Nếu không chọn bác sĩ cụ thể, lấy bác sĩ đầu tiên
            $doctorId = $request->doctor_id ?? Doctor::first()?->id;

            if (!$doctorId) {
                return response()->json(['status' => 'fail', 'message' => 'Hiện chưa có bác sĩ nào trong hệ thống.'], 422);
            }

            $apt = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id'  => $doctorId,
                'date'       => $request->date,
                'time'       => $request->time,
                'status'     => 'unconfirmed',
            ]);

            // Tự động tạo tài khoản nếu chưa có
            if (!$patient->user_id) {
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
                'status' => 'success', 
                'message' => 'Đặt lịch thành công! Bạn có thể đăng nhập bằng Số điện thoại của mình (mật khẩu mặc định là SĐT). Mã lịch hẹn: ' . $apt->id
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'fail', 'errors' => $e->errors(), 'message' => 'Vui lòng kiểm tra lại thông tin.'], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => 'Có lỗi xảy ra, vui lòng thử lại.'], 500);
        }
    }

    /**
     * API: Lấy các khung giờ đã được đặt của một bác sĩ trong một ngày.
     */
    public function getBookedSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'nullable',
            'date'      => 'required|date',
        ]);
        
        if (!$request->doctor_id) {
            return response()->json(['booked_times' => []]);
        }

        $bookedTimes = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('date', $request->date)
            ->whereIn('status', ['pending', 'inprocess', 'complete'])
            ->pluck('time')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            });

        return response()->json(['booked_times' => $bookedTimes]);
    }

    /**
     * Cập nhật thông tin bệnh nhân.
     */
    public function updatePatient(Request $request, Patient $patient)
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'birth_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'gender'      => 'nullable|in:0,1',
            'address'     => 'nullable|string|max:255',
        ]);

        $patient->update([
            'full_name'  => $request->full_name,
            'phone'       => $request->phone,
            'birth_year'  => $request->birth_year,
            'gender'      => $request->gender,
            'address'     => $request->address,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Cập nhật thông tin bệnh nhân thành công']);
    }

    /**
     * Trang quản lý bệnh nhân cho Lễ tân.
     */
    public function patients()
    {
        return view('reception.patients');
    }
}
