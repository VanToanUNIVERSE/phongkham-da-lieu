@extends('layouts.app')

@section('content')
<div class="animate-in fade-in duration-700">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Quản lý Hồ sơ khám</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Lưu trữ các hồ sơ chẩn đoán, điều trị bệnh nhân</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <input type="text" id="medicalRecordSearch" onkeyup="searchMedicalRecord()" placeholder="Tìm bệnh nhân, bác sĩ, chẩn đoán..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none w-64 shadow-sm">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreate()" class="bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                Tạo Hồ sơ khám
            </button>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <x-modal 
        id="modal" 
        titleId="title" 
        title="Thêm Hồ sơ" 
        maxWidth="max-w-2xl"
        submitId="submitBtn"
        submitClick="save()"
        submitText="Lưu Hồ sơ"
        submitIcon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'>
        
        <input type="hidden" id="id">

        <!-- Basic Error Msg -->
        <h3 id="message" class="text-emerald-600 font-bold text-center mb-4 text-sm tracking-wide"></h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
            
            <!-- Lịch khám -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Dựa trên Lịch khám <span class="text-rose-500">*</span></label>
                <select id="appointment_id" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn mã lịch khám --</option>
                    @foreach ($appointments as $a)
                        <option value="{{ $a->id }}">Mã hẹn #{{ $a->id }} ({{ $a->date }} - {{ $a->time }})</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Bác sĩ -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bác sĩ chủ trị <span class="text-rose-500">*</span></label>
                <select id="doctor_id" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn bác sĩ --</option>
                    @foreach ($doctors as $d)
                        <option value="{{ $d->id }}">BS. {{ $d->user->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bệnh nhân -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bệnh nhân <span class="text-rose-500">*</span></label>
                <select id="patient_id" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="" selected disabled>-- Chọn bệnh nhân --</option>
                    @foreach ($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Chẩn đoán -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nội dung chẩn đoán</label>
                <input type="text" id="diagnosis" placeholder="Mô tả các triệu chứng lâm sàng..." class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_diagnosis" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Kết quả khám -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kết luận kiểm tra</label>
                <input type="text" id="examination_result" placeholder="Kết quả từ các đánh giá..." class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_examination_result" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
        </div>

    </x-modal>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="table">
                <!-- JS Load Data Here -->
            </table>
        </div>
    </div>

    <script src="{{ asset('js/medical_record_mg.js') }}"></script>
    
@endsection


