@extends('layouts.app')

@section('content')

{{-- Modal xem chi tiết đơn thuốc --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div id="detailBackdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div id="detailPanel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 opacity-0 translate-y-4">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold text-gray-800">Chi tiết đơn thuốc</h3>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-red-500 p-1 rounded-md transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6" id="detailBody">
                {{-- dynamically filled --}}
            </div>
        </div>
    </div>
</div>

{{-- Modal tạo đơn thuốc mới --}}
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div id="createBackdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div id="createPanel" class="relative bg-white rounded-xl shadow-2xl w-full max-w-3xl transform transition-all duration-300 opacity-0 translate-y-4">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold text-gray-800">Tạo đơn thuốc mới</h3>
                <button onclick="closeCreate()" class="text-gray-400 hover:text-red-500 p-1 rounded-md transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                {{-- Hồ sơ --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hồ sơ khám <span class="text-red-500">*</span></label>
                    <select id="medicalRecordId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>-- Chọn hồ sơ khám --</option>
                        @foreach($myRecords as $rec)
                            <option value="{{ $rec->id }}">
                                #{{ $rec->id }} — {{ $rec->patient->full_name ?? 'N/A' }}
                                @if($rec->appointment) | {{ $rec->appointment->date }} {{ substr($rec->appointment->time ?? '', 0, 5) }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nội dung / ghi chú --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ghi chú / Hướng dẫn dùng thuốc</label>
                    <textarea id="prescContent" rows="2" placeholder="Ví dụ: Uống sau bữa ăn, dùng trong 7 ngày..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 resize-none"></textarea>
                </div>

                {{-- Danh sách thuốc --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-semibold text-gray-700">Danh sách thuốc <span class="text-red-500">*</span></label>
                        <button onclick="addMedicineRow()" class="text-xs bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded-lg transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Thêm thuốc
                        </button>
                    </div>
                    <div id="medicineRows" class="space-y-2">
                        {{-- rows added by JS --}}
                    </div>
                </div>

                <p id="createMessage" class="text-sm font-medium text-center"></p>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <button onclick="closeCreate()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all text-sm">Hủy</button>
                <button onclick="submitPrescription()" class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-medium shadow-sm transition-colors text-sm flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Kê đơn thuốc
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý đơn thuốc</h2>
        <p class="text-gray-500 text-sm mt-1">Đơn thuốc do BS. {{ auth()->user()->full_name }} kê cho bệnh nhân</p>
    </div>
    <button onclick="openCreate()" class="mt-4 md:mt-0 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2 text-sm">
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Kê đơn thuốc mới
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="prescTable">
            {{-- JS loads here --}}
        </table>
    </div>
</div>

<script>
const token    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const medicines = @json($medicines);

// ===== DETAIL MODAL =====
function openDetail(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('detailBackdrop').classList.remove('opacity-0');
        const p = document.getElementById('detailPanel');
        p.classList.remove('opacity-0', 'translate-y-4');
        p.classList.add('opacity-100', 'translate-y-0');
    }, 10);

    fetch('/prescriptions/' + id)
    .then(r => r.json())
    .then(data => {
        const pr = data.data;
        const statusMap = { pending: 'Chưa phát', dispensed: 'Đã phát' };
        let itemRows = (pr.items || []).map(it => `
            <tr class="border-b border-gray-100">
                <td class="py-2 px-3">${it.medicine ? it.medicine.name : '?'}</td>
                <td class="py-2 px-3 text-center">${it.quantity} ${it.medicine ? it.medicine.unit : ''}</td>
                <td class="py-2 px-3">${it.dosage || '—'}</td>
                <td class="py-2 px-3">${it.usage || '—'}</td>
            </tr>`).join('');

        document.getElementById('detailBody').innerHTML = `
            <div class="mb-4 grid grid-cols-2 gap-3 text-sm">
                <div><span class="font-semibold text-gray-600">Mã đơn:</span> #${pr.id}</div>
                <div><span class="font-semibold text-gray-600">Trạng thái:</span> ${statusMap[pr.dispense_status] || pr.dispense_status}</div>
                <div class="col-span-2"><span class="font-semibold text-gray-600">Ghi chú:</span> ${pr.content || '—'}</div>
            </div>
            <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 text-gray-600"><tr>
                    <th class="py-2 px-3 text-left font-semibold">Thuốc</th>
                    <th class="py-2 px-3 text-center font-semibold">Số lượng</th>
                    <th class="py-2 px-3 text-left font-semibold">Liều dùng</th>
                    <th class="py-2 px-3 text-left font-semibold">Cách dùng</th>
                </tr></thead>
                <tbody class="text-gray-700">${itemRows || '<tr><td colspan="4" class="py-4 text-center text-gray-400">Không có thuốc</td></tr>'}</tbody>
            </table>`;
    })
    .catch(e => alert('Lỗi: ' + e));
}

function closeDetail() {
    document.getElementById('detailBackdrop').classList.add('opacity-0');
    const p = document.getElementById('detailPanel');
    p.classList.remove('opacity-100', 'translate-y-0');
    p.classList.add('opacity-0', 'translate-y-4');
    setTimeout(() => document.getElementById('detailModal').classList.add('hidden'), 300);
}

// ===== CREATE MODAL =====
function openCreate() {
    document.getElementById('medicalRecordId').value = '';
    document.getElementById('prescContent').value = '';
    document.getElementById('medicineRows').innerHTML = '';
    document.getElementById('createMessage').innerText = '';
    addMedicineRow();
    document.getElementById('createModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('createBackdrop').classList.remove('opacity-0');
        const p = document.getElementById('createPanel');
        p.classList.remove('opacity-0', 'translate-y-4');
        p.classList.add('opacity-100', 'translate-y-0');
    }, 10);
}

function closeCreate() {
    document.getElementById('createBackdrop').classList.add('opacity-0');
    const p = document.getElementById('createPanel');
    p.classList.remove('opacity-100', 'translate-y-0');
    p.classList.add('opacity-0', 'translate-y-4');
    setTimeout(() => document.getElementById('createModal').classList.add('hidden'), 300);
}

let rowIndex = 0;
function addMedicineRow() {
    const idx = rowIndex++;
    const opts = medicines.map(m =>
        `<option value="${m.id}" data-unit="${m.unit}">${m.name} (Tồn: ${m.stock} ${m.unit})</option>`
    ).join('');
    const row = `
    <div class="grid grid-cols-12 gap-2 items-center" id="mrow-${idx}">
        <div class="col-span-4">
            <select name="medicine_${idx}" class="med-select w-full px-2 py-1.5 border border-gray-300 rounded text-sm bg-white focus:border-blue-500">
                <option value="" disabled selected>-- Chọn thuốc --</option>${opts}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" min="1" placeholder="SL" class="qty-input w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:border-blue-500">
        </div>
        <div class="col-span-3">
            <input type="text" placeholder="Liều dùng" class="dos-input w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:border-blue-500">
        </div>
        <div class="col-span-2">
            <input type="text" placeholder="Cách dùng" class="usg-input w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:border-blue-500">
        </div>
        <div class="col-span-1 flex justify-center">
            <button onclick="document.getElementById('mrow-${idx}').remove()" class="text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>`;
    document.getElementById('medicineRows').insertAdjacentHTML('beforeend', row);
}

function submitPrescription() {
    const medicalRecordId = document.getElementById('medicalRecordId').value;
    const content = document.getElementById('prescContent').value;
    const msgEl = document.getElementById('createMessage');

    if (!medicalRecordId) { msgEl.className = 'text-red-500 text-sm font-medium text-center'; msgEl.innerText = 'Vui lòng chọn hồ sơ khám'; return; }

    const rows = document.querySelectorAll('#medicineRows > div');
    const items = [];
    let valid = true;
    rows.forEach(row => {
        const medId = row.querySelector('.med-select').value;
        const qty   = parseInt(row.querySelector('.qty-input').value);
        const dos   = row.querySelector('.dos-input').value;
        const usg   = row.querySelector('.usg-input').value;
        if (!medId || !qty) { valid = false; return; }
        items.push({ medicine_id: medId, quantity: qty, dosage: dos, usage: usg });
    });

    if (!valid || items.length === 0) { msgEl.className = 'text-red-500 text-sm font-medium text-center'; msgEl.innerText = 'Vui lòng điền đủ thông tin thuốc'; return; }

    fetch('/prescriptions', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            medical_record_id: medicalRecordId,
            dispensed_by: {{ auth()->id() }},
            dispense_status: 'pending',
            content,
            items
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-green-600 text-sm font-medium text-center';
            msgEl.innerText = 'Kê đơn thành công!';
            loadData();
            setTimeout(closeCreate, 800);
        } else {
            msgEl.className = 'text-red-500 text-sm font-medium text-center';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => alert('Lỗi: ' + e));
}

// ===== TABLE =====
function loadData() {
    fetch('{{ route("doctor.prescriptions.load") }}')
    .then(r => r.json())
    .then(data => {
        const statusMap = {
            pending:   '<span class="bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-yellow-100">Chưa phát</span>',
            dispensed: '<span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-100">Đã phát</span>',
        };

        let html = `
            <thead class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                <tr>
                    <th class="py-4 px-6 font-semibold w-20">Mã đơn</th>
                    <th class="py-4 px-6 font-semibold">Bệnh nhân</th>
                    <th class="py-4 px-6 font-semibold">Hồ sơ</th>
                    <th class="py-4 px-6 font-semibold text-center">Số loại thuốc</th>
                    <th class="py-4 px-6 font-semibold">Trạng thái phát</th>
                    <th class="py-4 px-6 font-semibold">Ngày tạo</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">`;

        if (data.prescriptions.length === 0) {
            html += `<tr><td colspan="7" class="py-10 text-center text-gray-400">Chưa có đơn thuốc nào.</td></tr>`;
        }

        data.prescriptions.forEach(p => {
            const patient = p.medical_record && p.medical_record.patient ? p.medical_record.patient.full_name : 'N/A';
            const recId   = p.medical_record_id;
            const itemCount = p.items ? p.items.length : 0;
            const date    = new Date(p.created_at).toLocaleDateString('vi-VN');
            const badge   = statusMap[p.dispense_status] || `<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">${p.dispense_status}</span>`;

            html += `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="py-3 px-6 font-medium text-gray-900">#${p.id}</td>
                <td class="py-3 px-6 font-medium">${patient}</td>
                <td class="py-3 px-6 text-gray-600">HS #${recId}</td>
                <td class="py-3 px-6 text-center">
                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">${itemCount} loại</span>
                </td>
                <td class="py-3 px-6">${badge}</td>
                <td class="py-3 px-6 text-gray-500 text-sm">${date}</td>
                <td class="py-3 px-6 text-right">
                    <button onclick="openDetail(${p.id})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Xem chi tiết">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody>';
        document.getElementById('prescTable').innerHTML = html;
    })
    .catch(e => alert('Lỗi: ' + e));
}

loadData();
</script>
@endsection
