@extends('layouts.app')

@section('content')

{{-- ===== SLIDE PANEL (khám bệnh all-in-one) ===== --}}
<div id="examPanel" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div onclick="closePanel()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity duration-300" id="examBackdrop"></div>
    
    {{-- Slide panel từ phải --}}
    <div id="examSlide" class="absolute right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out">
        
        {{-- Panel Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-teal-600 to-emerald-600 text-white flex-shrink-0">
            <div>
                <h2 class="text-lg font-bold" id="panelPatientName">—</h2>
                <p class="text-teal-100 text-sm" id="panelInfo">—</p>
            </div>
            <button onclick="closePanel()" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Trạng thái (Tự động cập nhật) --}}
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex-shrink-0 flex items-center gap-3">
            <span class="text-sm font-medium text-gray-600">Trạng thái:</span>
            <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold border transition-all">—</span>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 flex-shrink-0 bg-white">
            <button onclick="switchTab('record')" id="tab-record"
                class="flex-1 py-3 text-sm font-semibold border-b-2 transition-colors tab-btn border-teal-600 text-teal-600">
                📋 Hồ sơ khám
            </button>
            <button onclick="switchTab('prescription')" id="tab-prescription"
                class="flex-1 py-3 text-sm font-semibold border-b-2 transition-colors tab-btn border-transparent text-gray-500 hover:text-gray-700">
                💊 Đơn thuốc
            </button>
            <button onclick="switchTab('history')" id="tab-history"
                class="flex-1 py-3 text-sm font-semibold border-b-2 transition-colors tab-btn border-transparent text-gray-500 hover:text-gray-700">
                ⌛ Lịch sử
            </button>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto">

            {{-- TAB: HỒ SƠ KHÁM --}}
            <div id="tab-content-record" class="p-6 space-y-4">
                <div id="existingRecord" class="hidden bg-teal-50 border border-teal-200 rounded-xl p-4 mb-2">
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-teal-600 font-semibold uppercase mb-1">Hồ sơ đã có</p>
                        <button onclick="editRecord()" class="text-xs text-teal-700 hover:underline">Sửa</button>
                    </div>
                    <p class="text-sm text-gray-700"><span class="font-semibold">Chẩn đoán:</span> <span id="existingDiagnosis">—</span></p>
                    <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Kết quả:</span> <span id="existingResult">—</span></p>
                </div>

                <div id="recordForm">
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Chẩn đoán <span class="text-red-500">*</span></label>
                        <textarea id="diagnosis" rows="3" placeholder="Nhập chẩn đoán bệnh lý..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500 resize-none transition-colors"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kết quả khám lâm sàng <span class="text-red-500">*</span></label>
                        <textarea id="examinationResult" rows="4" placeholder="Mô tả kết quả thăm khám..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500 resize-none transition-colors"></textarea>
                    </div>
                    <button onclick="saveRecord()" id="saveRecordBtn"
                        class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Lưu hồ sơ khám
                    </button>
                    <p id="recordMsg" class="text-center text-sm mt-2 font-medium"></p>
                </div>
            </div>

            {{-- TAB: ĐƠN THUỐC --}}
            <div id="tab-content-prescription" class="p-6 hidden">
                <div id="noPrescNote" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4 text-sm text-yellow-800">
                    ⚠️ Cần tạo <strong>hồ sơ khám</strong> trước khi kê đơn thuốc.
                </div>
                <div id="prescSection" class="hidden">
                    {{-- Existing prescriptions --}}
                    <div id="existingPrescriptions" class="mb-4 space-y-2"></div>

                    {{-- Form kê đơn mới --}}
                    <div id="prescForm" class="border border-gray-200 rounded-xl p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Kê đơn thuốc mới</p>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Ghi chú / Hướng dẫn</label>
                            <input type="text" id="prescContent" placeholder="Ví dụ: Uống sau ăn, 7 ngày..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-teal-500">
                        </div>

                        {{-- Danh sách thuốc --}}
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-medium text-gray-600">Thuốc <span class="text-red-500">*</span></label>
                            <button onclick="addMedRow()" class="text-xs text-teal-600 hover:text-teal-800 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Thêm thuốc
                            </button>
                        </div>
                        <div id="medRows" class="space-y-2 mb-3"></div>

                        <button onclick="savePrescription()"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kê đơn thuốc
                        </button>
                        
                        <div class="relative flex py-4 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink mx-4 text-gray-400 text-[10px] font-bold uppercase tracking-widest">Hoặc</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <button onclick="finishWithoutPrescription()"
                            class="w-full py-2.5 bg-white border-2 border-teal-600 text-teal-600 hover:bg-teal-50 font-bold rounded-lg transition-all text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Hoàn thành & Không kê đơn
                        </button>

                        <p id="prescMsg" class="text-center text-sm mt-2 font-medium"></p>
                    </div>
                </div>
            </div>

            {{-- TAB: LỊCH SỬ KHÁM --}}
            <div id="tab-content-history" class="p-6 hidden overflow-y-auto">
                <div id="historyList" class="space-y-4">
                    <div class="text-center py-10 text-gray-400">Đang tải lịch sử...</div>
                </div>
            </div>

        </div>{{-- end scrollable --}}
    </div>
</div>

{{-- ===== MAIN PAGE ===== --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Lịch khám hôm nay</h2>
        <p class="text-gray-500 text-sm mt-1">BS. {{ auth()->user()->full_name }} — {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center gap-3">
        <label class="text-sm text-gray-600 font-medium">Chọn ngày:</label>
        <input type="date" id="filterDate" value="{{ date('Y-m-d') }}" onchange="loadAppointments()"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500">
    </div>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-3 gap-4 mb-6" id="statsRow">
    <div onclick="filterByStatus('pending')" id="card-pending"
         class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-yellow-700" id="cntPending">—</p>
        <p class="text-xs text-yellow-600 font-medium mt-1">Chờ khám</p>
    </div>
    <div onclick="filterByStatus('inprocess')" id="card-inprocess"
         class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-blue-700" id="cntInprocess">—</p>
        <p class="text-xs text-blue-600 font-medium mt-1">Đang khám</p>
    </div>
    <div onclick="filterByStatus('complete')" id="card-complete"
         class="bg-green-50 border border-green-200 rounded-xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.02] transition-all">
        <p class="text-2xl font-bold text-green-700" id="cntComplete">—</p>
        <p class="text-xs text-green-600 font-medium mt-1">Hoàn thành</p>
    </div>
</div>

{{-- Appointment cards --}}
<div id="aptList" class="space-y-3">
    <div class="text-center py-12 text-gray-400">Đang tải...</div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const medicines = @json($medicines ?? []);
let currentAptId   = null;
let currentPtId    = null;
let currentRecordId = null;
let allAppointments = []; // Cache for filtering
let currentStatusFilter = 'all';
let medRowIdx = 0;

// =========================================================
// APPOINTMENT LIST
// =========================================================
function loadAppointments() {
    const date = document.getElementById('filterDate').value;
    fetch(`{{ route('doctor.appointments.load') }}?date=${date}`)
    .then(r => r.json())
    .then(data => {
        allAppointments = data.appointments;
        renderAppointments();
    })
    .catch(e => console.error(e));
}

function renderAppointments() {
    const apts = allAppointments;

    document.getElementById('cntPending').innerText   = apts.filter(a => a.status === 'pending').length;
    document.getElementById('cntInprocess').innerText = apts.filter(a => a.status === 'inprocess').length;
    document.getElementById('cntComplete').innerText  = apts.filter(a => a.status === 'complete').length;

    // Filter by current status
    let filtered = apts;
    if (currentStatusFilter !== 'all') {
        filtered = apts.filter(a => a.status === currentStatusFilter);
    }

    const statusCfg = {
        pending:   { color: 'yellow', label: 'Chờ khám',    dot: 'bg-yellow-400' },
        inprocess: { color: 'blue',   label: 'Đang khám',   dot: 'bg-blue-500' },
        complete:  { color: 'green',  label: 'Hoàn thành',  dot: 'bg-green-500' },
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
        const cfg  = statusCfg[a.status] || { color: 'gray', label: a.status, dot: 'bg-gray-400' };
        const time = a.time ? a.time.substring(0, 5) : '--:--';
        const pt   = a.patient ? a.patient.full_name : 'N/A';
        return `
        <div onclick="openPanel(${a.id}, '${pt}', '${a.date}', '${time}', ${a.patient_id}, '${a.status}')"
             class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 transition-all cursor-pointer p-5 flex items-center gap-4 group">
            <div class="w-14 h-14 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xl flex-shrink-0 group-hover:bg-teal-200 transition-colors">
                ${pt.charAt(0).toUpperCase()}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 text-base truncate">${pt}</p>
                <p class="text-sm text-gray-500 mt-0.5">
                    <svg class="w-3.5 h-3.5 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ${time} — ${a.date}
                </p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-${cfg.color}-50 text-${cfg.color}-700 border border-${cfg.color}-200">
                    <span class="w-1.5 h-1.5 rounded-full ${cfg.dot}"></span>${cfg.label}
                </span>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>`;
    }).join('');

    document.getElementById('aptList').innerHTML = html;
}

function filterByStatus(status) {
    if (currentStatusFilter === status) {
        currentStatusFilter = 'all'; // Toggle off
    } else {
        currentStatusFilter = status;
    }

    // Update UI active state
    ['pending', 'inprocess', 'complete'].forEach(s => {
        const card = document.getElementById('card-' + s);
        if (!card) return;
        if (s === currentStatusFilter) {
            card.classList.add('ring-2', 'ring-offset-2', 'shadow-md', 'scale-[1.02]');
            card.classList.remove('hover:scale-[1.02]');
            if (s === 'pending') card.classList.add('ring-yellow-400');
            if (s === 'inprocess') card.classList.add('ring-blue-400');
            if (s === 'complete') card.classList.add('ring-green-400');
        } else {
            card.classList.remove('ring-2', 'ring-offset-2', 'ring-yellow-400', 'ring-blue-400', 'ring-green-400', 'shadow-md', 'scale-[1.02]');
            card.classList.add('hover:scale-[1.02]');
        }
    });

    renderAppointments();
}

// =========================================================
// SLIDE PANEL
// =========================================================
function openPanel(aptId, patientName, date, time, patientId, status) {
    currentAptId  = aptId;
    currentPtId   = patientId;
    currentRecordId = null;

    document.getElementById('panelPatientName').innerText = patientName;
    document.getElementById('panelInfo').innerText = `Lịch khám #${aptId} — ${date} ${time}`;

    // Reset tabs
    switchTab('record');
    resetRecordForm();
    resetPrescForm();

    // Set status badge
    refreshStatusBadge(status);

    // Show panel
    document.getElementById('examPanel').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('examSlide').classList.remove('translate-x-full');
        document.getElementById('examSlide').classList.add('translate-x-0');
    }, 10);

    // Load medical record for this appointment
    loadRecord(aptId, patientId, status);
    // Load patient history
    loadHistory(patientId);

    // 🔥 Tự động chuyển trạng thái sang "Đang khám" nếu đang là "Chờ khám"
    if (status === 'pending') {
        updateAptStatus(aptId, 'inprocess');
    }

    // Nếu đã hoàn thành thì ẩn các nút thao tác chính
    const isComplete = (status === 'complete');
    document.getElementById('saveRecordBtn').classList.toggle('hidden', isComplete);
    document.getElementById('recordForm').classList.toggle('hidden', isComplete && !!currentRecordId);
}

function closePanel() {
    document.getElementById('examSlide').classList.remove('translate-x-0');
    document.getElementById('examSlide').classList.add('translate-x-full');
    setTimeout(() => document.getElementById('examPanel').classList.add('hidden'), 300);
    loadAppointments(); // refresh list
}

// =========================================================
// STATUS
// =========================================================
function refreshStatusBadge(current) {
    const cfg = {
        pending:   { bg: 'bg-yellow-50',  text: 'text-yellow-700', border: 'border-yellow-200', label: '⏳ Chờ khám' },
        inprocess: { bg: 'bg-blue-50',    text: 'text-blue-700',   border: 'border-blue-200',   label: '🔵 Đang khám' },
        complete:  { bg: 'bg-green-50',   text: 'text-green-700',  border: 'border-green-200',  label: '✅ Hoàn thành' },
    };
    const s = cfg[current] || { bg: 'bg-gray-50', text: 'text-gray-600', border: 'border-gray-200', label: current };
    const badge = document.getElementById('statusBadge');
    if (badge) {
        badge.innerText = s.label;
        badge.className = `px-3 py-1 rounded-full text-xs font-semibold border transition-all ${s.bg} ${s.text} ${s.border}`;
    }
}

function updateAptStatus(aptId, newStatus) {
    fetch(`/doctor/appointments/${aptId}/status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            // Cập nhật ngầm trong allAppointments để không cần load lại toàn bộ
            const apt = allAppointments.find(a => a.id == aptId);
            if (apt) {
                apt.status = newStatus;
                renderAppointments();
                refreshStatusBadge(newStatus);
            }
        }
    })
    .catch(e => console.error('Lỗi cập nhật trạng thái:', e));
}

// =========================================================
// TABS
// =========================================================
function switchTab(tab) {
    ['record','prescription','history'].forEach(t => {
        const btn = document.getElementById('tab-' + t);
        const content = document.getElementById('tab-content-' + t);
        if (t === tab) {
            btn.classList.add('border-teal-600', 'text-teal-600');
            btn.classList.remove('border-transparent', 'text-gray-500');
            content.classList.remove('hidden');
        } else {
            btn.classList.remove('border-teal-600', 'text-teal-600');
            btn.classList.add('border-transparent', 'text-gray-500');
            content.classList.add('hidden');
        }
    });
}

// =========================================================
// MEDICAL RECORD
// =========================================================
function resetRecordForm() {
    document.getElementById('diagnosis').value = '';
    document.getElementById('examinationResult').value = '';
    document.getElementById('recordMsg').innerText = '';
    document.getElementById('existingRecord').classList.add('hidden');
    document.getElementById('recordForm').classList.remove('hidden');
    document.getElementById('saveRecordBtn').innerText = 'Lưu hồ sơ khám';
}

function loadRecord(aptId, patientId, status) {
    const isComplete = (status === 'complete');
    
    // Tải toàn bộ hồ sơ của bác sĩ, lọc theo appointment_id
    fetch('{{ route("doctor.medical_records.load") }}')
    .then(r => r.json())
    .then(data => {
        const rec = data.medical_records.find(r => r.appointment_id == aptId);
        if (rec) {
            currentRecordId = rec.id;
            document.getElementById('existingDiagnosis').innerText = rec.diagnosis;
            document.getElementById('existingResult').innerText = rec.examination_result;
            document.getElementById('existingRecord').classList.remove('hidden');
            
            // Ẩn nút sửa nếu đã hoàn thành
            const editBtn = document.querySelector('#existingRecord button');
            if (editBtn) editBtn.classList.toggle('hidden', isComplete);

            document.getElementById('diagnosis').value = rec.diagnosis;
            document.getElementById('examinationResult').value = rec.examination_result;
            document.getElementById('saveRecordBtn').innerText = 'Cập nhật hồ sơ';
            
            // Nếu đã hoàn thành thì không hiện form nữa
            if (isComplete) {
                document.getElementById('recordForm').classList.add('hidden');
            }

            // Show prescription section
            showPrescSection(rec.id, status);
        } else {
            document.getElementById('noPrescNote').classList.toggle('hidden', isComplete);
            document.getElementById('prescSection').classList.add('hidden');
            
            if (isComplete) {
                document.getElementById('recordForm').innerHTML = '<div class="text-center py-10 text-gray-400">Không tìm thấy hồ sơ khám cho ca này.</div>';
            }
        }
    })
    .catch(e => console.error(e));
}

function editRecord() {
    document.getElementById('existingRecord').classList.add('hidden');
    document.getElementById('recordForm').classList.remove('hidden');
}

function saveRecord() {
    const diagnosis = document.getElementById('diagnosis').value.trim();
    const exResult  = document.getElementById('examinationResult').value.trim();
    const msgEl = document.getElementById('recordMsg');

    if (!diagnosis || !exResult) {
        msgEl.className = 'text-red-500 text-sm mt-2 font-medium text-center';
        msgEl.innerText = 'Vui lòng điền đầy đủ chẩn đoán và kết quả khám';
        return;
    }

    const isUpdate = !!currentRecordId;
    const url = isUpdate ? `/doctor/medical-records/${currentRecordId}` : '{{ route("doctor.medical_records.store") }}';

    fetch(url, {
        method: isUpdate ? 'PUT' : 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            appointment_id: currentAptId,
            patient_id: currentPtId,
            diagnosis,
            examination_result: exResult
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-green-600 text-sm mt-2 font-medium text-center';
            msgEl.innerText = '✓ ' + data.message;
            // Reload record info
            loadRecord(currentAptId, currentPtId);
            if (!isUpdate) refreshStatusBadge('inprocess');
        } else {
            msgEl.className = 'text-red-500 text-sm mt-2 font-medium text-center';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => console.error(e));
}

// =========================================================
// PRESCRIPTIONS
// =========================================================
function showPrescSection(recordId, status) {
    const isComplete = (status === 'complete');
    document.getElementById('noPrescNote').classList.add('hidden');
    document.getElementById('prescSection').classList.remove('hidden');
    
    // Ẩn form kê đơn nếu đã hoàn thành
    document.getElementById('prescForm').classList.toggle('hidden', isComplete);

    // Load existing prescriptions for this record
    fetch('{{ route("doctor.prescriptions.load") }}')
    .then(r => r.json())
    .then(data => {
        const prescs = data.prescriptions.filter(p => p.medical_record_id == recordId);
        const container = document.getElementById('existingPrescriptions');
        if (prescs.length > 0) {
            container.innerHTML = prescs.map(p => {
                const statusLabel = p.dispense_status === 'dispensed' ? '✅ Đã phát' : '⏳ Chưa phát';
                const items = (p.items || []).map(it =>
                    `<li class="text-xs text-gray-600">• ${it.medicine ? it.medicine.name : '?'} × ${it.quantity} ${it.medicine ? it.medicine.unit : ''} ${it.dosage ? '| ' + it.dosage : ''}</li>`
                ).join('');
                return `<div class="bg-indigo-50 border border-indigo-200 rounded-xl p-3 mb-2">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs font-semibold text-indigo-700">Đơn #${p.id}</span>
                        <span class="text-xs text-gray-500">${statusLabel}</span>
                    </div>
                    <ul class="space-y-0.5">${items}</ul>
                    ${p.content ? `<p class="text-xs text-gray-500 mt-1 italic">${p.content}</p>` : ''}
                </div>`;
            }).join('');
        } else {
            container.innerHTML = '';
        }
    });
}

function addMedRow() {
    const idx  = medRowIdx++;
    const opts = medicines.map(m =>
        `<option value="${m.id}">${m.name} (${m.stock} ${m.unit})</option>`
    ).join('');
    document.getElementById('medRows').insertAdjacentHTML('beforeend', `
    <div id="mr-${idx}" class="grid grid-cols-12 gap-1.5 items-center">
        <div class="col-span-5">
            <select class="med-sel w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-white focus:border-teal-500">
                <option value="" disabled selected>-- Thuốc --</option>${opts}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" min="1" placeholder="SL" class="qty-in w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:border-teal-500">
        </div>
        <div class="col-span-2">
            <input type="text" placeholder="Liều" class="dos-in w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:border-teal-500">
        </div>
        <div class="col-span-2">
            <input type="text" placeholder="Cách dùng" class="usg-in w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:border-teal-500">
        </div>
        <div class="col-span-1 flex justify-center">
            <button onclick="document.getElementById('mr-${idx}').remove()" class="text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>`);
}

function resetPrescForm() {
    document.getElementById('prescContent').value = '';
    document.getElementById('medRows').innerHTML = '';
    document.getElementById('prescMsg').innerText = '';
    document.getElementById('existingPrescriptions').innerHTML = '';
    medRowIdx = 0;
    addMedRow();
}

function savePrescription() {
    const msgEl = document.getElementById('prescMsg');
    if (!currentRecordId) {
        msgEl.className = 'text-red-500 text-sm mt-2 font-medium text-center';
        msgEl.innerText = 'Tạo hồ sơ khám trước khi kê đơn';
        return;
    }

    const rows = document.querySelectorAll('#medRows > div');
    const items = [];
    let valid = true;
    rows.forEach(row => {
        const medId = row.querySelector('.med-sel').value;
        const qty   = parseInt(row.querySelector('.qty-in').value);
        if (!medId || !qty) { valid = false; return; }
        items.push({
            medicine_id: medId, quantity: qty,
            dosage: row.querySelector('.dos-in').value,
            usage:  row.querySelector('.usg-in').value
        });
    });

    if (!valid || items.length === 0) {
        msgEl.className = 'text-red-500 text-sm mt-2 font-medium text-center';
        msgEl.innerText = 'Vui lòng chọn thuốc và nhập số lượng';
        return;
    }

    fetch('/prescriptions', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            medical_record_id: currentRecordId,
            dispense_status: 'pending',
            content: document.getElementById('prescContent').value,
            items
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-green-600 text-sm mt-2 font-medium text-center';
            msgEl.innerText = '✓ Kê đơn thành công!';
            showPrescSection(currentRecordId);
            refreshStatusBadge('complete');
            resetPrescForm();
        } else {
            msgEl.className = 'text-red-500 text-sm mt-2 font-medium text-center';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => console.error(e));
}

function finishWithoutPrescription() {
    showConfirm("Bạn xác nhận hoàn thành ca khám này mà không kê đơn thuốc?", () => {
        updateAptStatus(currentAptId, 'complete');
        showToast("Đã hoàn thành ca khám", "success");
        setTimeout(closePanel, 1000);
    });
}

// =========================================================
// MEDICAL HISTORY
// =========================================================
function loadHistory(patientId) {
    const list = document.getElementById('historyList');
    if (!list) return;

    fetch(`/doctor/patients/${patientId}/history`)
    .then(r => {
        if (!r.ok) {
            console.error("Fetch error:", r.status, r.statusText);
            throw new Error('Network response was not ok');
        }
        return r.json();
    })
    .then(data => {
        console.log("History data received:", data);
        // Lọc bỏ lần khám hiện tại (nếu đang dở dang) để tránh trùng lặp thông tin đang nhập
        const hist = data.history.filter(h => h.appointment_id != currentAptId); 
        
        if (hist.length === 0) {
            list.innerHTML = `<div class="bg-gray-50 border border-gray-100 rounded-xl py-10 text-center text-gray-400">
                                <p class="text-sm">Bệnh nhân chưa có lịch sử khám trước đây.</p>
                             </div>`;
            return;
        }

        list.innerHTML = hist.map(h => {
            const date = new Date(h.created_at).toLocaleDateString('vi-VN');
            const drName = h.doctor && h.doctor.user ? h.doctor.user.full_name : 'N/A';
            const meds = (h.prescription && h.prescription.items && h.prescription.items.length > 0) 
                ? h.prescription.items.map(it => 
                    `• ${it.medicine ? it.medicine.name : '?'} (${it.quantity} ${it.medicine ? it.medicine.unit : ''})`
                ).join('<br>') 
                : '<span class="text-gray-400 italic">Không kê đơn</span>';

            return `
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden mb-3">
                <div class="bg-gray-50 px-4 py-2 flex justify-between items-center border-b border-gray-100">
                    <span class="text-xs font-bold text-teal-700">${date}</span>
                    <span class="text-xs text-gray-500 font-medium">BS. ${drName}</span>
                </div>
                <div class="p-4 space-y-2">
                    <div class="text-sm text-gray-800"><span class="font-semibold">Chẩn đoán:</span> ${h.diagnosis}</div>
                    <div class="text-sm text-gray-800"><span class="font-semibold">Kết quả:</span> ${h.examination_result}</div>
                    <div class="mt-2 pt-2 border-t border-dashed border-gray-100 italic text-xs text-indigo-700">
                        <span class="font-bold not-italic">Đơn thuốc:</span><br>${meds}
                    </div>
                </div>
            </div>`;
        }).join('');
    })
    .catch(e => {
        console.error(e);
        list.innerHTML = `<div class="text-red-500 text-sm text-center">Lỗi khi tải lịch sử khám.</div>`;
    });
}

// Init
loadAppointments();
</script>
@endsection
