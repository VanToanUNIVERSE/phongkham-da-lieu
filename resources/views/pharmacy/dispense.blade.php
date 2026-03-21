@extends('layouts.app')

@section('pageTitle', 'Phát thuốc')

@section('content')

{{-- ===== SLIDE PANEL (Prescription Detail) ===== --}}
<div id="rxPanel" class="fixed inset-0 z-50 hidden">
    <div onclick="closePanel()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div id="rxSlide" class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out">

        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-violet-600 to-purple-700 text-white flex-shrink-0">
            <div>
                <h2 class="text-lg font-bold" id="panelPatient">—</h2>
                <p class="text-violet-100 text-sm" id="panelSubtitle">—</p>
            </div>
            <button onclick="closePanel()" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-5">
            {{-- Doctor info --}}
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm space-y-1.5">
                <p class="font-bold text-gray-600 uppercase tracking-widest text-xs mb-2">Thông tin đơn thuốc</p>
                <div class="flex justify-between"><span class="text-gray-500">Bác sĩ kê đơn</span><span id="panelDoctor" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Thời gian kê</span><span id="panelDate" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-500">Trạng thái</span><span id="panelStatus" class="text-xs font-black px-3 py-1 rounded-full">—</span></div>
            </div>

            {{-- Medicine list --}}
            <div>
                <p class="font-bold text-gray-700 mb-3 uppercase tracking-widest text-xs">Danh sách thuốc phát</p>
                <div id="rxItemsList" class="space-y-2">
                    <p class="text-gray-300 text-sm text-center py-4">Đang tải...</p>
                </div>
            </div>

            {{-- Dispense button --}}
            <div id="dispenseBlock">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 text-sm text-amber-800">
                    ⚠️ Hãy kiểm tra tất cả thuốc và số lượng trước khi xác nhận. Thao tác này sẽ <strong>trừ kho tự động</strong>.
                </div>
                <button onclick="confirmDispense()" class="w-full py-4 bg-violet-600 hover:bg-violet-700 text-white font-black rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    XÁC NHẬN ĐÃ PHÁT THUỐC
                </button>
                <p id="dispenseMsg" class="text-center text-sm font-semibold mt-2"></p>
            </div>

            <div id="dispatchedBlock" class="hidden text-center py-6 bg-emerald-50 text-emerald-700 rounded-xl">
                <div class="text-4xl mb-2">✅</div>
                <p class="font-black text-lg">Đã phát thuốc thành công</p>
                <p class="text-sm text-emerald-600 mt-1" id="dispatchedBy">—</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN PAGE ===== --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Phát thuốc</h2>
        <p class="text-gray-500 text-sm mt-0.5">Chỉ hiển thị đơn thuốc của bệnh nhân đã thanh toán.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex bg-gray-100 rounded-xl p-1 gap-1">
            <button onclick="setFilter('pending')" id="filterPending"
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all bg-violet-600 text-white shadow-sm">
                ⏳ Chờ phát
            </button>
            <button onclick="setFilter('dispensed')" id="filterDispensed"
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-700">
                ✓ Đã phát
            </button>
        </div>
        <input type="date" id="filterDate" value="{{ date('Y-m-d') }}" onchange="loadList()"
               class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-violet-300 outline-none">
    </div>
</div>

{{-- Stats bar --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-violet-50 border border-violet-100 rounded-xl px-5 py-3 flex items-center justify-between">
        <span class="text-xs font-bold text-violet-500 uppercase tracking-widest">Chờ phát hôm nay</span>
        <span id="statPending" class="text-2xl font-black text-violet-600">—</span>
    </div>
    <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-5 py-3 flex items-center justify-between">
        <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Đã phát hôm nay</span>
        <span id="statDone" class="text-2xl font-black text-emerald-600">—</span>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Bệnh nhân</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Bác sĩ kê</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Số loại thuốc</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Trạng thái</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Thời gian kê</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody id="rxTableBody" class="divide-y divide-gray-50">
            <tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>
        </tbody>
    </table>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentFilter = 'pending';
let currentRxId = null;
let rxCache = {};

function setFilter(status) {
    currentFilter = status;
    document.getElementById('filterPending').className = `px-4 py-2 rounded-lg text-sm font-bold transition-all ${status === 'pending' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'}`;
    document.getElementById('filterDispensed').className = `px-4 py-2 rounded-lg text-sm font-bold transition-all ${status === 'dispensed' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'}`;
    loadList();
}

function loadList() {
    const date = document.getElementById('filterDate').value;
    const tbody = document.getElementById('rxTableBody');
    tbody.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>';

    // Load both counts
    fetch(`{{ route('pharmacy.dispense.load') }}?status=pending&date=${date}`).then(r=>r.json()).then(d=>{
        document.getElementById('statPending').innerText = d.prescriptions.length;
    });
    fetch(`{{ route('pharmacy.dispense.load') }}?status=dispensed&date=${date}`).then(r=>r.json()).then(d=>{
        document.getElementById('statDone').innerText = d.prescriptions.length;
    });

    fetch(`{{ route('pharmacy.dispense.load') }}?status=${currentFilter}&date=${date}`)
    .then(r => r.json())
    .then(data => {
        const rxList = data.prescriptions;
        if (rxList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold">${currentFilter === 'pending' ? 'Không có đơn thuốc chờ phát' : 'Chưa phát đơn nào hôm nay'}</td></tr>`;
            return;
        }

        tbody.innerHTML = rxList.map(rx => {
            rxCache[rx.id] = rx;
            const pt = rx.medical_record?.patient?.full_name || 'N/A';
            const dr = rx.medical_record?.appointment?.doctor?.user?.full_name || 'N/A';
            const itemCount = rx.items?.length || 0;
            const isPending = !rx.dispense_status || rx.dispense_status === 'pending';
            const badge = isPending
                ? '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-violet-100 text-violet-700">⏳ Chờ phát</span>'
                : '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700">✓ Đã phát</span>';
            const time = rx.created_at ? rx.created_at.substring(0, 16).replace('T', ' ') : '—';

            return `<tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="openPanel(${rx.id})">
                <td class="px-5 py-4 font-semibold text-gray-900">${pt}</td>
                <td class="px-5 py-4 text-gray-500 text-xs">${dr}</td>
                <td class="px-5 py-4 text-gray-700 font-bold">${itemCount} loại</td>
                <td class="px-5 py-4 text-center">${badge}</td>
                <td class="px-5 py-4 text-gray-500 text-xs">${time}</td>
                <td class="px-5 py-4 text-right">
                    <span class="text-violet-500 font-bold text-xs">${isPending ? 'Phát →' : 'Xem →'}</span>
                </td>
            </tr>`;
        }).join('');
    })
    .catch(e => console.error(e));
}

function openPanel(rxId) {
    currentRxId = rxId;
    const rx = rxCache[rxId];
    if (!rx) return;

    const pt = rx.medical_record?.patient?.full_name || '—';
    const dr = rx.medical_record?.appointment?.doctor?.user?.full_name || '—';
    const isPending = !rx.dispense_status || rx.dispense_status === 'pending';

    document.getElementById('panelPatient').innerText = pt;
    document.getElementById('panelSubtitle').innerText = `Đơn thuốc #${rxId}`;
    document.getElementById('panelDoctor').innerText = dr;
    document.getElementById('panelDate').innerText = rx.created_at ? rx.created_at.substring(0, 16).replace('T', ' ') : '—';

    const statusEl = document.getElementById('panelStatus');
    statusEl.innerText = isPending ? '⏳ Chờ phát' : '✅ Đã phát';
    statusEl.className = `text-xs font-black px-3 py-1 rounded-full ${isPending ? 'bg-violet-100 text-violet-700' : 'bg-emerald-100 text-emerald-700'}`;

    // Items
    const items = rx.items || [];
    document.getElementById('rxItemsList').innerHTML = items.length === 0
        ? '<p class="text-gray-400 text-sm text-center">Không có thuốc trong đơn</p>'
        : items.map(item => {
            const med = item.medicine || {};
            const stock = med.stock || 0;
            const stockColor = stock < item.quantity ? 'text-red-500' : 'text-emerald-600';
            return `<div class="bg-white border border-gray-100 rounded-xl p-4 flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-900 text-sm">${med.name || 'N/A'}</p>
                    <p class="text-xs text-gray-500 mt-0.5">${item.dosage || ''} — ${item.usage || ''}</p>
                </div>
                <div class="text-right">
                    <p class="font-black text-gray-900">${item.quantity} ${med.unit || ''}</p>
                    <p class="text-xs ${stockColor} font-semibold mt-0.5">Tồn: ${stock} ${med.unit || ''}</p>
                </div>
            </div>`;
        }).join('');

    // Show/hide dispense button
    document.getElementById('dispenseBlock').classList.toggle('hidden', !isPending);
    document.getElementById('dispatchedBlock').classList.toggle('hidden', isPending);
    if (!isPending && rx.user) {
        document.getElementById('dispatchedBy').innerText = `Phát bởi: ${rx.user.full_name || '—'}`;
    }
    document.getElementById('dispenseMsg').innerText = '';

    // Open panel
    document.getElementById('rxPanel').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('rxSlide').classList.remove('translate-x-full');
        document.getElementById('rxSlide').classList.add('translate-x-0');
    }, 10);
}

function confirmDispense() {
    const msgEl = document.getElementById('dispenseMsg');
    msgEl.innerText = 'Đang xử lý...';
    msgEl.className = 'text-gray-400 text-sm font-semibold mt-2 text-center';

    fetch(`{{ url('pharmacy/dispense') }}/${currentRxId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-emerald-600 text-sm font-semibold mt-2 text-center';
            msgEl.innerText = '✓ ' + data.message;
            setTimeout(() => { closePanel(); loadList(); }, 800);
        } else {
            msgEl.className = 'text-red-500 text-sm font-semibold mt-2 text-center';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => { msgEl.className = 'text-red-500 text-sm font-semibold mt-2 text-center'; msgEl.innerText = 'Lỗi kết nối'; });
}

function closePanel() {
    const slide = document.getElementById('rxSlide');
    slide.classList.remove('translate-x-0');
    slide.classList.add('translate-x-full');
    setTimeout(() => document.getElementById('rxPanel').classList.add('hidden'), 300);
}

loadList();
</script>
@endsection
