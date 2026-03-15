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
            ->whereIn('status', ['pending', 'inprocess'])
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
}
