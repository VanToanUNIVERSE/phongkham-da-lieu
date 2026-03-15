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
            'code'  => 'required',
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'code.required'  => 'Vui lòng nhập mã lịch hẹn.',
        ]);

        $phone = $request->phone;
        $code  = $request->code;

        $appointment = Appointment::with(['patient', 'medicalRecord.prescription.items.medicine'])
            ->where('id', $code)
            ->whereHas('patient', function ($q) use ($phone) {
                $q->where('phone', $phone);
            })
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Không tìm thấy thông tin lịch hẹn. Vui lòng kiểm tra lại Số điện thoại hoặc Mã lịch hẹn.')->withInput();
        }

        return view('public.lookup', compact('appointment'));
    }
}
