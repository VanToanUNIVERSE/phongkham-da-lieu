@extends('layouts.app')

@section('pageTitle', 'Trang chủ')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-black text-gray-900">Trang chủ — Dược sĩ</h2>
    <p class="text-gray-500 text-sm mt-0.5">Xin chào, {{ auth()->user()->full_name }}. Hôm nay {{ date('d/m/Y') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    <a href="{{ route('pharmacy.dispense') }}" class="bg-white border border-violet-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Chờ phát</span>
        </div>
        <p class="text-4xl font-black text-violet-600">{{ $pendingCount }}</p>
        <p class="text-xs text-violet-400 font-semibold mt-1">đơn thuốc đang chờ →</p>
    </a>

    <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Đã phát hôm nay</span>
        </div>
        <p class="text-4xl font-black text-emerald-600">{{ $dispensedToday }}</p>
        <p class="text-xs text-emerald-400 font-semibold mt-1">đơn đã xong hôm nay</p>
    </div>

    <a href="{{ route('pharmacy.inventory') }}" class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 {{ $lowStockCount > 0 ? 'bg-red-100 text-red-500' : 'bg-orange-100 text-orange-500' }} rounded-xl flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Thuốc sắp hết</span>
        </div>
        <p class="text-4xl font-black {{ $lowStockCount > 0 ? 'text-red-500' : 'text-orange-500' }}">{{ $lowStockCount }}</p>
        <p class="text-xs text-orange-400 font-semibold mt-1">loại tồn kho ≤ 10 →</p>
    </a>

    <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tổng loại thuốc</span>
        </div>
        <p class="text-4xl font-black text-blue-600">{{ $totalMedicines }}</p>
        <p class="text-xs text-blue-400 font-semibold mt-1">loại thuốc đang hoạt động</p>
    </div>
</div>

@if($pendingCount > 0)
<div class="bg-violet-50 border border-violet-200 rounded-2xl p-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-violet-600 rounded-xl flex items-center justify-center text-white text-2xl">💊</div>
        <div>
            <p class="font-black text-violet-900 text-lg">Có {{ $pendingCount }} đơn thuốc đang chờ phát!</p>
            <p class="text-violet-600 text-sm">Bệnh nhân đã thanh toán và đang đợi nhận thuốc.</p>
        </div>
    </div>
    <a href="{{ route('pharmacy.dispense') }}" class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-black rounded-xl transition-all active:scale-95">
        Phát thuốc ngay →
    </a>
</div>
@endif
@endsection
