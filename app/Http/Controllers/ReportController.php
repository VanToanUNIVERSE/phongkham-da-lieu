<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index() {
        // Lấy các chỉ số tổng quan
        $totalPatients = Patient::count();
        $totalAppointmentsToday = Appointment::whereDate('date', today())->count();
        $totalMedicinesInStock = Medicine::sum('stock');
        $totalUsers = User::count();
        $totalPrescriptions = Prescription::count();
        $totalRecords = MedicalRecord::count();

        // 1. Lấy Doanh thu Tháng này
        $revenueThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // 2. Lấy Doanh thu Năm nay
        $revenueThisYear = Invoice::where('status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // Lấy 5 cuộc hẹn mới nhất hôm nay hoặc sắp tới
        $recentAppointments = Appointment::with(['patient', 'doctor.user'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->whereDate('date', '>=', today())
            ->take(5)
            ->get();

        return view('admin.report', compact(
            'totalPatients', 
            'totalAppointmentsToday', 
            'totalMedicinesInStock', 
            'totalUsers',
            'totalPrescriptions',
            'totalRecords',
            'recentAppointments',
            'revenueThisMonth',
            'revenueThisYear'
        ));
    }
}
