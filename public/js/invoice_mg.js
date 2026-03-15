document.addEventListener("DOMContentLoaded", function () {
    loadData();

    // Event listeners for filters
    const filterSearch = document.getElementById('filterSearch');
    const filterStatus = document.getElementById('filterStatus');
    const filterPaymentMethod = document.getElementById('filterPaymentMethod');
    const filterDateFrom = document.getElementById('filterDateFrom');
    const filterDateTo = document.getElementById('filterDateTo');

    if (filterSearch) {
        filterSearch.addEventListener('input', debounce(() => loadData(), 500));
    }
    [filterStatus, filterPaymentMethod, filterDateFrom, filterDateTo].forEach(el => {
        if (el) el.addEventListener('change', () => loadData());
    });
});

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function loadData() {
    // Collect filter values
    const params = new URLSearchParams({
        search: document.getElementById('filterSearch')?.value || '',
        status: document.getElementById('filterStatus')?.value || 'all',
        payment_method: document.getElementById('filterPaymentMethod')?.value || 'all',
        date_from: document.getElementById('filterDateFrom')?.value || '',
        date_to: document.getElementById('filterDateTo')?.value || ''
    });

    fetch(`/invoices/loadData?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            let html = "";
            if (data.length === 0) {
                html = `<tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium">Chưa có hóa đơn nào</p>
                        <p class="text-sm mt-1">Hãy tạo hóa đơn đầu tiên để bắt đầu quản lý doanh thu.</p>
                    </td>
                </tr>`;
            } else {
                data.forEach(item => {
                    // Badge Trạng thái
                    let statusBadge = '';
                    if (item.status === 'paid') {
                        statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Đã thanh toán</span>';
                    } else if (item.status === 'pending') {
                        statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Chưa thanh toán</span>';
                    } else {
                        statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Đã hủy</span>';
                    }

                    // Badge Phương thức
                    let methodBadge = item.payment_method;
                    if(methodBadge === 'cash') methodBadge = 'Tiền mặt';
                    if(methodBadge === 'transfer') methodBadge = 'Chuyển khoản';
                    if(methodBadge === 'card') methodBadge = 'Thẻ tín dụng';

                    html += `<tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">#INV-${item.id}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">${item.patient_name}</td>
                        <td class="px-6 py-4 font-bold text-blue-600">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.total_amount)}</td>
                        <td class="px-6 py-4 px-2 py-1">${statusBadge}</td>
                        <td class="px-6 py-4 text-gray-500">${methodBadge}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">${item.created_at}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">`;
                    
                    if (window.isReceptionistUser) {
                        // Receptionists only get a "Sửa trạng thái" (Edit Status) button
                        html += `
                                <button onclick="edit(${item.id})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Cập nhật trạng thái">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>`;
                    } else {
                        // Admins keep the standard full Edit and Delete buttons
                        html += `
                                <button onclick="edit(${item.id})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Sửa">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button onclick="del(${item.id})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Xóa">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>`;
                    }

                    html += `
                            </div>
                        </td>
                    </tr>`;
                });
            }
            document.getElementById("tableBody").innerHTML = html;
        });

    // Sự kiện lắng nghe Medical Record thay đổi
    const medRecordSelect = document.getElementById("medical_record_id");
    if(medRecordSelect) {
        medRecordSelect.addEventListener("change", function() {
            let recordId = this.value;
            if(!recordId) {
                document.getElementById("medicine_fee").value = "0";
                calculateTotal();
                return;
            }

            fetch(`/invoices/calculateCost/${recordId}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("medicine_fee").value = data.medicine_fee || "0";
                    calculateTotal();
                });
        });
    }

    // Sự kiện thay đổi phí khám
    const examFeeInput = document.getElementById("examination_fee");
    if(examFeeInput) {
        examFeeInput.addEventListener("input", calculateTotal);
    }
}

function openModal() {
    resetForm();
    document.getElementById("modalTitle").innerText = "Tạo hóa đơn mới";
    
    // Nút lưu mới
    const submitBtn = document.getElementById("submitBtn");
    submitBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Hóa Đơn';
    
    const modal = document.getElementById("modal");
    modal.classList.remove("hidden");
    
    // Fade in
    setTimeout(() => {
        modal.classList.remove("opacity-0");
        const panel = document.getElementById("modalContent");
        panel.classList.remove("opacity-0", "scale-95");
        panel.classList.add("opacity-100", "scale-100");
    }, 10);
}

function closeModal() {
    const modal = document.getElementById("modal");
    const panel = document.getElementById("modalContent");
    
    // Fade out
    modal.classList.add("opacity-0");
    panel.classList.remove("opacity-100", "scale-100");
    panel.classList.add("opacity-0", "scale-95");
    
    setTimeout(() => {
        modal.classList.add("hidden");
    }, 300);
}

function calculateTotal() {
    let examFee = parseInt(document.getElementById("examination_fee").value) || 0;
    let medFee = parseInt(document.getElementById("medicine_fee").value) || 0;
    document.getElementById("total_amount").value = examFee + medFee;
}

function resetForm() {
    document.getElementById("entityId").value = "";
    document.getElementById("patient_id").value = "";
    document.getElementById("medical_record_id").value = "";
    document.getElementById("examination_fee").value = "0";
    document.getElementById("medicine_fee").value = "0";
    document.getElementById("total_amount").value = "0";
    document.getElementById("status").value = "pending";
    document.getElementById("payment_method").value = "";
    document.getElementById("errors").classList.add("hidden");
    
    // Khôi phục giao diện gốc cho Modal
    const footer = document.getElementById("modalFooter");
    if (footer) {
        footer.innerHTML = `
            <button type="button" onclick="closeModal()" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors shadow-sm">
                Hủy bỏ
            </button>
            <button type="button" id="submitBtn" onclick="save()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu Hóa Đơn
            </button>
        `;
    }
    const statusWrapper = document.getElementById("statusWrapper");
    if(statusWrapper) statusWrapper.classList.remove("hidden");
    const paymentWrapper = document.getElementById("paymentMethodWrapper");
    if(paymentWrapper) paymentWrapper.classList.remove("col-span-2");
    
    // Reset red borders
    const fields = ['medical_record_id', 'examination_fee', 'status', 'payment_method'];
    fields.forEach(field => {
        const input = document.getElementById(field);
        if(input) {
            input.classList.remove('border-red-500', 'ring-red-200');
            input.disabled = false; // Re-enable for Add New
            input.classList.remove("bg-gray-50", "cursor-not-allowed"); // Clean up receptionist locks
            const errorText = document.getElementById('error-' + field);
            if(errorText) errorText.classList.add('hidden');
        }
    });
}

function save() {
    let id = document.getElementById("entityId").value;
    let url = id ? `/invoices/${id}` : "/invoices";
    let method = id ? "PUT" : "POST";

    let data = {
        medical_record_id: document.getElementById("medical_record_id").value,
        examination_fee: document.getElementById("examination_fee").value,
        status: document.getElementById("status").value,
        payment_method: document.getElementById("payment_method").value
    };

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const result = await res.json();
        if (!res.ok) {
            // Validation error handler
            document.getElementById("errors").innerHTML = "";
            document.getElementById("errors").classList.add("hidden");
            
            // Xóa lỗi cũ
            const fields = ['medical_record_id', 'examination_fee', 'status', 'payment_method'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                if(input) {
                    input.classList.remove('border-red-500', 'ring-red-200');
                    const errorText = document.getElementById('error-' + field);
                    if(errorText) errorText.classList.add('hidden');
                }
            });

            if (result.errors) {
                let errorHtml = "<ul>";
                for (let key in result.errors) {
                    errorHtml += `<li>- ${result.errors[key][0]}</li>`;
                    
                    // Highlight input
                    const input = document.getElementById(key);
                    const errorText = document.getElementById('error-' + key);
                    if (input) {
                        input.classList.add('border-red-500', 'ring-red-200');
                    }
                    if (errorText) {
                        errorText.innerText = result.errors[key][0];
                        errorText.classList.remove('hidden');
                    }
                }
                errorHtml += "</ul>";
                document.getElementById("errors").innerHTML = `<strong>Vui lòng kiểm tra lại:</strong> ${errorHtml}`;
                document.getElementById("errors").classList.remove("hidden");
            } else {
                alert(result.message || "Lỗi server");
            }
        } else {
            closeModal();
            loadData();
        }
    });
}

function edit(id) {
    fetch(`/invoices/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById("entityId").value = data.id;
            document.getElementById("medical_record_id").value = data.medical_record_id;
            document.getElementById("examination_fee").value = data.examination_fee;
            document.getElementById("medicine_fee").value = data.medicine_fee;
            document.getElementById("total_amount").value = data.total_amount;
            document.getElementById("status").value = data.status;
            document.getElementById("payment_method").value = data.payment_method || "";

            document.getElementById("modalTitle").innerText = "Cập nhật hóa đơn";
            
            // Đổi giao diện nút cập nhật
            const submitBtn = document.getElementById("submitBtn");
            if (submitBtn) {
                submitBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Cập nhật Hóa Đơn';
            }
            
            if (window.isReceptionistUser) {
                document.getElementById("medical_record_id").setAttribute("readonly", true);
                document.getElementById("medical_record_id").classList.add("bg-gray-50", "pointer-events-none");
                
                document.getElementById("examination_fee").setAttribute("readonly", true);
                document.getElementById("examination_fee").classList.add("bg-gray-50", "pointer-events-none");
                
                document.getElementById("modalTitle").innerText = "Thông tin thanh toán hóa đơn";
                
                // Ẩn field Trạng thái đi, chỉ dựa vào 2 Nút Xác Nhận / Hủy dưới cùng
                document.getElementById("statusWrapper").classList.add("hidden");
                // Mở rộng phần Phương thức thanh toán ra 2 cột cho cân đối
                document.getElementById("paymentMethodWrapper").classList.add("col-span-2");
                
                // Đổi Footer tùy theo Trạng thái Đơn
                const footer = document.getElementById("modalFooter");
                footer.className = "mt-6 flex flex-wrap justify-end gap-3"; // update flex container
                if (data.status === 'pending') {
                    footer.innerHTML = `
                        <button type="button" onclick="closeModal()" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors shadow-sm">
                            Đóng
                        </button>
                        <button type="button" onclick="saveStatus('cancelled')" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hủy đơn
                        </button>
                        <button type="button" onclick="saveStatus('paid')" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Xác nhận thanh toán
                        </button>
                    `;
                    document.getElementById("payment_method").disabled = false;
                    document.getElementById("payment_method").classList.remove("bg-gray-50", "cursor-not-allowed");
                } else {
                    // Nếu đã thanh toán hoặc hủy thì chỉ hiện 1 nút đóng để xem
                    footer.innerHTML = `
                        <button type="button" onclick="closeModal()" class="px-5 py-2 border border-blue-300 rounded-lg text-blue-700 hover:bg-blue-50 font-medium transition-colors shadow-sm w-full">
                            Đóng thông tin (Hóa đơn đã chốt)
                        </button>
                    `;
                    document.getElementById("payment_method").disabled = true;
                    document.getElementById("payment_method").classList.add("bg-gray-50", "cursor-not-allowed");
                }
            } else {
                document.getElementById("medical_record_id").removeAttribute("readonly");
                document.getElementById("medical_record_id").classList.remove("bg-gray-50", "pointer-events-none");
                
                document.getElementById("examination_fee").removeAttribute("readonly");
                document.getElementById("examination_fee").classList.remove("bg-gray-50", "pointer-events-none");
            }

            document.getElementById("errors").classList.add("hidden");
            const modal = document.getElementById("modal");
            modal.classList.remove("hidden");
            
            // Fade in
            setTimeout(() => {
                modal.classList.remove("opacity-0");
                const panel = document.getElementById("modalContent");
                panel.classList.remove("opacity-0", "scale-95");
                panel.classList.add("opacity-100", "scale-100");
            }, 10);
        });
}

function del(nid) {
    showDeleteConfirm(nid, 'Hóa đơn này', '/invoices');
}

// Function dành riêng cho Lễ tân
function saveStatus(newStatus) {
    document.getElementById("status").value = newStatus;
    
    // Yêu cầu phương thức thanh toán nếu xác nhận đóng tiền
    if (newStatus === 'paid') {
        if (!document.getElementById("payment_method").value) {
            document.getElementById("errors").innerHTML = "<strong>Vui lòng kiểm tra lại:</strong><ul><li>- Bạn chưa chọn Phương thức thanh toán khi xác nhận lấy tiền.</li></ul>";
            document.getElementById("errors").classList.remove("hidden");
            document.getElementById("payment_method").classList.add('border-red-500', 'ring-red-200');
            return;
        }
    }
    
    // Nếu pass điều kiện thì lưu vào Database dựa theo API Save mặc định
    save();
}
