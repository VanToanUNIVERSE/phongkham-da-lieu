@extends('layouts.app')

@section('pageTitle', 'Quản lý hồ sơ bệnh án')

@section('content')

{{-- Modal Tạo / Sửa Hồ sơ --}}
<div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div id="modal-panel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 opacity-0 translate-y-4">
            {{-- Header --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center rounded-t-xl">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tạo hồ sơ khám bệnh</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 p-1 rounded-md transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">
                <input type="hidden" id="recordId">

                {{-- Lịch hẹn --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lịch hẹn <span class="text-red-500">*</span></label>
                    <select id="appointmentId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>-- Chọn lịch hẹn --</option>
                        @foreach($appointments as $apt)
                            <option value="{{ $apt->id }}" data-patient="{{ $apt->patient_id }}">
                                #{{ $apt->id }} — {{ $apt->patient->full_name ?? 'N/A' }} | {{ $apt->date }} {{ substr($apt->time, 0, 5) }}
                            </option>
                        @endforeach
                    </select>
                    <p id="err_appointment" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Bệnh nhân (auto-fill) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bệnh nhân <span class="text-red-500">*</span></label>
                    <select id="patientId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>-- Chọn bệnh nhân --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->phone ?? 'Trống' }})</option>
                        @endforeach
                    </select>
                    <p id="err_patient" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Chẩn đoán --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Chẩn đoán <span class="text-red-500">*</span></label>
                    <textarea id="diagnosis" rows="3" placeholder="Nhập chẩn đoán bệnh..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 resize-none"></textarea>
                    <p id="err_diagnosis" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                {{-- Kết quả khám --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kết quả khám <span class="text-red-500">*</span></label>
                    <textarea id="examinationResult" rows="3" placeholder="Nhập kết quả khám lâm sàng..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 resize-none"></textarea>
                    <p id="err_examination" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <p id="formMessage" class="text-green-600 text-sm font-medium text-center"></p>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all text-sm">Hủy bỏ</button>
                <button onclick="saveRecord()" class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-medium shadow-sm transition-colors text-sm flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lưu hồ sơ
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Hồ sơ khám bệnh</h2>
        <p class="text-gray-500 text-sm mt-1">Quản lý hồ sơ bệnh án do BS. {{ auth()->user()->full_name }} tạo</p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center gap-3">
        <div class="relative group">
            <input type="text" id="recordSearch" placeholder="Tìm tên, chẩn đoán..." onkeyup="debounceSearch()"
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm focus:ring-2 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none w-64">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <button onclick="openCreate()" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2 text-sm">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tạo hồ sơ mới
        </button>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="recordTable">
            {{-- JS loads here --}}
        </table>
    </div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// --- Modal helpers ---
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('modal-backdrop').classList.remove('opacity-0');
        const p = document.getElementById('modal-panel');
        p.classList.remove('opacity-0', 'translate-y-4');
        p.classList.add('opacity-100', 'translate-y-0');
    }, 10);
}

function closeModal() {
    document.getElementById('modal-backdrop').classList.add('opacity-0');
    const p = document.getElementById('modal-panel');
    p.classList.remove('opacity-100', 'translate-y-0');
    p.classList.add('opacity-0', 'translate-y-4');
    setTimeout(() => document.getElementById('modal').classList.add('hidden'), 300);
}

function resetForm() {
    document.getElementById('recordId').value = '';
    document.getElementById('appointmentId').value = '';
    document.getElementById('patientId').value = '';
    document.getElementById('diagnosis').value = '';
    document.getElementById('examinationResult').value = '';
    document.getElementById('formMessage').innerText = '';
    document.querySelectorAll('[id^="err_"]').forEach(el => { el.innerText = ''; el.classList.add('hidden'); });
}

function openCreate() {
    resetForm();
    document.getElementById('modalTitle').innerText = 'Tạo hồ sơ khám bệnh';
    openModal();
}

// Auto-fill patient when appointment is selected
document.getElementById('appointmentId').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const pid = opt.getAttribute('data-patient');
    if (pid) document.getElementById('patientId').value = pid;
});

// --- CRUD ---
let searchTimeout = null;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(loadData, 500);
}

function loadData() {
    const search = document.getElementById('recordSearch').value;
    fetch(`{{ route("doctor.medical_records.load") }}?search=${search}`)
    .then(r => r.json())
    .then(data => {
        let html = `
            <thead class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                <tr>
                    <th class="py-4 px-6 font-semibold w-20">Mã HS</th>
                    <th class="py-4 px-6 font-semibold">Bệnh nhân</th>
                    <th class="py-4 px-6 font-semibold">Lịch hẹn</th>
                    <th class="py-4 px-6 font-semibold">Chẩn đoán</th>
                    <th class="py-4 px-6 font-semibold">Ngày tạo</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
        `;

        if (data.medical_records.length === 0) {
            html += `<tr><td colspan="6" class="py-10 text-center text-gray-400">Chưa có hồ sơ nào.</td></tr>`;
        }

        data.medical_records.forEach(r => {
            const date = new Date(r.created_at).toLocaleDateString('vi-VN');
            const aptLabel = r.appointment ? `#${r.appointment.id} (${r.appointment.date})` : 'N/A';
            const diag = r.diagnosis.length > 60 ? r.diagnosis.substring(0, 60) + '...' : r.diagnosis;
            html += `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="py-3 px-6 font-medium text-gray-900">#${r.id}</td>
                <td class="py-3 px-6 font-medium">${r.patient ? r.patient.full_name : 'N/A'}</td>
                <td class="py-3 px-6 text-gray-600">${aptLabel}</td>
                <td class="py-3 px-6 text-gray-600 text-sm">${diag}</td>
                <td class="py-3 px-6 text-gray-500 text-sm">${date}</td>
                <td class="py-3 px-6 text-right">
                    <button onclick="editRecord(${r.id})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Sửa">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody>';
        document.getElementById('recordTable').innerHTML = html;
    })
    .catch(e => alert('Lỗi: ' + e));
}

function editRecord(id) {
    fetch('/medical_records/' + id)
    .then(r => r.json())
    .then(data => {
        const rec = data.medical_record;
        document.getElementById('recordId').value = rec.id;
        document.getElementById('appointmentId').value = rec.appointment_id;
        document.getElementById('patientId').value = rec.patient_id;
        document.getElementById('diagnosis').value = rec.diagnosis;
        document.getElementById('examinationResult').value = rec.examination_result;
        document.getElementById('modalTitle').innerText = 'Sửa hồ sơ khám bệnh';
        document.getElementById('formMessage').innerText = '';
        openModal();
    })
    .catch(e => alert('Lỗi: ' + e));
}

function saveRecord() {
    const rid = document.getElementById('recordId').value;
    const isEdit = !!rid;
    const url    = isEdit ? `/doctor/medical-records/${rid}` : '{{ route("doctor.medical_records.store") }}';
    const method = isEdit ? 'PUT' : 'POST';

    const body = {
        appointment_id:     document.getElementById('appointmentId').value,
        patient_id:         document.getElementById('patientId').value,
        diagnosis:          document.getElementById('diagnosis').value,
        examination_result: document.getElementById('examinationResult').value,
    };

    fetch(url, {
        method,
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('formMessage').innerText = data.message;
            document.getElementById('formMessage').className = 'text-green-600 text-sm font-medium text-center';
            loadData();
            setTimeout(closeModal, 800);
        } else {
            const msg = data.message || 'Có lỗi xảy ra';
            document.getElementById('formMessage').innerText = msg;
            document.getElementById('formMessage').className = 'text-red-500 text-sm font-medium text-center';
            if (data.errors) {
                for (const [field, msgs] of Object.entries(data.errors)) {
                    const el = document.getElementById('err_' + field.replace('_id','').replace('examination_result','examination'));
                    if (el) { el.innerText = msgs[0]; el.classList.remove('hidden'); }
                }
            }
        }
    })
    .catch(e => alert('Lỗi: ' + e));
}

loadData();
</script>
@endsection
