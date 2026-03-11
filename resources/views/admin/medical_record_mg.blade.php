@extends('layouts.app')

@section('content')

    {{-- MODAL --}}
    <div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto w-full" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        
        <!-- Modal Dialog -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div id="modal-panel" class="relative bg-white rounded-xl shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:max-w-xl transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 id="title" class="text-xl font-bold text-gray-800">Thêm Hồ sơ khám</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <input type="hidden" id="id">

                    <!-- Basic Error Msg -->
                    <h3 id="message" class="text-green-600 font-medium text-center mb-2"></h3>
                    

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        
                        <!-- Lịch khám -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dựa trên Lịch khám <span class="text-red-500">*</span></label>
                            <select id="appointment_id" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                                <option value="" selected disabled>-- Chọn mã lịch khám --</option>
                                @foreach ($appointments as $a)
                                    <option value="{{ $a->id }}">Mã hẹn #{{ $a->id }} ({{ $a->date }} - {{ $a->time }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Bác sĩ -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Bác sĩ chủ trị <span class="text-red-500">*</span></label>
                            <select id="doctor_id" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                                <option value="" selected disabled>-- Chọn bác sĩ --</option>
                                @foreach ($doctors as $d)
                                    <option value="{{ $d->id }}">BS. {{ $d->user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bệnh nhân -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Bệnh nhân <span class="text-red-500">*</span></label>
                            <select id="patient_id" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                                <option value="" selected disabled>-- Chọn bệnh nhân --</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Chẩn đoán -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nội dung chẩn đoán</label>
                            <input type="text" id="diagnosis" placeholder="Mô tả các triệu chứng lâm sàng..." class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_diagnosis" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        
                        <!-- Kết quả khám -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kết luận kiểm tra</label>
                            <input type="text" id="examination_result" placeholder="Kết quả từ các đánh giá..." class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <p id="err_examination_result" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 font-bold transition-all shadow-sm active:scale-95">
                        Hủy bỏ
                    </button>
                    <button id="submitBtn" onclick="save()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Lưu Hồ sơ
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý Hồ sơ khám</h1>
            <p class="text-sm text-gray-500 mt-1">Lưu trữ các hồ sơ chẩn đoán, điều trị bệnh nhân</p>
        </div>
        <button onclick="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
            Tạo Hồ sơ khám
        </button>
    </div>

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


