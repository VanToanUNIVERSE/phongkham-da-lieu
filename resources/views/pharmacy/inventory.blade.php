@extends('layouts.app')

@section('pageTitle', 'Kho thuốc')

@section('content')

{{-- Import modal --}}
<div id="importModal" class="fixed inset-0 z-50 hidden">
    <div onclick="closeImport()" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-lg font-black text-gray-900 mb-1">Nhập kho thuốc</h3>
            <p class="text-sm text-gray-500 mb-5">Tồn kho sẽ được cập nhật ngay sau khi xác nhận.</p>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tên thuốc <span class="text-red-500">*</span></label>
                    <a href="{{ route('medicines.index') }}" class="text-xs font-bold text-violet-600 hover:text-violet-700 hover:underline flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tạo thuốc mới
                    </a>
                </div>
                <select id="importMedicineId" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-100 outline-none">
                    <option value="">-- Chọn thuốc --</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Số lượng <span class="text-red-500">*</span></label>
                <input type="number" id="importQty" min="1" placeholder="VD: 100"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-100 outline-none">
            </div>
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">Ghi chú</label>
                <input type="text" id="importNote" placeholder="VD: Nhập từ nhà cung cấp X"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-100 outline-none">
            </div>
            <div class="flex gap-3">
                <button onclick="closeImport()" class="flex-1 py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">Hủy</button>
                <button onclick="submitImport()" class="flex-1 py-3 bg-violet-600 hover:bg-violet-700 text-white font-black rounded-xl transition-all active:scale-95">Xác nhận nhập kho</button>
            </div>
            <p id="importMsg" class="text-center text-sm font-semibold mt-3"></p>
        </div>
    </div>
</div>

{{-- ===== MAIN PAGE ===== --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Kho thuốc</h2>
        <p class="text-gray-500 text-sm mt-0.5">Quản lý tồn kho và nhập thuốc vào kho.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchInput" placeholder="Tìm tên thuốc..." oninput="loadInventory()"
                class="border-none bg-transparent text-sm outline-none w-48">
        </div>
        <a href="{{ route('medicines.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-all active:scale-95 flex items-center gap-2 text-sm shadow-sm md:flex hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            Quản lý Kho gốc
        </a>
        <button onclick="openImport()" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-black rounded-xl transition-all active:scale-95 flex items-center gap-2 text-sm shadow-md shadow-violet-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nhập kho
        </button>
    </div>
</div>

{{-- Low stock warning --}}
<div id="lowStockBanner" class="hidden bg-red-50 border border-red-200 rounded-xl px-5 py-4 mb-5 text-sm text-red-700 font-semibold">
    ⚠️ Có một số thuốc sắp hết (tồn ≤ 10). Hãy nhập kho bổ sung sớm!
</div>

{{-- Inventory table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Tên thuốc</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Đơn vị</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Giá bán</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Tồn kho</th>
                <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Hạn dùng</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody id="inventoryBody" class="divide-y divide-gray-50">
            <tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>
        </tbody>
    </table>
</div>

{{-- Transaction history --}}
<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-black text-gray-800">Lịch sử nhập/xuất kho</h3>
        <button onclick="loadTransactions()" class="text-xs font-bold text-violet-500 hover:underline flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Làm mới
        </button>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Thuốc</th>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Loại GD</th>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Số lượng</th>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Ghi chú</th>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Người thực hiện</th>
                    <th class="px-5 py-3.5 font-bold text-gray-500 text-xs uppercase tracking-wider">Thời gian</th>
                </tr>
            </thead>
            <tbody id="txBody" class="divide-y divide-gray-50">
                <tr><td colspan="6" class="py-10 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let medicineList = [];

function loadInventory() {
    const search = document.getElementById('searchInput').value;
    const tbody = document.getElementById('inventoryBody');

    fetch(`{{ route('pharmacy.inventory.load') }}?search=${encodeURIComponent(search)}`)
    .then(r => r.json())
    .then(data => {
        medicineList = data.medicines;

        // Populate import select
        const sel = document.getElementById('importMedicineId');
        sel.innerHTML = '<option value="">-- Chọn thuốc --</option>' +
            medicineList.map(m => `<option value="${m.id}">${m.name} (${m.unit})</option>`).join('');

        let hasLow = false;

        if (medicineList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-gray-300 text-sm font-bold">Không tìm thấy thuốc</td></tr>';
            return;
        }

        tbody.innerHTML = medicineList.map(m => {
            const isLow = m.stock <= 10;
            if (isLow) hasLow = true;
            const stockColor = m.stock === 0 ? 'text-red-600 bg-red-50' : isLow ? 'text-orange-500 bg-orange-50' : 'text-emerald-600 bg-emerald-50';
            const expiry = m.expiry_date ? m.expiry_date : '—';
            const price = m.price ? parseInt(m.price).toLocaleString('vi-VN') + ' đ' : '—';

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4 font-semibold text-gray-900">${m.name}</td>
                <td class="px-5 py-4 text-gray-500">${m.unit}</td>
                <td class="px-5 py-4 text-gray-700">${price}</td>
                <td class="px-5 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-black ${stockColor}">${m.stock}</span>
                </td>
                <td class="px-5 py-4 text-gray-500 text-xs">${expiry}</td>
                <td class="px-5 py-4 text-right">
                    <button onclick="quickImport(${m.id})" class="text-violet-500 font-bold text-xs hover:underline">Nhập kho</button>
                </td>
            </tr>`;
        }).join('');

        document.getElementById('lowStockBanner').classList.toggle('hidden', !hasLow);
    })
    .catch(e => console.error(e));
}

function loadTransactions() {
    const tbody = document.getElementById('txBody');
    tbody.innerHTML = '<tr><td colspan="6" class="py-10 text-center text-gray-300 text-sm font-bold animate-pulse">Đang tải...</td></tr>';

    fetch(`{{ route('pharmacy.transactions') }}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        const txs = data.transactions;
        if (txs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-10 text-center text-gray-300 text-sm font-bold">Chưa có giao dịch nào</td></tr>';
            return;
        }

        tbody.innerHTML = txs.map(tx => {
            const isImport = tx.type === 'import';
            const typeBadge = isImport
                ? '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-700">⬆ Nhập kho</span>'
                : '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-600">⬇ Xuất kho</span>';
            const qtyColor = isImport ? 'text-blue-700 font-black' : 'text-red-600 font-black';
            const qtySign = isImport ? '+' : '−';
            const medName = tx.medicine ? tx.medicine.name : '—';
            const medUnit = tx.medicine ? tx.medicine.unit : '';
            const user = tx.user ? tx.user.full_name : '—';
            const note = tx.note || '—';
            const time = tx.created_at ? tx.created_at.substring(0, 16).replace('T', ' ') : '—';

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 font-semibold text-gray-900">${medName}</td>
                <td class="px-5 py-3.5 text-center">${typeBadge}</td>
                <td class="px-5 py-3.5 text-center ${qtyColor}">${qtySign}${tx.quantity} ${medUnit}</td>
                <td class="px-5 py-3.5 text-gray-500 text-xs max-w-[200px] truncate">${note}</td>
                <td class="px-5 py-3.5 text-gray-500 text-xs">${user}</td>
                <td class="px-5 py-3.5 text-gray-400 text-xs">${time}</td>
            </tr>`;
        }).join('');
    })
    .catch(e => console.error(e));
}

function openImport(medicineId = null) {
    document.getElementById('importMedicineId').value = medicineId || '';
    document.getElementById('importQty').value = '';
    document.getElementById('importNote').value = '';
    document.getElementById('importMsg').innerText = '';
    document.getElementById('importModal').classList.remove('hidden');
}

function quickImport(medicineId) {
    openImport(medicineId);
}

function closeImport() {
    document.getElementById('importModal').classList.add('hidden');
}

function submitImport() {
    const msgEl = document.getElementById('importMsg');
    const medicineId = document.getElementById('importMedicineId').value;
    const qty = document.getElementById('importQty').value;
    const note = document.getElementById('importNote').value;

    if (!medicineId || !qty || parseInt(qty) < 1) {
        msgEl.className = 'text-red-500 text-sm font-semibold mt-3 text-center';
        msgEl.innerText = '⚠ Vui lòng chọn thuốc và nhập số lượng hợp lệ';
        return;
    }

    fetch(`{{ route('pharmacy.inventory.import') }}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ medicine_id: medicineId, quantity: qty, note: note })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            msgEl.className = 'text-emerald-600 text-sm font-semibold mt-3 text-center';
            msgEl.innerText = '✓ ' + data.message;
            setTimeout(() => { closeImport(); loadInventory(); loadTransactions(); }, 800);
        } else {
            msgEl.className = 'text-red-500 text-sm font-semibold mt-3 text-center';
            msgEl.innerText = data.message || 'Có lỗi xảy ra';
        }
    })
    .catch(e => console.error(e));
}

loadInventory();
loadTransactions();
</script>
@endsection
