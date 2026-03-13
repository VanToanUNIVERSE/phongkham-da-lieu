<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Số ca khám hôm nay
        $todayAppointmentsCount = Appointment::whereDate('date', $today)->count();

        // 2. Số bệnh nhân mới trong tháng
        $newPatientsThisMonth = Patient::whereMonth('created_at', $today->month)
                                     ->whereYear('created_at', $today->year)
                                     ->count();

        // 3. Số hóa đơn xuất hôm nay
        $invoicesTodayCount = Invoice::whereDate('created_at', $today)->count();

        // 4. Lịch khám sắp diễn ra hôm nay
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
}
