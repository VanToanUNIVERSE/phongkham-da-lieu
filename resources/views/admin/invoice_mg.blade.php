@extends('layouts.app')

@section('content')
<div class="animate-in fade-in duration-700">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight tracking-tighter uppercase">Quản lý hóa đơn</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Theo dõi tài chính và quản lý thu chi phòng khám</p>
        </div>
        @php
            $isReceptionist = in_array(auth()->user()->role->name, ['Lễ tân', 'Lễ tân']);
        @endphp
        
        @if(!$isReceptionist)
            <button onclick="openModal()" class="bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tạo hóa đơn mới
            </button>
        @endif
    </div>

    {{-- Stats Grid for Admin --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
        {{-- Revenue Month --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-500">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity duration-500">
                <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
            </div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner border border-emerald-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Thu nhập tháng này</p>
                    <h3 class="text-3xl font-black text-slate-900 leading-tight">
                        {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}<span class="text-xs ml-1 text-slate-400 uppercase tracking-tighter">VNĐ</span>
                    </h3>
                </div>
            </div>
        </div>

        {{-- Revenue Year --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-500">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity duration-500">
                <svg class="w-24 h-24 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>
            </div>
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner border border-amber-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Doanh thu năm nay</p>
                    <h3 class="text-3xl font-black text-slate-900 leading-tight">
                        {{ number_format($revenueThisYear ?? 0, 0, ',', '.') }}<span class="text-xs ml-1 text-slate-400 uppercase tracking-tighter">VNĐ</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-8 animate-in fade-in slide-in-from-bottom-6 duration-700 delay-200">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6">
            {{-- Search --}}
            <div class="md:col-span-2 xl:col-span-2 relative">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4 mb-2 block font-sans">Tìm kiếm hóa đơn</label>
                <div class="relative group">
                    <input type="text" id="filterSearch" placeholder="Tên bệnh nhân hoặc mã hóa đơn..." 
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            {{-- Status --}}
            <div class="md:col-span-1 xl:col-span-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4 mb-2 block font-sans">Trạng thái</label>
                <select id="filterStatus" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="all">Tất cả</option>
                    <option value="pending">Chờ thanh toán</option>
                    <option value="paid">Đã thanh toán</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>

            {{-- Payment Method --}}
            <div class="md:col-span-1 xl:col-span-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4 mb-2 block font-sans">Phương thức</label>
                <select id="filterPaymentMethod" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
                    <option value="all">Tất cả</option>
                    <option value="cash">Tiền mặt</option>
                    <option value="transfer">Chuyển khoản</option>
                    <option value="card">Thẻ / Ví</option>
                </select>
            </div>

            {{-- Date Range --}}
            <div class="md:col-span-2 xl:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4 mb-2 block font-sans">Khoảng thời gian</label>
                <div class="flex items-center gap-2">
                    <input type="date" id="filterDateFrom" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                    <span class="text-slate-300 font-bold">→</span>
                    <input type="date" id="filterDateTo" class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none">
                </div>
            </div>
        </div>
    </div>

    <!-- Script truyền quyền từ Blade sang JS -->
    <script>
        window.isReceptionistUser = @json($isReceptionist);
    </script>

    <!-- Bảng Dữ Liệu -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="table">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Mã HĐ</th>
                        <th class="px-6 py-4">Bệnh nhân</th>
                        <th class="px-6 py-4">Tổng tiền (VNĐ)</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4">Phương thức</th>
                        <th class="px-6 py-4">Ngày tạo</th>
                        <th class="px-6 py-4 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700" id="tableBody">
                    <!-- Dữ liệu JS render -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm/Sửa -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 overflow-y-auto flex items-center justify-center transition-opacity duration-300 opacity-0">
        <div id="modalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Thêm hóa đơn mới</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="dataForm" class="p-6">
                <input type="hidden" id="entityId">
                <input type="hidden" id="patient_id">
                
                <div id="errors" class="mb-4 text-red-500 text-sm hidden bg-red-50 p-3 rounded-lg border border-red-100"></div>

                <div class="space-y-4">
                    <!-- Hồ sơ khám -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hồ sơ khám <span class="text-red-500">*</span></label>
                        <select id="medical_record_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 transition-colors">
                            <option value="">-- Chọn hồ sơ khám --</option>
                            @foreach($medical_records as $record)
                                <option value="{{ $record->id }}">Hồ sơ khám #{{ $record->id }} - {{ $record->patient->full_name }} ({{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-red-500 mt-1 hidden" id="error-medical_record_id"></p>
                    </div>

                    <!-- Phí khám bệnh -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phí khám bệnh (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" id="examination_fee" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 transition-colors">
                        <p class="text-xs text-red-500 mt-1 hidden" id="error-examination_fee"></p>
                    </div>

                    <!-- Tiền thuốc -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tiền thuốc (VNĐ) <span class="text-gray-400 font-normal text-xs">(Tính tự động từ đơn thuốc)</span></label>
                        <input type="number" id="medicine_fee" value="0" readonly class="w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm px-3 py-2 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-red-500 mt-1 hidden" id="error-medicine_fee"></p>
                    </div>

                    <!-- Tổng tiền -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tổng tiền (VNĐ)</label>
                        <input type="number" id="total_amount" value="0" readonly class="w-full bg-blue-50 bg-opacity-50 border-blue-200 font-bold text-blue-700 rounded-lg shadow-sm px-3 py-2 cursor-not-allowed">
                        <p class="text-xs text-red-500 mt-1 hidden" id="error-total_amount"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Trạng thái -->
                        <div id="statusWrapper">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                            <select id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 transition-colors">
                                <option value="pending">Chưa thanh toán</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                            <p class="text-xs text-red-500 mt-1 hidden" id="error-status"></p>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div id="paymentMethodWrapper">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phương thức thanh toán</label>
                            <select id="payment_method" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 transition-colors">
                                <option value="">-- Chọn phương thức --</option>
                                <option value="cash">Tiền mặt</option>
                                <option value="transfer">Chuyển khoản</option>
                                <option value="card">Thẻ tín dụng</option>
                            </select>
                            <p class="text-xs text-red-500 mt-1 hidden" id="error-payment_method"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3" id="modalFooter">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors shadow-sm">
                        Hủy bỏ
                    </button>
                    <button type="button" id="submitBtn" onclick="save()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Lưu Hóa Đơn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/invoice_mg.js') }}?v={{ time() }}"></script>
@endsection
