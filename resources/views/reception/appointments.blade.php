@extends('layouts.app')

@section('content')

{{-- ===== SLIDE PANEL (Tiếp nhận all-in-one) ===== --}}
<div id="examPanel" class="fixed inset-0 z-50 hidden">
    <div onclick="closePanel()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

    <div id="examSlide" class="absolute right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex-shrink-0">
            <div>
                <h2 class="text-lg font-bold" id="panelPatientName">—</h2>
                <p class="text-blue-100 text-sm" id="panelInfo">—</p>
            </div>
            <button onclick="closePanel()" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Trạng thái (chỉ xem, do bác sĩ cập nhật) --}}
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex-shrink-0 flex items-center gap-3">
            <span class="text-sm font-medium text-gray-600">Trạng thái:</span>
            <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold border">—</span>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto">

            {{-- TAB: THÔNG TIN BỆNH NHÂN --}}
            <div id="tab-content-info" class="p-6">
                <div id="patientInfoView" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 text-sm relative group/info">
                    <button onclick="toggleEditPatient('edit')" class="absolute top-3 right-3 p-1.5 rounded-lg bg-blue-100 text-blue-600 opacity-0 group-hover/info:opacity-100 transition-opacity hover:bg-blue-200" title="Chỉnh sửa thông tin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <p><span class="font-semibold text-gray-600 w-24 inline-block">Họ tên:</span> <span id="ptName" class="text-gray-800">—</span></p>
                    <p><span class="font-semibold text-gray-600 w-24 inline-block">Năm sinh:</span> <span id="ptBirthYear" class="text-gray-800">—</span></p>
                    <p><span class="font-semibold text-gray-600 w-24 inline-block">Giới tính:</span> <span id="ptGender" class="text-gray-800">—</span></p>
                    <p><span class="font-semibold text-gray-600 w-24 inline-block">SĐT:</span> <span id="ptPhone" class="text-gray-800">—</span></p>
                    <p><span class="font-semibold text-gray-600 w-24 inline-block">Địa chỉ:</span> <span id="ptAddress" class="text-gray-800">—</span></p>
                </div>

                {{-- Edit Patient Info Box (Hidden by default) --}}
                <div id="patientInfoEdit" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4 space-y-3">
                    <p class="font-bold text-gray-700 text-sm mb-1">Chỉnh sửa bệnh nhân</p>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Họ tên</label>
                        <input type="text" id="editPtName" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Năm sinh</label>
                            <input type="number" id="editPtBirthYear" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Giới tính</label>
                            <select id="editPtGender" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white focus:border-blue-500">
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">SĐT</label>
                        <input type="text" id="editPtPhone" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Địa chỉ</label>
                        <input type="text" id="editPtAddress" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button onclick="savePatientInfo()" class="flex-1 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-700">Lưu</button>
                        <button onclick="toggleEditPatient('view')" class="flex-1 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-50">Hủy</button>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm space-y-1.5">
                    <p class="font-semibold text-gray-700 mb-2">Thông tin lịch hẹn</p>
                    <p><span class="text-gray-600 w-24 inline-block">Bác sĩ:</span> <span id="aptDoctor" class="font-medium text-gray-800">—</span></p>
                    <p><span class="text-gray-600 w-24 inline-block">Ngày:</span> <span id="panelAptDate" class="font-medium text-gray-800">—</span></p>
                    <p><span class="text-gray-600 w-24 inline-block">Giờ:</span> <span id="panelAptTime" class="font-medium text-gray-800">—</span></p>
                </div>

                {{-- Link sang trang Hóa đơn (chỉ hiện khi đã hoàn thành) --}}
                <div id="invoiceLinkBox" class="hidden mt-4">
                    <a href="{{ route('reception.invoices') }}"
                       class="flex items-center justify-between w-full px-4 py-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 group-hover:bg-emerald-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-800">Quản lý hóa đơn</p>
                                <p class="text-xs text-emerald-600">Ca khám đã hoàn thành — bấm để thu phí</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-emerald-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Action: Xác nhận lịch (chỉ hiện khi status = unconfirmed) --}}
                <div id="confirmActionBox" class="hidden mt-4">
                    <button onclick="confirmAppointment()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-colors font-bold shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Xác nhận lịch hẹn
                    </button>
                </div>

                {{-- Action: Hủy lịch (hiện khi status != complete && status != cancel) --}}
                <div id="cancelActionBox" class="hidden mt-3">
                    <button onclick="cancelAppointment()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Hủy bỏ lịch hẹn này
                    </button>
                </div>
            </div>

        </div>{{-- end scrollable --}}
    </div>
</div>

{{-- ===== MODAL ĐĂNG KÝ LỊCH HẸN MỚI ===== --}}
<div id="newAptModal" class="fixed inset-0 z-50 hidden">
    <div onclick="closeNewApt()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div id="newAptPanel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-300 opacity-0 scale-95 max-h-[90vh] flex flex-col">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-lg font-bold text-white" id="newAptTitle">Đặt lịch khám mới</h3>
                <button onclick="closeNewApt()" class="text-white/70 hover:text-white p-1 rounded transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4 overflow-y-auto flex-1">
                {{-- Tabs: Bệnh nhân cũ / Đăng ký mới --}}
                <div class="flex rounded-lg bg-gray-100 p-1 gap-1">
                    <button onclick="switchAptTab('existing')" id="tab-existing" class="flex-1 py-1.5 text-sm font-semibold rounded-md transition-colors bg-white text-blue-600 shadow-sm">Bệnh nhân cũ</button>
                    <button onclick="switchAptTab('new')" id="tab-new" class="flex-1 py-1.5 text-sm font-semibold rounded-md transition-colors text-gray-500">Đăng ký mới</button>
                </div>

                {{-- Existing patient --}}
                <div id="apt-existing">
                    <label class="text-sm font-semibold text-gray-700 mb-1 block">Chọn bệnh nhân</label>
                    <select id="selPatient" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:border-blue-500">
                        <option value="" disabled selected>-- Tìm bệnh nhân --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }} {{ $p->phone ? '— '.$p->phone : '' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- New patient --}}
                <div id="apt-new" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Họ tên <span class="text-red-500">*</span></label>
                            <input type="text" id="newName" placeholder="Nguyễn Văn A"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">SĐT</label>
                            <input type="text" id="newPhone" placeholder="0901234567"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Năm sinh</label>
                            <input type="number" id="newBirthYear" placeholder="1990" min="1900" max="2026"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Giới tính</label>
                            <select id="newGender" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:border-blue-500">
                                <option value="">--</option>
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Địa chỉ</label>
                            <input type="text" id="newAddress" placeholder="Địa chỉ..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Common fields --}}
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1 block">Bác sĩ <span class="text-red-500">*</span></label>
                        <select id="selDoctor" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:border-blue-500">
                            <option value="" disabled selected>-- Chọn --</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}">{{ $d->user->full_name ?? '?' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1 block">Ngày khám <span class="text-red-500">*</span></label>
                        <input type="date" id="aptDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1 block">Giờ khám <span class="text-red-500">*</span></label>
                        <input type="time" id="aptTime" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1 block">Trạng thái</label>
                        <div class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500">
                            ⏳ Chờ khám (mặc định)
                        </div>
                    </div>
                </div>

                <p id="newAptMsg" class="text-center text-sm font-medium mt-1"></p>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <button onclick="closeNewApt()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium text-sm">Hủy</button>
                <button onclick="saveAppointment()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm text-sm flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Xác nhận đặt lịch
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN PAGE ===== --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Tiếp nhận bệnh nhân</h2>
        <p class="text-gray-500 text-sm mt-1">Lễ tân — {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 border border-gray-300 rounded-lg shadow-sm">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <input type="date" id="filterDate" value="{{ date('Y-m-d') }}" onchange="loadAppointments()"
                   class="border-none p-0 text-sm focus:ring-0 text-gray-700 bg-transparent">
        </div>

        <button onclick="openNewApt()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2 text-sm">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Đặt lịch mới
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div onclick="filterByStatus('unconfirmed')" id="card-unconfirmed"
         class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-amber-700" id="cntUnconfirmed">—</p>
        <p class="text-[10px] text-amber-600 font-medium mt-1">Chờ xác nhận</p>
    </div>
    <div onclick="filterByStatus('pending')" id="card-pending"
         class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-yellow-700" id="cntPending">—</p>
        <p class="text-[10px] text-yellow-600 font-medium mt-1">Chờ khám</p>
    </div>
    <div onclick="filterByStatus('inprocess')" id="card-inprocess"
         class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-blue-700" id="cntInprocess">—</p>
        <p class="text-[10px] text-blue-600 font-medium mt-1">Đang khám</p>
    </div>
    <div onclick="filterByStatus('complete')" id="card-complete"
         class="bg-green-50 border border-green-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-green-700" id="cntComplete">—</p>
        <p class="text-[10px] text-green-600 font-medium mt-1">Hoàn thành</p>
    </div>
    <div onclick="filterByStatus('cancel')" id="card-cancel"
         class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-gray-700" id="cntCancel">—</p>
        <p class="text-[10px] text-gray-600 font-medium mt-1">Đã hủy</p>
    </div>
</div>

{{-- Workload Summary --}}
<div id="workloadSummary" class="mb-6 p-4 bg-white border border-gray-100 rounded-xl shadow-sm hidden">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-1.5 h-4 bg-blue-600 rounded-full"></div>
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Mật độ công việc bác sĩ</h3>
    </div>
    <div id="workloadList" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        {{-- JS Render --}}
    </div>
</div>

{{-- Appointment cards --}}
<div id="aptList" class="space-y-3">
    <div class="text-center py-12 text-gray-400">Đang tải...</div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentAptId  = null;
let currentRecordId = null;
let currentInvoiceId = null;
let currentAptTab = 'existing';
let allAppointments = []; // Cache for filtering
let currentStatusFilter = 'all';
let currentDoctorFilter = 'all';

// =========================================================
// APPOINTMENT LIST
// =========================================================
function loadAppointments() {
    const date = document.getElementById('filterDate').value;
    fetch(`{{ route('reception.appointments.load') }}?date=${date}`)
    .then(r => r.json())
    .then(data => {
        allAppointments = data.appointments;
        renderWorkload(data.appointments);
        renderAppointments();
    })
    .catch(e => console.error(e));
}

function renderWorkload(apts) {
    const counts = {};
    apts.forEach(a => {
        const drName = a.doctor && a.doctor.user ? a.doctor.user.full_name : 'N/A';
        const drId   = a.doctor_id || 'na';
        if (!counts[drId]) counts[drId] = { id: drId, name: drName, count: 0 };
        counts[drId].count++;
    });

    const list = document.getElementById('workloadList');
    const container = document.getElementById('workloadSummary');
    
    if (apts.length === 0) {
        container.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');

    // "All Doctors" Card
    const allActive = currentDoctorFilter === 'all';
    let html = `
        <div onclick="filterByDoctor('all')" 
             class="cursor-pointer border-2 rounded-lg p-2 flex flex-col items-center justify-center transition-all ${allActive ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-100 bg-gray-50 hover:border-gray-300'}">
            <p class="text-[10px] text-gray-400 font-bold uppercase">Tất cả BS</p>
            <p class="text-lg font-bold text-blue-700">${apts.length} ca</p>
        </div>
    `;

    // Doctor Cards
    html += Object.values(counts).sort((a,b) => b.count - a.count).map(c => {
        const isActive = currentDoctorFilter == c.id;
        const isDense  = c.count >= 10;
        return `
        <div onclick="filterByDoctor(${c.id})" 
             class="cursor-pointer border-2 rounded-lg p-2 flex flex-col items-center justify-center transition-all ${isActive ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-100 bg-gray-50 hover:border-gray-300'}">
            <p class="text-xs text-gray-500 truncate w-full text-center" title="${c.name}">${c.name}</p>
            <p class="text-lg font-bold ${isDense ? 'text-red-600' : 'text-gray-800'}">${c.count} ca</p>
            ${isDense ? '<span class="text-[8px] bg-red-100 text-red-700 px-1 rounded font-bold">DÀY ĐẶC</span>' : ''}
        </div>`;
    }).join('');

    list.innerHTML = html;
}

function filterByDoctor(drId) {
    currentDoctorFilter = drId;
    renderWorkload(allAppointments);
    renderAppointments();
}

function renderAppointments() {
    const apts = allAppointments;

    document.getElementById('cntUnconfirmed').innerText = apts.filter(a => a.status === 'unconfirmed').length;
    document.getElementById('cntPending').innerText     = apts.filter(a => a.status === 'pending').length;
    document.getElementById('cntInprocess').innerText   = apts.filter(a => a.status === 'inprocess').length;
    document.getElementById('cntComplete').innerText    = apts.filter(a => a.status === 'complete').length;
    document.getElementById('cntCancel').innerText      = apts.filter(a => a.status === 'cancel').length;

    // Filter by status & doctor 
    let filtered = apts;
    if (currentStatusFilter !== 'all') {
        filtered = filtered.filter(a => a.status === currentStatusFilter);
    }
    if (currentDoctorFilter !== 'all') {
        filtered = filtered.filter(a => a.doctor_id == currentDoctorFilter);
    }

    const statusCfg = {
        unconfirmed: { label: '🟠 Chờ xác nhận', bg: 'bg-amber-50',  text: 'text-amber-700', border: 'border-amber-200', dot: 'bg-amber-500' },
        pending:     { label: '⏳ Chờ khám',     bg: 'bg-yellow-50', text: 'text-yellow-700', border: 'border-yellow-200', dot: 'bg-yellow-400' },
        inprocess:   { label: '🔵 Đang khám',    bg: 'bg-blue-50',   text: 'text-blue-700',   border: 'border-blue-200',   dot: 'bg-blue-500' },
        complete:    { label: '✅ Hoàn thành',   bg: 'bg-green-50',  text: 'text-green-700',  border: 'border-green-200',  dot: 'bg-green-500' },
        cancel:      { label: '❌ Đã hủy',       bg: 'bg-gray-50',   text: 'text-gray-700',   border: 'border-gray-200',   dot: 'bg-gray-400' },
    };

    if (filtered.length === 0) {
        document.getElementById('aptList').innerHTML = `
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-16 text-center text-gray-400">
                <svg class="w-14 h-14 mx-auto mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                ${currentStatusFilter === 'all' ? 'Không có lịch khám nào trong ngày này.' : 'Không có lịch khám nào với trạng thái này.'}
            </div>`;
        return;
    }

    const html = filtered.map(a => {
        const cfg  = statusCfg[a.status] || { label: a.status, bg: 'bg-gray-50', text: 'text-gray-700', border: 'border-gray-200', dot: 'bg-gray-400' };
        const time = a.time ? a.time.substring(0, 5) : '--:--';
        // Truyền object a vào openPanel (tránh lỗi JSON.stringify phức tạp)
        return `
        <div onclick="openAptByObject(${allAppointments.indexOf(a)})" 
             class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all cursor-pointer p-5 flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg group-hover:bg-blue-200 transition-colors">
                    ${a.patient ? a.patient.full_name.charAt(0).toUpperCase() : '?'}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded leading-none">#${a.id}</span>
                        <h4 class="font-bold text-gray-800 truncate">${a.patient ? a.patient.full_name : 'N/A'}</h4>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${cfg.bg} ${cfg.text} border ${cfg.border}">
                            ${cfg.label}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        BS. ${a.doctor && a.doctor.user ? a.doctor.user.full_name : 'Chưa phân công'} — <span class="bg-gray-100 px-1.5 py-0.5 rounded font-medium">🕒 ${time}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>`;
    }).join('');

    document.getElementById('aptList').innerHTML = html;
}

function openAptByObject(index) {
    if (allAppointments[index]) {
        openPanel(allAppointments[index]);
    }
}

function filterByStatus(status) {
    if (currentStatusFilter === status) {
        currentStatusFilter = 'all'; // Toggle off
    } else {
        currentStatusFilter = status;
    }

    // Update UI active state
    ['unconfirmed', 'pending', 'inprocess', 'complete', 'cancel'].forEach(s => {
        const card = document.getElementById('card-' + s);
        if (s === currentStatusFilter) {
            card.classList.add('ring-2', 'ring-offset-2', 'shadow-md', 'scale-[1.02]');
            card.classList.remove('hover:scale-[1.02]');
            if (s === 'unconfirmed') card.classList.add('ring-amber-400');
            if (s === 'pending') card.classList.add('ring-yellow-400');
            if (s === 'inprocess') card.classList.add('ring-blue-400');
            if (s === 'complete') card.classList.add('ring-green-400');
            if (s === 'cancel') card.classList.add('ring-gray-400');
        } else {
            card.classList.remove('ring-2', 'ring-offset-2', 'ring-amber-400', 'ring-yellow-400', 'ring-blue-400', 'ring-green-400', 'ring-gray-400', 'shadow-md', 'scale-[1.02]');
            card.classList.add('hover:scale-[1.02]');
        }
    });

    renderAppointments();
}

// =========================================================
// SLIDE PANEL
// =========================================================
let currentPatientId = null;

function openPanel(a) {
    currentAptId = a.id;
    currentPatientId = a.patient_id;
    toggleEditPatient('view');

    const pt  = a.patient || {};
    const dr  = a.doctor && a.doctor.user ? a.doctor.user : {};
    const time = a.time ? a.time.substring(0, 5) : '--:--';

    document.getElementById('panelPatientName').innerText = pt.full_name || 'N/A';
    document.getElementById('panelInfo').innerText = `Lịch #${a.id} — ${a.date} ${time}`;

    // Fill patient info
    document.getElementById('ptName').innerText      = pt.full_name || '—';
    document.getElementById('ptBirthYear').innerText = pt.birth_year || '—';
    document.getElementById('ptGender').innerText    = pt.gender == 1 ? 'Nam' : (pt.gender == 0 ? 'Nữ' : '—');
    document.getElementById('ptPhone').innerText     = pt.phone || '—';
    document.getElementById('ptAddress').innerText   = pt.address || '—';

    // Set edit inputs
    document.getElementById('editPtName').value      = pt.full_name || '';
    document.getElementById('editPtBirthYear').value = pt.birth_year || '';
    document.getElementById('editPtGender').value    = pt.gender ?? '1';
    document.getElementById('editPtPhone').value     = pt.phone || '';
    document.getElementById('editPtAddress').value   = pt.address || '';

    document.getElementById('aptDoctor').innerText   = dr.full_name || '—';
    document.getElementById('panelAptDate').innerText = a.date;
    document.getElementById('panelAptTime').innerText = time;

    const statusLabel = {
        unconfirmed: '🟠 Chờ xác nhận',
        pending:     '⏳ Chờ khám',
        inprocess:   '🔵 Đang khám',
        complete:    '✅ Hoàn thành',
        cancel:      '❌ Đã hủy',
    }[a.status] || a.status;

    const statusBadge = document.getElementById('statusBadge');
    statusBadge.innerText = statusLabel;
    statusBadge.className = `px-3 py-1 rounded-full text-xs font-semibold border ` + ({
        unconfirmed: 'bg-amber-50 text-amber-700 border-amber-200',
        pending:     'bg-yellow-50 text-yellow-700 border-yellow-200',
        inprocess:   'bg-blue-50 text-blue-700 border-blue-200',
        complete:    'bg-green-50 text-green-700 border-green-200',
        cancel:      'bg-gray-50 text-gray-700 border-gray-200',
    }[a.status] || '');

    // Action buttons
    const invLink = document.getElementById('invoiceLinkBox');
    if (invLink) invLink.classList.toggle('hidden', a.status !== 'complete');

    const confirmBox = document.getElementById('confirmActionBox');
    if (confirmBox) confirmBox.classList.toggle('hidden', a.status !== 'unconfirmed');

    const cancelBox = document.getElementById('cancelActionBox');
    if (cancelBox) cancelBox.classList.toggle('hidden', a.status === 'complete' || a.status === 'cancel');

    document.getElementById('examPanel').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('examSlide').classList.remove('translate-x-full');
        document.getElementById('examSlide').classList.add('translate-x-0');
    }, 10);
}

function closePanel() {
    document.getElementById('examSlide').classList.remove('translate-x-0');
    document.getElementById('examSlide').classList.add('translate-x-full');
    setTimeout(() => document.getElementById('examPanel').classList.add('hidden'), 300);
    loadAppointments();
}



async function cancelAppointment() {
    if (!currentAptId) return;
    
    showConfirm('Bạn có chắc chắn muốn HỦY lịch hẹn này không?', async () => {
        try {
            const res = await fetch(`/reception/appointments/${currentAptId}/status`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ status: 'cancel' })
        }).then(r => r.json());

            if (res.status === 'success') {
                showToast('Đã hủy lịch hẹn', 'info');
                closePanel();
            } else {
                showToast(res.message || 'Lỗi hủy lịch', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Lỗi kết nối', 'error');
        }
    }, null, { title: 'Hủy lịch hẹn', type: 'error', confirmText: 'Đồng ý hủy' });
}

function toggleEditPatient(mode) {
    const isEdit = mode === 'edit';
    document.getElementById('patientInfoView').classList.toggle('hidden', isEdit);
    document.getElementById('patientInfoEdit').classList.toggle('hidden', !isEdit);
}

async function savePatientInfo() {
    if (!currentPatientId) return;
    
    const data = {
        full_name:  document.getElementById('editPtName').value.trim(),
        birth_year: document.getElementById('editPtBirthYear').value || null,
        gender:     document.getElementById('editPtGender').value,
        phone:      document.getElementById('editPtPhone').value.trim(),
        address:    document.getElementById('editPtAddress').value.trim(),
    };

    if (!data.full_name) return showToast('Họ tên không được để trống', 'warning');

    try {
        const res = await fetch(`/reception/patients/${currentPatientId}`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': token, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify(data)
        }).then(r => r.json());

        if (res.status === 'success') {
            showToast('Đã cập nhật thông tin bệnh nhân', 'success');
            loadAppointments(); 
            document.getElementById('ptName').innerText      = data.full_name;
            document.getElementById('ptBirthYear').innerText = data.birth_year || '—';
            document.getElementById('ptGender').innerText    = data.gender == 1 ? 'Nam' : 'Nữ';
            document.getElementById('ptPhone').innerText     = data.phone || '—';
            document.getElementById('ptAddress').innerText   = data.address || '—';
            document.getElementById('panelPatientName').innerText = data.full_name;
            
            toggleEditPatient('view');
        } else {
            alert(res.message || 'Lỗi cập nhật');
        }
    } catch (e) {
        console.error(e);
        alert('Lỗi kết nối');
    }
}

async function confirmAppointment() {
    if (!currentAptId) return;
    
    showConfirm('Xác nhận lịch hẹn này và chuyển sang danh sách chờ khám?', async () => {
        try {
            const res = await fetch(`/reception/appointments/${currentAptId}/status`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ status: 'pending' })
        }).then(r => r.json());

            if (res.status === 'success') {
                showToast('Đã xác nhận lịch hẹn', 'success');
                closePanel();
            } else {
                showToast(res.message || 'Lỗi xác nhận', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Lỗi kết nối', 'error');
        }
    }, null, { title: 'Xác nhận lịch hẹn', type: 'info', confirmText: 'Xác nhận ngay' });
}

// =========================================================
// NEW APPOINTMENT MODAL
// =========================================================
function openNewApt() {
    document.getElementById('newAptModal').classList.remove('hidden');
    setTimeout(() => {
        const p = document.getElementById('newAptPanel');
        p.classList.remove('opacity-0', 'scale-95');
        p.classList.add('opacity-100', 'scale-100');
    }, 10);
    // Default date to today
    document.getElementById('aptDate').value = document.getElementById('filterDate').value;
}

function closeNewApt() {
    const p = document.getElementById('newAptPanel');
    p.classList.remove('opacity-100', 'scale-100');
    p.classList.add('opacity-0', 'scale-95');
    setTimeout(() => document.getElementById('newAptModal').classList.add('hidden'), 250);
}

function switchAptTab(tab) {
    currentAptTab = tab;
    const isExisting = tab === 'existing';
    document.getElementById('apt-existing').classList.toggle('hidden', !isExisting);
    document.getElementById('apt-new').classList.toggle('hidden', isExisting);
    document.getElementById('tab-existing').className = `flex-1 py-1.5 text-sm font-semibold rounded-md transition-colors ${isExisting ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500'}`;
    document.getElementById('tab-new').className      = `flex-1 py-1.5 text-sm font-semibold rounded-md transition-colors ${!isExisting ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500'}`;
}

async function saveAppointment() {
    const msgEl = document.getElementById('newAptMsg');
    msgEl.innerText = '';
    let patientId = null;

    // Step 1: create new patient if needed
    if (currentAptTab === 'new') {
        const name = document.getElementById('newName').value.trim();
        if (!name) { msgEl.className = 'text-red-500 text-sm font-medium text-center'; msgEl.innerText = 'Nhập họ tên bệnh nhân'; return; }

        const res = await fetch('{{ route("reception.patients.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                full_name:  name,
                phone:      document.getElementById('newPhone').value,
                birth_year: document.getElementById('newBirthYear').value || null,
                gender:     document.getElementById('newGender').value !== '' ? document.getElementById('newGender').value : null,
                address:    document.getElementById('newAddress').value,
            })
        }).then(r => r.json());

        if (res.status !== 'success') {
            msgEl.className = 'text-red-500 text-sm font-medium text-center';
            if (res.errors) {
                const errMsgs = Object.values(res.errors).flat();
                msgEl.innerText = errMsgs.join(', ');
            } else {
                msgEl.innerText = res.message || 'Lỗi tạo bệnh nhân';
            }
            return;
        }
        patientId = res.patient.id;
    } else {
        patientId = document.getElementById('selPatient').value;
        if (!patientId) { msgEl.className = 'text-red-500 text-sm font-medium text-center'; msgEl.innerText = 'Chọn bệnh nhân'; return; }
    }

    // Step 2: create appointment
    const data = {
        patient_id: patientId,
        doctor_id:  document.getElementById('selDoctor').value,
        date:       document.getElementById('aptDate').value,
        time:       document.getElementById('aptTime').value,
    };

    if (!data.doctor_id || !data.date || !data.time) {
        msgEl.className = 'text-red-500 text-sm font-medium text-center';
        msgEl.innerText = 'Vui lòng điền đủ thông tin lịch hẹn';
        return;
    }

    fetch('{{ route("reception.appointments.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            msgEl.className = 'text-green-600 text-sm font-medium text-center';
            msgEl.innerText = '✓ ' + res.message;
            loadAppointments();
            setTimeout(closeNewApt, 700);
        } else {
            msgEl.className = 'text-red-500 text-sm font-medium text-center';
            if (res.errors) {
                const errMsgs = Object.values(res.errors).flat();
                msgEl.innerText = errMsgs.join(', ');
            } else {
                msgEl.innerText = res.message || 'Có lỗi xảy ra';
            }
        }
    })
    .catch(e => console.error(e));
}

// Init
loadAppointments();
</script>
@endsection
