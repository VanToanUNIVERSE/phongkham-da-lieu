<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function showLookupForm()
    {
        return view('public.lookup');
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại để tra cứu.',
        ]);

        $phone = $request->phone;

        // Tìm bệnh nhân theo số điện thoại
        $patient = \App\Models\Patient::where('phone', $phone)->first();

        if (!$patient) {
            return back()->with('error', 'Không tìm thấy hồ sơ bệnh nhân nào với số điện thoại này.')->withInput();
        }

        // Lấy toàn bộ lịch sử khám bệnh (Medical Records) của bệnh nhân này
        // Bao gồm: bác sĩ, cuộc hẹn, đơn thuốc, chi tiết đơn thuốc, hóa đơn
        $records = clone $patient->medicalRecords()
            ->with([
                'doctor.user',
                'appointment',
                'prescription.items.medicine',
                'invoice'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('public.lookup', compact('patient', 'records'));
    }
}
