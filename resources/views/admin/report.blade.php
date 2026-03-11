@extends('layouts.app')
@section('content')

    <!-- Nút in báo cáo và Tiêu đề -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thống Kê Báo Cáo</h1>
            <p class="text-sm text-gray-500 mt-1">Tổng quan tình hình hoạt động của Phòng khám Da liễu DaVi</p>
        </div>
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            In Báo Cáo
        </button>
    </div>

    <!-- 8 CARDS TỔNG QUAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Doanh Thu Tháng Này -->
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl border border-teal-400 p-6 shadow-md hover:shadow-lg transition-all relative overflow-hidden group text-white">
            <svg class="absolute right-0 top-0 h-full w-32 opacity-10 transform translate-x-10 group-hover:translate-x-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="flex flex-col relative z-10">
                <p class="text-teal-100 font-medium text-sm mb-1">DOANH THU THÁNG NÀY</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}<span class="text-lg font-normal ml-1">VNĐ</span></h3>
            </div>
        </div>

        <!-- Doanh Thu Năm Nay -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl border border-amber-400 p-6 shadow-md hover:shadow-lg transition-all relative overflow-hidden group text-white">
            <svg class="absolute right-0 top-0 h-full w-32 opacity-10 transform translate-x-10 group-hover:translate-x-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            <div class="flex flex-col relative z-10">
                <p class="text-amber-100 font-medium text-sm mb-1">DOANH THU NĂM NAY</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ number_format($revenueThisYear ?? 0, 0, ',', '.') }}<span class="text-lg font-normal ml-1">VNĐ</span></h3>
            </div>
        </div>
        
        <!-- Tổng Bệnh Nhân -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Tổng Bệnh nhân</p>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ number_format($totalPatients) }}</h3>
                </div>
            </div>
        </div>

        <!-- Thuốc Trong Kho -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Lượng Thuốc Trong Kho</p>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ number_format($totalMedicinesInStock) }} <span class="text-sm font-normal text-gray-400">ĐV</span></h3>
                </div>
            </div>
        </div>

        <!-- Lịch Khám Hôm Nay -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                     <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Lịch Khám Hôm nay</p>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ number_format($totalAppointmentsToday) }}</h3>
                </div>
            </div>
        </div>

        <!-- Hồ Sơ Khám -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl shadow border border-indigo-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-700 uppercase tracking-wider mb-2">Hồ Sơ Khám</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-4xl font-extrabold text-indigo-900 border-b-2 border-indigo-300 pb-1">{{ number_format($medicalRecordsCount ?? 0) }}</h3>
                </div>
            </div>
        </div>

        <!-- Đơn Thuốc Đã Kê -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Đơn Thuốc Đã Kê</p>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ number_format($totalPrescriptions) }}</h3>
                </div>
            </div>
        </div>

        <!-- Nhân sự / Bác Sĩ -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-gray-700 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-700">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Nhân sự Hệ thống</p>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tight">{{ number_format($totalUsers) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN DASHBOARD CONTENT AREA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- BẢNG LỊCH KHÁM MỚI NHẤT TRONG NGÀY -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Lịch Khám Sắp Tới
                </h2>
                <a href="{{ route('appointments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Xem tất cả &rarr;</a>
            </div>
            
            <div class="p-0 overflow-x-auto flex-1">
                @if($recentAppointments->count() > 0)
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white text-gray-500">
                            <tr>
                                <th class="px-6 py-3 font-semibold pb-2">Bệnh nhân</th>
                                <th class="px-6 py-3 font-semibold pb-2">Thời gian</th>
                                <th class="px-6 py-3 font-semibold pb-2">Bác sĩ phụ trách</th>
                                <th class="px-6 py-3 font-semibold pb-2">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentAppointments as $apt)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-blue-600">{{ $apt->patient->first_name . ' ' . $apt->patient->last_name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</span>
                                        <span class="text-gray-400 text-xs ml-1">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $apt->doctor->user->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @if($apt->status == 'scheduled')
                                            <span class="inline-flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-blue-100">Sắp đến</span>
                                        @elseif($apt->status == 'completed')
                                            <span class="inline-flex items-center bg-green-50 text-green-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-100">Đã khám</span>
                                        @elseif($apt->status == 'cancelled')
                                            <span class="inline-flex items-center bg-red-50 text-red-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-red-100">Đã hủy</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center flex flex-col justify-center h-full">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-gray-500 font-medium text-lg">Hôm nay chưa có lịch hẹn nào</p>
                        <p class="text-gray-400 text-sm mt-1">Lịch khám của bạn đang hoàn toàn trống!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- MỘT CÁI BẢNG GHI CHÚ NHANH (Hoặc Thêm Shortcut) -->
        <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl shadow-sm border border-indigo-400 p-6 text-white relative overflow-hidden flex flex-col">
            <!-- Pattern SVG Background -->
            <svg class="absolute right-0 top-0 h-full w-full opacity-10" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
            </svg>
            
            <h2 class="text-xl font-bold mb-2 relative z-10">Bạn cần làm gì tiếp?</h2>
            <p class="text-indigo-100 text-sm mb-6 relative z-10">Tạo nhanh các tác vụ y tế hằng ngày mà không cần chuyển trang</p>
            
            <div class="space-y-3 relative z-10 mt-auto">
                <a href="{{ route('patients.index') }}" class="block w-full text-center bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-lg transition-colors backdrop-blur-sm border border-white/10">
                    + Thêm Bệnh nhân mới
                </a>
                <a href="{{ route('appointments.index') }}" class="block w-full text-center bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-lg transition-colors backdrop-blur-sm border border-white/10">
                    + Đặt Lịch khám ngay
                </a>
                <a href="{{ route('prescriptions.index') }}" class="block w-full text-center bg-white text-indigo-600 hover:bg-indigo-50 font-bold py-3 px-4 rounded-lg transition-colors shadow-lg mt-4">
                    Kê Đơn Thuốc
                </a>
            </div>
        </div>

    </div>

@endsection