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
        return back()->with('error', 'Chức năng tra cứu bằng số điện thoại đã bị gỡ bỏ. Vui lòng đăng nhập để xem lịch sử khám bệnh.')->withInput();
    }
}
