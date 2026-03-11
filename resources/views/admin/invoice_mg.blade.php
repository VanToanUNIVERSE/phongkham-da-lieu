@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý hóa đơn</h2>
        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tạo hóa đơn
        </button>
    </div>

    <!-- Bảng Dữ Liệu -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                            <select id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 transition-colors">
                                <option value="pending">Chưa thanh toán</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                            <p class="text-xs text-red-500 mt-1 hidden" id="error-status"></p>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div>
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

                <div class="mt-8 flex justify-end gap-3">
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

<script src="{{ asset('js/invoice_mg.js') }}"></script>
@endsection
