const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');

const id = document.getElementById('id');
const mediceName = document.getElementById('name');
const unit = document.getElementById('unit');
const stock = document.getElementById('stock');
const price = document.getElementById('price');
const expiryDate = document.getElementById('expiry_date');
const description = document.getElementById('description');
const isActive = document.getElementById('is_active');

const message = document.getElementById('message');
const table = document.getElementById('table');

function openModal() {
    modal.classList.remove('hidden');
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
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function resetForm() {
    const btn = document.getElementById('submitBtn');
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Thông tin';
    id.value = "";
    mediceName.value = "";
    unit.value = "";
    stock.value = "";
    price.value = '';
    expiryDate.value = '';
    description.value = '';
    isActive.value = '1';
    /* message.innerHTML = ""; */
    
}

function openCreate() {
    openModal();
    resetForm();
    message.innerHTML = "";
}

function loadData() {
    let html = '';
    fetch('/medicines/loadData')
        .then(res => res.json())
        .then(data => {
                html += `
                <thead class="bg-gray-50 border-b border-gray-200 text-sm md:text-base text-gray-600 tracking-wider">
                    <tr>
                        <th class="py-4 px-6 font-semibold w-24">ID</th>
                        <th class="py-4 px-6 font-semibold">Tên thuốc</th>
                        <th class="py-4 px-6 font-semibold">Đơn vị</th>
                        <th class="py-4 px-6 font-semibold text-right">Kho</th>
                        <th class="py-4 px-6 font-semibold text-right">Đơn giá</th>
                        <th class="py-4 px-6 font-semibold">HSD</th>
                        <th class="py-4 px-6 font-semibold">Trạng thái</th>
                        <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
            `;

            data.medicines.forEach(m => {
                let statusBadge = m.is_active == 1 
                    ? `<span class="inline-flex items-center text-green-700 bg-green-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-100">Hoạt động</span>` 
                    : `<span class="inline-flex items-center text-red-700 bg-red-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-red-100">Ngưng</span>`;

                html += `
            <tr id="row-${m.id}" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-3 px-6 font-medium text-gray-900">#${m.id}</td>
                <td class="py-3 px-6 font-medium text-blue-600">${m.name}</td>
                <td class="py-3 px-6">${m.unit}</td>
                <td class="py-3 px-6 text-right font-medium">${Number(m.stock).toLocaleString()}</td>
                <td class="py-3 px-6 text-right font-medium text-orange-600">${Number(m.price).toLocaleString()} đ</td>
                <td class="py-3 px-6 text-sm">${m.expiry_date ?? '<em class="text-gray-400">Không có</em>'}</td>
                <td class="py-3 px-6">${statusBadge}</td>
                <td class="py-3 px-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="edit(${m.id})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Xem / Sửa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button onclick="del(${m.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
            });

            html += `</tbody>`;

            table.innerHTML = html;
        })
        .catch(e => {
            alert("Lỗi: " + e);
        })
}

function save() {
    let url = '/medicines';
    const method = 'POST';
    const formData = new FormData();
    let isReset = true;
    if (id.value) {
        url = '/medicines/' + id.value;
        formData.append('_method', 'PUT');
        isReset = false;
    }
    formData.append('name', mediceName.value);
    formData.append('unit', unit.value);
    formData.append('stock', stock.value);
    formData.append('price', price.value);
    formData.append('expiry_date', expiryDate.value);
    formData.append('description', description.value);
    formData.append('is_active', isActive.value);

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
                // Hiển thị lỗi mới form Thuốc
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


function edit(nid) {
    const btn = document.getElementById('submitBtn');
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Cập nhật';
    openModal();
    document.getElementById('title').innerText = "Sửa thông tin thuốc";
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
    fetch('/medicines/' + nid)
        .then(res => res.json())
        .then(data => {
            const medicine = data.medicine;
            id.value = medicine.id;
            mediceName.value = medicine.name;
            unit.value = medicine.unit;
            stock.value = medicine.stock;
            price.value = medicine.price;
            expiryDate.value = medicine.expiry_date;
            description.value = medicine.description;
            isActive.value = medicine.is_active;
        })
        .catch(e => {
            alert("Lỗi: " + e);
        })
}


function del(nid) {
    showDeleteConfirm(nid, 'mục Thuốc này', '/medicines');
}

loadData();



