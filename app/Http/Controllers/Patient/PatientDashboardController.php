<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Invoice;

class PatientDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return view('patient.no_profile');
        }

        // Lấy lịch hẹn sắp tới
        $upcomingAppointments = Appointment::with('doctor.user')
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['pending', 'inprocess', 'unconfirmed'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        // Lấy hồ sơ bệnh án gần đây
        $recentRecords = MedicalRecord::with(['doctor.user', 'prescription.items.medicine'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Lấy hóa đơn gần đây
        $recentInvoices = Invoice::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Lấy danh sách bác sĩ để đặt lịch
        $doctors = \App\Models\Doctor::with('user')->get();

        return view('patient.dashboard', compact('patient', 'upcomingAppointments', 'recentRecords', 'recentInvoices', 'doctors'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $patient = $user->patient;
        return view('patient.profile', compact('user', 'patient'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gender' => 'nullable|in:0,1',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'full_name' => $request->full_name,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        if ($patient) {
            $patient->update([
                'full_name' => $request->full_name,
                'birth_year' => $request->birth_year,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);
        }

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công');
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if ($appointment->patient_id !== $patient->id) {
            return back()->with('error', 'Bạn không có quyền hủy lịch hẹn này.');
        }

        if (!in_array($appointment->status, ['pending', 'unconfirmed'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn đã được xử lý hoặc hoàn thành.');
        }

        $appointment->update(['status' => 'cancel']);

        return back()->with('success', 'Đã hủy lịch hẹn thành công.');
    }
}
