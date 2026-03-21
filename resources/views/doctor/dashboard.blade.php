@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Trang chủ Bác sĩ</h2>
        <p class="text-gray-500 text-sm mt-1">Xin chào, BS. {{ auth()->user()->full_name }} — Chuyên khoa: {{ $doctor->specialty ?? 'Đa khoa' }}</p>
    </div>
    <div class="mt-4 md:mt-0">
        <span class="text-sm font-medium bg-blue-100 text-blue-800 py-1.5 px-3 rounded-full">
            {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </span>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-6 mb-8">
    {{-- Card 1 --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1 duration-300">
        <div class="p-4 bg-blue-50 text-blue-600 rounded-lg mr-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Ca khám hôm nay</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $todayCount }}</h3>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1 duration-300">
        <div class="p-4 bg-green-50 text-green-600 rounded-lg mr-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Tổng bệnh nhân đã khám</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalPatients }}</h3>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition-transform hover:-translate-y-1 duration-300">
        <div class="p-4 bg-purple-50 text-purple-600 rounded-lg mr-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Hồ sơ tháng này</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $recordsThisMonth }}</h3>
        </div>
    </div>

</div>

{{-- Lịch hôm nay --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Lịch khám hôm nay của tôi</h3>
        <a href="{{ route('doctor.appointments') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Xem tất cả &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-sm">
                <tr>
                    <th class="py-3 px-6 font-semibold">Bệnh nhân</th>
                    <th class="py-3 px-6 font-semibold">Giờ khám</th>
                    <th class="py-3 px-6 font-semibold">Trạng thái</th>
                    <th class="py-3 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                @forelse($upcomingAppointments as $apt)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 font-medium text-gray-900">{{ $apt->patient->full_name ?? 'N/A' }}</td>
                        <td class="py-3 px-6">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ \Carbon\Carbon::parse($apt->time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            @if($apt->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 py-1 px-2.5 rounded-full text-xs font-semibold">Đang chờ khám</span>
                            @elseif($apt->status == 'inprocess')
                                <span class="bg-blue-100 text-blue-800 py-1 px-2.5 rounded-full text-xs font-semibold">Đang khám</span>
                            @elseif($apt->status == 'complete')
                                <span class="bg-green-100 text-green-800 py-1 px-2.5 rounded-full text-xs font-semibold">Đã hoàn thành</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 py-1 px-2.5 rounded-full text-xs font-semibold">{{ $apt->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right">
                            <a href="{{ route('doctor.medical_records') }}" class="text-xs bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 rounded-lg transition-colors font-medium">
                                Tạo hồ sơ
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Không có lịch khám nào hôm nay.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
