@extends('layouts.app')

@section('pageTitle', 'Thống kê doanh thu')

@section('title', 'BÁO CÁO TOÀN DIỆN')

@section('content')
    <div class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Thống Kê Tổng Quan</h1>
        <p class="text-slate-500 font-medium">Theo dõi hoạt động và doanh thu phòng khám DaVi</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
        {{-- Total Patients --}}
        <div class="group bg-white rounded-[2rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-500">
                <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.25-2.906z" /></svg>
            </div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner border border-blue-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tổng lượng bệnh nhân</p>
                <h3 class="text-3xl font-black text-slate-900 leading-tight">
                    {{ number_format($totalPatients ?? 0) }}<span class="text-xs ml-2 text-slate-400 uppercase tracking-tighter">BỆNH NHÂN</span>
                </h3>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full uppercase">Dữ liệu hệ thống</span>
            </div>
        </div>

        {{-- Appointments --}}
        <div class="group bg-white rounded-[2rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-500">
                <svg class="w-24 h-24 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
            </div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner border border-rose-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Lịch khám hôm nay</p>
                <h3 class="text-3xl font-black text-slate-900 leading-tight">
                    {{ $totalAppointmentsToday ?? 0 }}<span class="text-xs ml-2 text-slate-400 uppercase tracking-tighter">CA ĐÃ ĐẶT</span>
                </h3>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-bold rounded-full uppercase">Đang tiếp nhận</span>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 animate-in fade-in slide-in-from-bottom-8 duration-700 delay-200">
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex items-center gap-6 group hover:translate-y-[-4px] transition-all duration-300">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-sm border border-indigo-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Kho dược phẩm</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalMedicinesInStock ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex items-center gap-6 group hover:translate-y-[-4px] transition-all duration-300">
            <div class="w-14 h-14 bg-violet-50 text-violet-500 rounded-2xl flex items-center justify-center group-hover:bg-violet-500 group-hover:text-white transition-all duration-500 shadow-sm border border-violet-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Hồ sơ khám bệnh</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalRecords ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex items-center gap-6 group hover:translate-y-[-4px] transition-all duration-300">
            <div class="w-14 h-14 bg-slate-50 text-slate-500 rounded-2xl flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-all duration-500 shadow-sm border border-slate-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Nhân sự hệ thống</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($totalUsers ?? 0) }}</p>
            </div>
        </div>
    </div>

    {{-- Bottom Layout --}}
    <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000 delay-300">
        {{-- Recent Appointments Table --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-50">
                <div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Lịch Khám Sắp Tới</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Hôm nay & Ngày mai</p>
                </div>
                <a href="{{ route('appointments.index') }}" class="px-5 py-2.5 bg-slate-50 hover:bg-slate-950 hover:text-white text-slate-600 font-bold text-[10px] rounded-xl transition-all uppercase tracking-widest">Xem tất cả</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="pb-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] pl-4">Bệnh nhân</th>
                            <th class="pb-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Thời gian</th>
                            <th class="pb-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Bác sĩ phụ trách</th>
                            <th class="pb-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right pr-4">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentAppointments as $apt)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 pl-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black group-hover:scale-110 transition-transform border border-blue-100 shadow-sm">
                                            {{ substr($apt->patient->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $apt->patient->full_name }}</p>
                                            <p class="text-[10px] font-medium text-slate-400">#PAT-{{ $apt->patient_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs font-bold text-slate-900 italic">{{ \Carbon\Carbon::parse($apt->date)->format('d/m/Y') }}</p>
                                    <p class="text-xs font-bold text-blue-600">{{ $apt->time }}</p>
                                </td>
                                <td class="py-5 text-xs font-bold text-slate-600 lowercase tracking-tight italic">
                                    {{ $apt->doctor->user->full_name ?? 'N/A' }}
                                </td>
                                <td class="py-5 text-right pr-4">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            'completed' => 'bg-emerald-50 text-emerald-600',
                                            'cancelled' => 'bg-rose-50 text-rose-600'
                                        ][$apt->status] ?? 'bg-slate-100 text-slate-600';
                                        
                                        $statusText = [
                                            'pending' => 'CHỜ KHÁM',
                                            'completed' => 'XONG',
                                            'cancelled' => 'HỦY'
                                        ][$apt->status] ?? strtoupper($apt->status);
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black {{ $statusClass }} uppercase tracking-widest shadow-sm">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 opacity-30">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold tracking-widest text-[10px] uppercase">Không có lịch khám nào sắp tới</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection