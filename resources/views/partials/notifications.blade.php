{{-- resources/views/partials/notifications.blade.php --}}

<!-- Toast Container -->
<div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 pointer-events-none"></div>

<!-- Custom Confirm Modal -->
<div id="custom-confirm-modal" class="hidden fixed inset-0 z-[90] overflow-y-auto w-full">
    <div id="confirm-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div id="confirm-panel" class="relative bg-white rounded-2xl shadow-2xl text-left overflow-hidden w-full max-w-sm transform transition-all duration-300 opacity-0 scale-95">
            <div class="p-6">
                <div id="confirm-icon-box" class="mx-auto flex items-center justify-center h-14 w-14 rounded-full mb-4">
                    <svg id="confirm-icon" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"></svg>
                </div>
                <h3 id="confirm-title" class="text-lg font-bold text-gray-900 text-center mb-2"></h3>
                <p id="confirm-message" class="text-sm text-gray-500 text-center mb-6"></p>
                
                <div class="flex gap-3">
                    <button type="button" id="confirm-cancel-btn" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="button" id="confirm-ok-btn" class="flex-1 px-4 py-2.5 text-white rounded-xl font-bold transition-colors shadow-sm">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .toast-item {
        @apply flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border transition-all duration-500 transform translate-x-10 opacity-0 pointer-events-auto max-w-xs;
    }
    .toast-item.show {
        @apply translate-x-0 opacity-100;
    }
    .toast-success { @apply bg-emerald-50 border-emerald-200 text-emerald-800; }
    .toast-error { @apply bg-red-50 border-red-200 text-red-800; }
    .toast-info { @apply bg-blue-50 border-blue-200 text-blue-800; }
    .toast-warning { @apply bg-amber-50 border-amber-200 text-amber-800; }
</style>

<script>
    /**
     * Show a toast notification
     * type: 'success', 'error', 'info', 'warning'
     */
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type}`;
        
        const icons = {
            success: '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            error: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
            info: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            warning: '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
        };

        toast.innerHTML = `
            <div class="flex-shrink-0">${icons[type]}</div>
            <div class="font-medium text-sm leading-tight">${message}</div>
        `;

        container.appendChild(toast);
        
        // Triggers animation
        requestAnimationFrame(() => toast.classList.add('show'));

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3500);
    }

    /**
     * Show a custom confirmation modal
     * options: { title, type, confirmText, cancelText }
     */
    function showConfirm(message, onConfirm, onCancel, options = {}) {
        const modal = document.getElementById('custom-confirm-modal');
        const title = options.title || 'Xác nhận';
        const type = options.type || 'warning'; // warning, info, error
        const confirmText = options.confirmText || 'Xác nhận';
        const cancelText = options.cancelText || 'Hủy bỏ';

        const config = {
            warning: { bg: 'bg-amber-100', text: 'text-amber-600', btn: 'bg-amber-500 hover:bg-amber-600', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' },
            error: { bg: 'bg-red-100', text: 'text-red-600', btn: 'bg-red-600 hover:bg-red-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' },
            info: { bg: 'bg-blue-100', text: 'text-blue-600', btn: 'bg-blue-600 hover:bg-blue-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' }
        };

        const cfg = config[type] || config.warning;

        document.getElementById('confirm-title').innerText = title;
        document.getElementById('confirm-message').innerText = message;
        document.getElementById('confirm-ok-btn').innerText = confirmText;
        document.getElementById('confirm-ok-btn').className = `flex-1 px-4 py-2.5 text-white rounded-xl font-bold transition-colors shadow-sm ${cfg.btn}`;
        document.getElementById('confirm-cancel-btn').innerText = cancelText;
        
        const iconBox = document.getElementById('confirm-icon-box');
        iconBox.className = `mx-auto flex items-center justify-center h-14 w-14 rounded-full mb-4 ${cfg.bg} ${cfg.text}`;
        document.getElementById('confirm-icon').innerHTML = cfg.icon;

        modal.classList.remove('hidden');
        
        setTimeout(() => {
            document.getElementById('confirm-backdrop').classList.replace('opacity-0', 'opacity-100');
            const panel = document.getElementById('confirm-panel');
            panel.classList.replace('opacity-0', 'opacity-100');
            panel.classList.replace('scale-95', 'scale-100');
        }, 10);

        const close = () => {
            document.getElementById('confirm-backdrop').classList.replace('opacity-100', 'opacity-0');
            const panel = document.getElementById('confirm-panel');
            panel.classList.replace('opacity-100', 'opacity-0');
            panel.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };

        document.getElementById('confirm-ok-btn').onclick = () => { close(); if(onConfirm) onConfirm(); };
        document.getElementById('confirm-cancel-btn').onclick = () => { close(); if(onCancel) onCancel(); };
    }

    // Override alert/confirm for globally consistency if desired, 
    // but better to call showToast/showConfirm explicitly to control types/options.
    // window.alert = (msg) => showToast(msg, 'info');
</script>
