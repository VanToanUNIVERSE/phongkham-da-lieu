@extends('layouts.app')

@section('pageTitle', 'Lịch sử xuất nhập kho')

@section('content')
<div class="animate-in fade-in duration-700">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Lịch sử xuất nhập kho</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium italic">Theo dõi biến động tồn kho thuốc tại phòng khám</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('medicines.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm font-bold transition-all active:scale-95 flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none"tếtroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                Quản lý Kho thuốc
            </a>
        </div>
    </div>

    {{-- Filter Bar (Optional but nice) --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="relative group">
            <input type="text" id="transactionSearch" onkeyup="filterTransactions()" placeholder="Tìm thuốc, ghi chú..." class="pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none w-64">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        
        <select id="typeFilter" onchange="filterTransactions()" class="px-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10 transition-all outline-none cursor-pointer">
            <option value="all">Tất cả loại</option>
            <option value="import">Nhập kho</option>
            <option value="export">Xuất kho</option>
        </select>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="transactionTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-sm text-slate-500 uppercase tracking-widest font-black">
                        <th class="py-5 px-6 font-black w-20">ID</th>
                        <th class="py-5 px-6 font-black">Thời gian</th>
                        <th class="py-5 px-6 font-black">Thuốc</th>
                        <th class="py-5 px-6 font-black">Loại</th>
                        <th class="py-5 px-6 font-black text-center">Số lượng</th>
                        <th class="py-5 px-6 font-black">Người thực hiện</th>
                        <th class="py-5 px-6 font-black">Ghi chú</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-700">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/50 transition-colors transaction-row" data-type="{{ $tx->type }}" data-content="{{ strtolower($tx->medicine->name . ' ' . $tx->note) }}">
                            <td class="py-4 px-6 font-bold text-slate-400 text-sm">#{{ $tx->id }}</td>
                            <td class="py-4 px-6 text-sm font-medium">
                                <span class="block text-slate-900">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y') }}</span>
                                <span class="text-xs text-slate-400 italic">{{ \Carbon\Carbon::parse($tx->created_at)->format('H:i:s') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                        {{ substr($tx->medicine->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $tx->medicine->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($tx->type == 'import')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-wider">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        Nhập kho
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 text-xs font-black uppercase tracking-wider">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        Xuất kho
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-black text-lg {{ $tx->type == 'import' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type == 'import' ? '+' : '-' }}{{ $tx->quantity }}
                                <span class="text-[10px] text-slate-400 font-bold uppercase ml-1">{{ $tx->medicine->unit }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 overflow-hidden">
                                        @if($tx->user && $tx->user->avatar)
                                            <img src="{{ asset('storage/' . $tx->user->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($tx->user->full_name ?? '?', 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="text-sm font-semibold text-slate-600">{{ $tx->user->full_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-sm text-slate-500 italic max-w-xs truncate" title="{{ $tx->note }}">{{ $tx->note ?? '---' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-4xl mb-4 text-slate-200">🗃️</div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Chưa có giao dịch nào được ghi lại</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTransactions() {
    const search = document.getElementById('transactionSearch').value.toLowerCase();
    const type = document.getElementById('typeFilter').value;
    const rows = document.querySelectorAll('.transaction-row');

    rows.forEach(row => {
        const rowType = row.getAttribute('data-type');
        const content = row.getAttribute('data-content');
        
        const matchesSearch = content.includes(search);
        const matchesType = type === 'all' || rowType === type;

        if (matchesSearch && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
