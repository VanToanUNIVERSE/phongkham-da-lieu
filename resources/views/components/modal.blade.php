@props([
    'id' => 'modal',
    'titleId' => 'title',
    'title' => 'Title',
    'maxWidth' => 'max-w-2xl',
    'submitId' => 'submitBtn',
    'submitClick' => 'save()',
    'submitText' => 'Lưu',
    'submitIcon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto w-full" aria-labelledby="{{ $titleId }}" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div id="modal-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    
    <!-- Modal Dialog -->
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div id="modal-panel" class="relative bg-white rounded-[2rem] shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:{{ $maxWidth }} transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 border border-slate-100">
            
            <!-- Modal Header -->
            <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                <h3 id="{{ $titleId }}" class="text-xl font-black text-slate-900 tracking-tight">{{ $title }}</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-100 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-6 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-slate-900 font-bold transition-all shadow-sm active:scale-95 text-sm">
                    Hủy bỏ
                </button>
                <button id="{{ $submitId }}" onclick="{{ $submitClick }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-black font-bold shadow-lg shadow-slate-900/20 transition-all flex items-center gap-2 active:scale-95 text-sm">
                    {!! $submitIcon !!}
                    {{ $submitText }}
                </button>
            </div>
            
        </div>
    </div>
</div>
