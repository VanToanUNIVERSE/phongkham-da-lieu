@extends('layouts.app')

@section('content')

{{-- ===== SLIDE PANEL (Invoice Detail / Create) ===== --}}
<div id="examPanel" class="fixed inset-0 z-50 hidden">
    <div onclick="closePanel()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div id="examSlide" class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-700 text-white flex-shrink-0">
            <div>
                <h2 class="text-lg font-bold" id="panelPatientName">—</h2>
                <p class="text-emerald-100 text-sm" id="panelSubtitle">—</p>
            </div>
            <button onclick="closePanel()" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-5">

            {{-- Patient Info --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm space-y-2">
                <p class="font-bold text-blue-700 mb-2 uppercase tracking-widest text-xs">Thông tin bệnh nhân</p>
                <div class="flex justify-between"><span class="text-blue-500">Số điện thoại</span><span id="ptPhone" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-blue-500">Giới tính</span><span id="ptGender" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-blue-500">Năm sinh</span><span id="ptBirth" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-blue-500">Địa chỉ</span><span id="ptAddress" class="font-semibold text-gray-800 text-right">—</span></div>
            </div>

            {{-- Appointment Info --}}
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm space-y-1.5">
                <p class="font-bold text-gray-700 mb-2 uppercase tracking-widest text-xs">Chi tiết ca khám</p>
                <div class="flex justify-between"><span class="text-gray-500">Bác sĩ</span><span id="aptDoctor" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Giờ khám</span><span id="aptTime" class="font-semibold text-gray-800">—</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Ngày</span><span id="aptDate" class="font-semibold text-gray-800">—</span></div>
            </div>

            {{-- === BLOCK A: Invoice already exists === --}}
            <div id="blockExisting" class="hidden space-y-4">
                <div class="flex justify-between items-center">
                    <p class="font-bold text-gray-800">Chi tiết hóa đơn</p>
                    <span id="invStatusBadge" class="text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-widest"></span>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-50">
                    <div class="flex justify-between px-4 py-3 text-sm"><span class="text-gray-500">Phí khám lâm sàng</span><span id="invExamFee" class="font-bold text-gray-800">0 VNĐ</span></div>
                    <div class="flex justify-between px-4 py-3 text-sm"><span class="text-gray-500">Tiền thuốc (theo đơn)</span><span id="invMedFee" class="font-bold text-gray-800">0 VNĐ</span></div>
                    <div class="flex justify-between px-4 py-4 font-bold"><span class="text-gray-900 text-base">Tổng thanh toán</span><span id="invTotal" class="text-emerald-600 text-xl">0 VNĐ</span></div>
                </div>

                {{-- Confirm payment (only if pending) --}}
                <div id="confirmPayBlock" class="hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Phương thức thanh toán</p>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="payMethod" value="cash" checked class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all">Tiền mặt</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payMethod" value="bank_transfer" class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all">Chuyển khoản</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payMethod" value="card" class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all">Thẻ/Ví</div>
                        </label>
                    </div>
                    <button onclick="confirmPayment()" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        XÁC NHẬN THANH TOÁN
                    </button>
                </div>

                <div id="paidBlock" class="hidden text-center py-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold">
                    ✓ Hóa đơn đã được thanh toán
                </div>
            </div>

            {{-- === BLOCK B: No invoice yet, but medical record exists — create form === --}}
            <div id="blockCreate" class="hidden space-y-4">
                <p class="font-bold text-gray-800">Lập hóa đơn thu phí</p>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Phí khám lâm sàng (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" id="examinationFee" min="0" placeholder="VD: 150000"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Tiền thuốc (tự tính từ đơn)</label>
                    <input type="text" id="medicineFeeDisplay" readonly
                        class="w-full px-4 py-3 border border-gray-100 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-widest">Phương thức thanh toán</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="createPayMethod" value="cash" checked class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">Tiền mặt</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="createPayMethod" value="bank_transfer" class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">Chuyển khoản</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="createPayMethod" value="card" class="hidden peer">
                            <div class="text-center py-2.5 border border-gray-200 rounded-xl text-xs font-bold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">Thẻ/Ví</div>
                        </label>
                    </div>
                </div>
                <button onclick="createInvoice()" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    TẠO HÓA ĐƠN
                </button>
                <p id="invoiceMsg" class="text-center text-sm font-semibold"></p>
            </div>

            {{-- === BLOCK C: No medical record yet === --}}
            <div id="blockNoRecord" class="hidden text-center py-10">
                <div class="w-16 h-16 mx-auto mb-4 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="font-bold text-gray-700 mb-1">Bác sĩ chưa tạo hồ sơ khám</p>
                <p class="text-sm text-gray-400">Vui lòng chờ bác sĩ hoàn tất ghi nhận trước khi lập hóa đơn.</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN PAGE ===== --}}
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Quản lý hóa đơn</h2>
        <p class="text-gray-500 text-sm mt-0.5">Ca khám hoàn thành — lập hóa đơn và thu phí tại đây.</p>
    </div>
    <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <input type="date" id="filterDate" value="{{ date('Y-m-d') }}" onchange="loadInvoices()"
               class="border-none bg-transparent text-sm font-semibold focus:ring-0 outline-none text-gray-700">
    </div>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-red-50 border border-red-100 rounded-xl px-5 py-4">
        <p class="text-xs font-bold text-red-400 uppercase tracking-widest mb-1">Chờ thu phí</p>
        <p id="countPending" class="text-3xl font-black text-red-600">—</p>
    </div>
    <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-5 py-4">
        <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Đã thu phí</p>
        <p id="countPaid" class="text-3xl font-black text-emerald-600">—</p>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Bệnh nhân</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Bác sĩ</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Giờ khám</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Hóa đơn</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Thanh toán</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody id="invTableBody" class="divide-y divide-gray-50">
            <tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>
        </tbody>
    </table>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentAptId = null;
let currentRecordId = null;
let currentInvoiceId = null;
let aptCache = {}; // cache appointment data by id

function fmt(v) {
    return v != null ? parseInt(v).toLocaleString('vi-VN') + ' đ' : '0 đ';
}

function loadInvoices() {
    const date = document.getElementById('filterDate').value;
    const tbody = document.getElementById('invTableBody');
    tbody.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>';

    fetch(`{{ route('reception.invoices.load') }}?date=${date}`)
    .then(r => r.json())
    .then(data => {
        const apts = data.appointments;

        let pending = 0, paid = 0;
        apts.forEach(a => {
            const inv = a.medical_record && a.medical_record.invoice ? a.medical_record.invoice : null;
            if (!inv || inv.status !== 'paid') pending++;
            else paid++;
        });
        document.getElementById('countPending').innerText = pending;
        document.getElementById('countPaid').innerText = paid;

        if (apts.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold">Không có ca khám hoàn thành trong ngày này</td></tr>';
            return;
        }

        tbody.innerHTML = apts.map(a => {
            // Cache each appointment for use in openPanel
            aptCache[a.id] = a;

            const pt = a.patient ? a.patient.full_name : 'N/A';
            const dr = a.doctor && a.doctor.user ? a.doctor.user.full_name : 'N/A';
            const record = a.medical_record || null;
            const invoice = record && record.invoice ? record.invoice : null;
            const isPaid = invoice && invoice.status === 'paid';
            const hasInvoice = !!invoice;

            const invBadge = hasInvoice
                ? `<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase ${isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700'}">${isPaid ? '✓ Đã thu' : '⏳ Chờ thu'}</span>`
                : `<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-gray-100 text-gray-400">Chưa lập</span>`;

            const totalBadge = hasInvoice
                ? `<span class="font-bold text-gray-800">${fmt(invoice.total_amount)}</span>`
                : `<span class="text-gray-300">—</span>`;

            return `<tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="openPanel(${a.id})">
                <td class="px-5 py-4 font-semibold text-gray-900">${pt}</td>
                <td class="px-5 py-4 text-gray-500 text-xs">${dr}</td>
                <td class="px-5 py-4 text-gray-500">${a.time.substring(0,5)}</td>
                <td class="px-5 py-4 text-center">${invBadge}</td>
                <td class="px-5 py-4 text-center">${totalBadge}</td>
                <td class="px-5 py-4 text-right">
                    <span class="text-blue-500 font-bold text-xs hover:underline">${hasInvoice && !isPaid ? 'Thu phí →' : hasInvoice ? 'Xem →' : 'Lập HĐ →'}</span>
                </td>
            </tr>`;
        }).join('');
    })
    .catch(e => console.error(e));
}

function openPanel(aptId) {
    currentAptId = aptId;

    fetch(`/reception/appointments/${aptId}/invoice`)
    .then(r => r.json())
    .then(data => {
        const record = data.record;
        const invoice = data.invoice;
        const medFee = data.medicine_fee || 0;

        currentRecordId = record ? record.id : null;
        currentInvoiceId = invoice ? invoice.id : null;

        // Header — from cache first, then supplement with record data
        const cached = aptCache[aptId] || {};
        const cachedPt = cached.patient ? cached.patient.full_name : '—';
        const cachedDr = cached.doctor && cached.doctor.user ? cached.doctor.user.full_name : '—';
        const cachedTime = cached.time ? cached.time.substring(0, 5) : '—';
        const cachedDate = cached.date || '—';

        document.getElementById('panelPatientName').innerText = cachedPt;
        document.getElementById('panelSubtitle').innerText = `Lịch #${aptId} — ${cachedDate}`;
        
        // Populate Patient Details
        const patient = record ? record.patient : cached.patient;
        if (patient) {
            document.getElementById('ptPhone').innerText   = patient.phone || '—';
            document.getElementById('ptGender').innerText  = patient.gender == 1 ? 'Nam' : (patient.gender == 0 ? 'Nữ' : '—');
            document.getElementById('ptBirth').innerText   = patient.birth_year || '—';
            document.getElementById('ptAddress').innerText = patient.address || '—';
        }

        document.getElementById('aptDoctor').innerText = record && record.doctor ? record.doctor.user.full_name : cachedDr;
        document.getElementById('aptTime').innerText = cachedTime;
        document.getElementById('aptDate').innerText = cachedDate;

        // Hide all blocks first
        ['blockExisting', 'blockCreate', 'blockNoRecord'].forEach(id => document.getElementById(id).classList.add('hidden'));

        if (invoice) {
            // Show existing invoice
            document.getElementById('blockExisting').classList.remove('hidden');
            document.getElementById('invExamFee').innerText = fmt(invoice.examination_fee);
            document.getElementById('invMedFee').innerText = fmt(invoice.medicine_fee);
            document.getElementById('invTotal').innerText = fmt(invoice.total_amount);

            const isPaid = invoice.status === 'paid';
            const badge = document.getElementById('invStatusBadge');
            badge.innerText = isPaid ? '✓ Đã thanh toán' : '⏳ Chờ thanh toán';
            badge.className = `text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-widest ${isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600'}`;

            document.getElementById('confirmPayBlock').classList.toggle('hidden', isPaid);
            document.getElementById('paidBlock').classList.toggle('hidden', !isPaid);
        } else if (record) {
            // No invoice yet, but has medical record — show create form
            document.getElementById('blockCreate').classList.remove('hidden');
            document.getElementById('medicineFeeDisplay').value = medFee > 0 ? parseInt(medFee).toLocaleString('vi-VN') + ' đ' : '0 đ';
            document.getElementById('examinationFee').value = '';
            document.getElementById('invoiceMsg').innerText = '';
        } else {
            // No medical record at all
            document.getElementById('blockNoRecord').classList.remove('hidden');
        }

        // Open panel
        document.getElementById('examPanel').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('examSlide').classList.remove('translate-x-full');
            document.getElementById('examSlide').classList.add('translate-x-0');
        }, 10);
    })
    .catch(e => console.error(e));
}

function createInvoice() {
    const msgEl = document.getElementById('invoiceMsg');
    const fee = document.getElementById('examinationFee').value;
    if (!fee || parseInt(fee) <= 0) {
        msgEl.className = 'text-red-500 text-sm font-semibold';
        msgEl.innerText = '⚠ Vui lòng nhập phí khám hợp lệ';
        return;
    }
    const method = document.querySelector('input[name="createPayMethod"]:checked')?.value || 'cash';

    fetch('/invoices', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            medical_record_id: currentRecordId,
            examination_fee: fee,
            status: 'pending',
            payment_method: method
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-emerald-600 text-sm font-semibold';
            msgEl.innerText = '✓ Đã tạo hóa đơn thành công!';
            setTimeout(() => { openPanel(currentAptId); loadInvoices(); }, 600);
        } else {
            msgEl.className = 'text-red-500 text-sm font-semibold';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => console.error(e));
}

function confirmPayment() {
    const method = document.querySelector('input[name="payMethod"]:checked')?.value || 'cash';
    fetch(`/invoices/${currentInvoiceId}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ status: 'paid', payment_method: method })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            openPanel(currentAptId);
            loadInvoices();
        }
    })
    .catch(e => console.error(e));
}

function closePanel() {
    const slide = document.getElementById('examSlide');
    slide.classList.remove('translate-x-0');
    slide.classList.add('translate-x-full');
    setTimeout(() => document.getElementById('examPanel').classList.add('hidden'), 300);
}

loadInvoices();
</script>
@endsection
