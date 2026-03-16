@extends('layouts.app')

@section('content')
<div class="animate-in fade-in duration-700">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Quản lý Lịch khám</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Sắp xếp và theo dõi lịch hẹn khách hàng</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="appointmentSearch" onkeyup="searchAppointment()" placeholder="Tìm tên bệnh nhân..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none w-64">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreate()" class="bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Thêm Lịch Khám
            </button>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <x-modal 
        id="modal" 
        titleId="title" 
        title="Thêm lịch khám" 
        maxWidth="max-w-2xl"
        submitId="submitBtn"
        submitClick="save()"
        submitText="Lưu Lịch hẹn"
        submitIcon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'>
        
        <input type="hidden" id="id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
            
            <!-- Bác sĩ -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bác sĩ phụ trách <span class="text-rose-500">*</span></label>
                <select id="doctor_id" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn bác sĩ --</option>
                    @foreach ($doctors as $d)
                        <option value="{{ $d->id }}">Bác sĩ: {{ $d->user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Bệnh nhân -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bệnh nhân <span class="text-rose-500">*</span></label>
                <select id="patient_id" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn bệnh nhân --</option>
                    @foreach ($patients as $p)
                        <option value="{{ $p->id }}">BN: {{ $p->full_name }} (SDT: {{ $p->phone ?? 'Trống' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Ngày khám -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ngày khám hẹn</label>
                <input type="date" id="date" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_date" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Giờ khám -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Giờ khám dự kiến</label>
                <input type="time" id="time" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_time" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Trạng thái -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Trạng thái</label>
                <select id="status" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn trạng thái --</option>
                    <option value="pending" >Đang chờ khám</option>
                    <option value="inprocess" >Đang khám (Trong phòng)</option>
                    <option value="complete" >Đã hoàn thành</option>
                </select>
            </div>
        </div>

        <h3 id="message" class="text-emerald-600 font-bold text-center mt-4 text-sm tracking-wide"></h3>
    </x-modal>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="appointmentTable">
                <!-- JS Load Data Here -->
            </table>
        </div>
    </div>

    <script src="{{ asset('js/appointment_mg.js') }}"></script>
@endsection


