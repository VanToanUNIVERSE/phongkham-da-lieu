@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Quản lý Đơn thuốc</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Danh sách các đơn thuốc đã kê cho bệnh nhân</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <input type="text" id="prescriptionSearch" onkeyup="searchPrescription()" placeholder="Tìm bệnh nhân, bác sĩ..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none w-64 shadow-sm">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-2xl shadow-lg transition-all active:scale-95 flex items-center gap-2 uppercase text-xs tracking-widest whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Thêm đơn thuốc
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="table">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm md:text-base text-gray-600 uppercase tracking-wider">
                        <th class="py-4 px-6 font-medium">Mã đơn</th>
                        <th class="py-4 px-6 font-medium">Mã Hồ sơ khám</th>
                        <th class="py-4 px-6 font-medium">Nhân viên phát</th>
                        <th class="py-4 px-6 font-medium">Nội dung</th>
                        <th class="py-4 px-6 font-medium">Trạng thái</th>
                        <th class="py-4 px-6 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($prescriptions as $p)
                        <tr id="row-{{ $p->id }}" class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-6 font-medium text-gray-900">#{{ $p->id }}</td>
                            <td class="py-3 px-6"><span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-sm font-medium">BA-{{ $p->medical_record_id }}</span></td>
                            <td class="py-3 px-6">
                                @if($p->user)
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                            {{ mb_substr($p->user->full_name, 0, 1, 'UTF-8') }}
                                        </div>
                                        <span>{{ $p->user->full_name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Chưa phân công</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 truncate max-w-xs" title="{{ $p->content }}">{{ $p->content }}</td>
                            <td class="py-3 px-6">
                                @if($p->dispense_status == 'Đã phát')
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Đã phát
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                        Chưa phát
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="edit({{ $p->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button onclick="del({{ $p->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Chưa có dữ liệu đơn thuốc nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto w-full" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        
        <!-- Modal Dialog -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div id="modal-panel" class="relative bg-white rounded-xl shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:max-w-3xl transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 id="title" class="text-xl font-bold text-gray-800">Thêm đơn thuốc</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6">
                    <input type="hidden" id="id">

                    <!-- HEADER INFO (Grid 2 Cột) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mã hồ sơ khám <span class="text-red-500">*</span></label>
                            <select id="medical_record_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="">Chọn mã hồ sơ khám</option>
                                @foreach ($medical_records as $a)
                                    <option value="{{ $a->id }}">BA-{{ $a->id }} (BN: {{ $a->patient->full_name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nhân viên phát <span class="text-red-500">*</span></label>
                            <select id="user_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="">Chọn nhân viên</option>
                                @foreach ($users as $s)
                                    <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nội dung chẩn đoán / Ghi chú</label>
                            <input type="text" id="content" placeholder="Nhập nội dung chẩn đoán chi tiết..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái phát thuốc</label>
                            <select id="dispense_status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white transition-colors">
                                <option value="Chưa phát">Chưa phát</option>
                                <option value="Đã phát">Đã phát</option>
                            </select>
                        </div>
                    </div>

                    <!-- DETAIL THUỐC -->
                    <div class="flex items-center justify-between mb-3 mt-8">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Chi tiết Đơn thuốc
                        </h4>
                        <button onclick="addMedicineRow({{ $medicines }})" class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 font-medium px-4 py-2 rounded-lg transition-all shadow-sm flex items-center gap-1 active:scale-95">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Thêm thuốc
                        </button>
                    </div>

                    <div class="border border-gray-200 rounded-xl overflow-hidden mb-4 shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="py-3 px-4 font-semibold text-gray-700">Tên Thuốc</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700 w-24">SL</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700">Liều dùng</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700">Cách dùng</th>
                                    <th class="py-3 px-4 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="medicine-items" class="divide-y divide-gray-100 bg-white">
                                <!-- JS Add items here -->
                            </tbody>
                        </table>
                    </div>

                    <h3 id="message" class="text-green-600 font-medium text-center mt-4"></h3>
                    <div id="errors" class="text-red-500 text-sm mb-4 space-y-1 p-3 bg-red-50 rounded-lg hidden"></div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 font-bold transition-all shadow-sm active:scale-95">
                        Hủy bỏ
                    </button>
                    <button id="submitBtn" onclick="save()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Lưu Đơn thuốc
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
    window.medicines = @json($medicines);
</script>
    <script src="{{ asset('js/prescription_mg.js') }}"></script>
@endsection

