@extends('layouts.app')

@section('pageTitle', 'Quản lý thuốc')

@section('content')
<div class="animate-in fade-in duration-700">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Quản lý Thuốc</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Danh sách kho thuốc và vật tư y tế</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <input type="text" id="medicineSearch" onkeyup="searchMedicine()" placeholder="Tìm tên thuốc..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none w-64 shadow-sm">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreate()" class="bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a4.978 4.978 0 00-1.464-3.536 5.002 5.002 0 00-7.072 0L3.928 8.001a5.002 5.002 0 000 7.071 5.002 5.002 0 007.072 0l3.535-3.536A4.978 4.978 0 0016 8zM8.5 15.5l7-7" />
                </svg>
                Thêm Thuốc Mới
            </button>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <x-modal 
        id="modal" 
        titleId="title" 
        title="Thêm thuốc" 
        maxWidth="max-w-3xl"
        submitId="submitBtn"
        submitClick="save()"
        submitText="Lưu Thông tin"
        submitIcon='<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'>
        
        <input type="hidden" id="id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
            <!-- Tên thuốc -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tên thuốc <span class="text-rose-500">*</span></label>
                <input type="text" id="name" placeholder="VD: Paracetamol 500mg" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_name" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Đơn vị -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Đơn vị</label>
                <input type="text" id="unit" placeholder="VD: Viên, Vỉ, Hộp" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_unit" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Số lượng -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Số lượng tồn kho</label>
                <input type="number" id="stock" placeholder="VD: 100" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_stock" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Đơn giá -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Đơn giá (VNĐ)</label>
                <input type="number" id="price" placeholder="VD: 5000" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_price" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Hạn sử dụng -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Hạn sử dụng</label>
                <input type="date" id="expiry_date" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                <p id="err_expiry_date" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
            
            <!-- Trạng thái -->
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Trạng thái</label>
                <select id="is_active" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="1" selected>Đang hoạt động</option>
                    <option value="0">Ngưng / Thu hồi</option>
                </select>
                <p id="err_is_active" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>

            <!-- Mô tả -->
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Mô tả thêm / Công dụng</label>
                <textarea id="description" rows="3" placeholder="Ghi chú thêm về thuốc..." class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none"></textarea>
                <p id="err_description" class="text-rose-500 text-xs mt-2 font-semibold hidden"></p>
            </div>
        </div>

        <h3 id="message" class="text-emerald-600 font-bold text-center mt-4 text-sm tracking-wide"></h3>
    </x-modal>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="table">
                <!-- JS Load Data Here -->
            </table>
        </div>
    </div>

    <script src="{{ asset('js/medicine_mg.js') }}"></script>
@endsection


