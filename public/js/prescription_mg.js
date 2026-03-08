const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const medicines = window.medicines;

const id = document.getElementById('id');
const medicalRecordId = document.getElementById('medical_record_id');
const userId = document.getElementById('user_id');
const content = document.getElementById('content');
const dispenseStatus = document.getElementById('dispense_status');

const message = document.getElementById('message');
const table = document.getElementById('table');

function openModal() {
    modal.classList.remove('hidden');
    // Đợi 10ms để trình duyệt render class hidden ra khỏi DOM trước khi chạy transition
    setTimeout(() => {
        document.getElementById('modal-backdrop').classList.remove('opacity-0');
        const panel = document.getElementById('modal-panel');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function closeModal() {
    document.getElementById('modal-backdrop').classList.add('opacity-0');
    const panel = document.getElementById('modal-panel');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    
    // Đợi transition chạy xong (300ms) rồi mới thêm lại class hidden
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function resetForm() {
    const btn = document.getElementById('submitBtn');
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Đơn thuốc';
    id.value = "";
    medicalRecordId.value = "";
    userId.value = "";
    content.value = "";
    dispenseStatus.value = '';
    /* message.innerHTML = ""; */
    
    // Clear errors
    document.querySelectorAll('[id^="err_"]').forEach(el => {
        el.innerText = "";
        el.classList.add('hidden');
    });
    
    // Reset border color
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        el.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
    });

    document.getElementById('title').innerHTML = "Thêm đơn thuốc";
    document.getElementById("medicine-items").innerHTML = '';
}

function openCreate() {
    openModal();
    resetForm();
    message.innerHTML = "";
}

let row = '';
function addMedicineRow(medicines) {

    const tbody = document.getElementById("medicine-items");

    let options = '<option value="">Chọn thuốc</option>';
    medicines.forEach(m => {
        if (m.is_active == 1) {
            options += `
                <option value="${m.id}">
                    ${m.name} (Còn: ${m.stock})
                </option>
            `;
        }
    });

    const tr = document.createElement("tr");
    tr.className = 'medicine_item';

    tr.innerHTML = `
        <td class="py-2 px-1">
            <select class="medicine_id w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                ${options}
            </select>
        </td>
        <td class="py-2 px-1"><input type="number" class="quantity w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" min="1" value="1"></td>
        <td class="py-2 px-1"><input type="text" class="dosage w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="VD: 2 viên"></td>
        <td class="py-2 px-1"><input type="text" class="usage w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="VD: Sáng, Tối"></td>
        <td class="py-2 px-1 text-center">
            <button type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;

    // nút xoá
    tr.querySelector("button").onclick = function () {
        tr.remove();
    };

    tbody.appendChild(tr);
}


function loadData() {
    let html = '';
    fetch('/prescriptions/loadData')
        .then(res => res.json())
        .then(data => {
            html += `
        <tr>
            <th>Mã đơn</th>
            <th>Mã bệnh án</th>
            <th>Nhân viên phát</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        `;

            

            data.prescriptions.forEach(m => {
                let badge = m.dispense_status === 'Đã phát' 
                    ? `<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-sm font-medium"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Đã phát</span>`
                    : `<span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-full text-sm font-medium"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg> Chưa phát</span>`;

                html += `
        <tr id="row-${m.id}" class="hover:bg-gray-50/50 transition-colors">
            <td class="py-3 px-6 font-medium text-gray-900">#${m.id}</td>
            <td class="py-3 px-6"><span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-sm font-medium">BA-${m.medical_record_id}</span></td>
            <td class="py-3 px-6 flex items-center gap-2">
                <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                    ${m.user.full_name.substring(0, 1)}
                </div>
                ${m.user.full_name}
            </td>
            <td class="py-3 px-6 truncate max-w-xs" title="${m.content}">${m.content}</td>
            <td class="py-3 px-6">${badge}</td>
            <td class="py-3 px-6 text-right">
                <div class="flex justify-end gap-2">
                    <button onclick="edit(${m.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button onclick="del(${m.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
            });

            table.innerHTML = html;
        })
        .catch(e => {
            alert("Lỗi: " + e);
        })
}

function save() {
    let url = '/prescriptions';
    const method = 'POST';
    const formData = new FormData();
    let isReset = true;
    if (id.value) {
        url = '/prescriptions/' + id.value;
        formData.append('_method', 'PUT');
        isReset = false;
    }
    formData.append('medical_record_id', medicalRecordId.value);
    formData.append('dispensed_by', userId.value);
    formData.append('content', content.value);
    formData.append('dispense_status', dispenseStatus.value);

    const items = [];
    const medicineItems = document.querySelectorAll(".medicine_item");
    medicineItems.forEach(row => {
        const medicine_id = row.querySelector('.medicine_id').value;
        const quantity = row.querySelector('.quantity').value;
        const dosage = row.querySelector('.dosage').value;
        const usage = row.querySelector('.usage').value;

        if (medicine_id && quantity) {
            items.push({
                medicine_id,
                quantity,
                dosage,
                usage
            });
        }
    });
    items.forEach((item, index) => {
        formData.append(`items[${index}][medicine_id]`, item.medicine_id);
        formData.append(`items[${index}][quantity]`, item.quantity);
        formData.append(`items[${index}][dosage]`, item.dosage);
        formData.append(`items[${index}][usage]`, item.usage);
    });

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            message.innerHTML = data.message;
            if (data.status == 'success') {
                isReset ? resetForm() : '';
                loadData();
                closeModal();
            }
            else {
                // Hiển thị lỗi mới form Đơn Thuốc
                for (let field in data.errors) {
                    let errEl = document.getElementById('err_' + field);
                    if (errEl) {
                        errEl.innerText = data.errors[field][0];
                        errEl.classList.remove('hidden');
                    }
                    
                    // Đổi viền input sang màu đỏ
                    let inputEl = document.getElementById(field);
                    if (inputEl) {
                        inputEl.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        inputEl.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                    }
                }
            }
        })
        .catch(e => {
            alert("Lỗi: " + e);
        });
}


function edit(id) {
    const btn = document.getElementById('submitBtn');
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Cập nhật';

    openModal();

    document.getElementById('title').innerText = "Sửa đơn thuốc";
        // Clear errors
    document.querySelectorAll('[id^="err_"]').forEach(el => {
        el.innerText = "";
        el.classList.add('hidden');
    });
    
    // Reset border color
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        el.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
    });
    message.innerHTML = '';

    fetch('/prescriptions/' + id)
        .then(res => res.json())
        .then(res => {
            console.log(res); 
            const p = res.data;

            // Header
            document.getElementById('id').value = p.id;
            document.getElementById('medical_record_id').value = p.medical_record_id;
            document.getElementById('user_id').value = p.dispensed_by;
            document.getElementById('dispense_status').value = p.dispense_status;
            content.value = p.content;

            // Clear items cũ
            const tbody = document.getElementById("medicine-items");
            tbody.innerHTML = '';

            // Render items
            if(!p.items) {
                console.log("Don thuoc rong");
            }
            else {
                p.items.forEach(item => {
                addMedicineRowEdit(item);
            });
            }
            

        })
        .catch(e => {
            console.log("Lỗi: " + e);
        });
}

function addMedicineRowEdit(item) {


    let options = '<option value="">Chọn thuốc</option>';

    medicines.forEach(m => {
        options += `
            <option value="${m.id}" 
                ${m.id == item.medicine_id ? 'selected' : ''}>
                ${m.name} (Còn: ${m.stock})
            </option>
        `;
    });

    const tr = document.createElement("tr");
    tr.classList.add("medicine_item");

    tr.innerHTML = `
        <td class="py-2 px-1">
            <select class="medicine_id w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                ${options}
            </select>
        </td>
        <td class="py-2 px-1"><input type="number" class="quantity w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" value="${item.quantity}"></td>
        <td class="py-2 px-1"><input type="text" class="dosage w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" value="${item.dosage ?? ''}"></td>
        <td class="py-2 px-1"><input type="text" class="usage w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" value="${item.usage ?? ''}"></td>
        <td class="py-2 px-1 text-center">
            <button type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;

    tr.querySelector("button").onclick = function () {
        tr.remove();
    };

    document.getElementById("medicine-items").appendChild(tr);
}


function del(nid) {
    showDeleteConfirm(nid, 'mục Đơn thuốc này', '/prescriptions');
}




