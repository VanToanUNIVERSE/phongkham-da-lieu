@extends('layouts.app')

@section('content')
<div class="animate-in fade-in duration-700">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Quản lý Bệnh nhân</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Danh sách và thông tin bệnh nhân đăng ký khám</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <input type="text" id="patientSearch" onkeyup="searchPatient()" placeholder="Tìm tên, số điện thoại..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none w-64 shadow-sm">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
                Thêm Bệnh Nhân
            </button>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <x-modal 
        id="modal" 
        titleId="title" 
        title="Thêm bệnh nhân" 
        maxWidth="max-w-3xl"
        submitId="submitBtn"
        submitClick="save()"
        submitText="Lưu Bệnh nhân"
        submitIcon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'>
        
        <input type="hidden" id="id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
            <!-- Họ tên -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                <input type="text" id="full_name" placeholder="VD: Nguyễn Văn A" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_full_name" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Số điện thoại -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Số điện thoại</label>
                <input type="text" id="phone" placeholder="VD: 0987654321" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_phone" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Năm sinh -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Năm sinh</label>
                <input type="number" id="birth_year" placeholder="VD: 1990" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_birth_year" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Giới tính -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Giới tính</label>
                <select id="gender" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="1" selected>Nam</option>
                    <option value="0">Nữ</option>
                </select>
                <p id="err_gender" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Địa chỉ -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Địa chỉ</label>
                <input type="text" id="address" placeholder="Nhập địa chỉ đầy đủ..." class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_address" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
        </div>

        <h3 id="message" class="text-emerald-600 font-bold text-center mt-4 text-sm tracking-wide"></h3>
    </x-modal>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="patientTable">
                <!-- JS Load Data Here -->
            </table>
        </div>
    </div>

    <script src="{{ asset('js/patient_mg.js') }}"></script>
    
</div>
@endsection
