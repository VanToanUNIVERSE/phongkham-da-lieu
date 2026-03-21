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


{{-- Header --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Lịch sử đơn thuốc đã kê</h2>
        <p class="text-gray-500 text-sm mt-1">Danh sách các đơn thuốc do BS. {{ auth()->user()->full_name }} đã kê dựa trên kết quả khám bệnh</p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center gap-3">
        <div class="relative group">
            <input type="text" id="prescSearch" placeholder="Tìm tên bệnh nhân..." onkeyup="debounceSearch()"
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm focus:ring-2 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none w-64">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>
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

// ===== TABLE =====
let searchTimeout = null;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(loadData, 500);
}

function loadData() {
    const search = document.getElementById('prescSearch').value;
    fetch(`{{ route("doctor.prescriptions.load") }}?search=${search}`)
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
