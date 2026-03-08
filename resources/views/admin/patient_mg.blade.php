@extends('layouts.app')

@section('content')

    {{-- MODAL --}}
    <div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto w-full" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        
        <!-- Modal Dialog -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div id="modal-panel" class="relative bg-white rounded-xl shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:max-w-2xl transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 id="title" class="text-xl font-bold text-gray-800">Thêm bệnh nhân</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <input type="hidden" id="id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Họ tên -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" id="full_name" placeholder="VD: Nguyễn Văn A" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_full_name" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        
                        <!-- Số điện thoại -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" id="phone" placeholder="VD: 0987654321" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_phone" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Năm sinh -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Năm sinh</label>
                            <input type="number" id="birth_year" placeholder="VD: 1990" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_birth_year" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Giới tính -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Giới tính</label>
                            <select id="gender" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                                <option value="1" selected>Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                            <p id="err_gender" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ</label>
                            <input type="text" id="address" placeholder="Nhập địa chỉ đầy đủ..." class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_address" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                    <h3 id="message" class="text-green-600 font-medium text-center mt-2"></h3>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 font-bold transition-all shadow-sm active:scale-95">
                        Hủy bỏ
                    </button>
                    <button id="submitBtn" onclick="save()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Lưu Bệnh nhân
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý Bệnh nhân</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách bệnh nhân đến khám tại phòng khám</p>
        </div>
        <button onclick="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
            Thêm bệnh nhân
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="patientTable">
                <!-- JS Load Data Here -->
            </table>
        </div>
    </div>

    <script src="{{ asset('js/patient_mg.js') }}"></script>
    
@endsection

