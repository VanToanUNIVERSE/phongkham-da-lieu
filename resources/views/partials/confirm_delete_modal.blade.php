<!-- confirm_delete_modal.blade.php -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto w-full" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div id="delete-modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    
    <!-- Modal Dialog -->
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div id="delete-modal-panel" class="relative bg-white rounded-xl shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:max-w-md transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Modal Body -->
            <div class="p-6 text-center">
                <!-- Icon cảnh báo -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-5">
                    <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Xác nhận xóa</h3>
                <p class="text-sm text-gray-500 mb-6">Bạn có chắc chắn muốn xóa <span id="deleteEntityName" class="font-bold text-gray-800"></span>? Hành động này không thể hoàn tác.</p>
                
                <!-- ID VÀ ENDPOINT BÍ MẬT DƯỚI NỀN -->
                <input type="hidden" id="deleteEntityId">
                <input type="hidden" id="deleteEndpoint">

                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors shadow-sm w-1/2">
                        Hủy bỏ
                    </button>
                    <button type="button" id="btnConfirmDelete" onclick="executeDelete()" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm transition-colors w-1/2 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Xóa ngay
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    function showDeleteConfirm(id, name, endpoint) {
        document.getElementById('deleteEntityId').value = id;
        document.getElementById('deleteEntityName').innerText = name;
        document.getElementById('deleteEndpoint').value = endpoint;
        
        const modal = document.getElementById('deleteConfirmModal');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            document.getElementById('delete-modal-backdrop').classList.remove('opacity-0');
            const panel = document.getElementById('delete-modal-panel');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal-backdrop').classList.add('opacity-0');
        const panel = document.getElementById('delete-modal-panel');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        
        setTimeout(() => {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }, 300);
    }

    function executeDelete() {
        const id = document.getElementById('deleteEntityId').value;
        const endpoint = document.getElementById('deleteEndpoint').value;
        const btn = document.getElementById('btnConfirmDelete');
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // Đổi trạng thái nút thành đang loading
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xóa...';
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        fetch(`${endpoint}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                closeDeleteModal();
                // Load lại trang hoặc gọi loadData tùy trang
                if (typeof loadData === "function") {
                    loadData();
                } else {
                    location.reload(); 
                }
            } else {
                showToast(data.message || 'Không thể xóa', 'error');
            }
        })
        .catch(e => {
            showToast('Lỗi kết nối khi xoá', 'error');
            console.error(e);
        })
        .finally(() => {
             // Trả lại trạng thái cho nút
             btn.disabled = false;
             btn.classList.remove('opacity-75', 'cursor-not-allowed');
             btn.innerHTML = originalHtml;
        });
    }
</script>
