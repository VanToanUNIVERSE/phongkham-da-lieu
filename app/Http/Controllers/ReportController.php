<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\MedicalRecord;

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
            'recentAppointments'
        ));
    }
}
