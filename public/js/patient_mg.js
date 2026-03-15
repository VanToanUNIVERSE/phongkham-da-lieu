const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const fullName = document.getElementById('full_name');
const phone = document.getElementById('phone');
const gender = document.getElementById('gender');
const birthYear = document.getElementById('birth_year');
const address = document.getElementById('address');
const id = document.getElementById('id');
const message = document.getElementById('message');
const errors = document.getElementById('errors');
const patientTable = document.getElementById('patientTable');
function loadData() {
    console.log("loadData running...");
    fetch('/patients/loadData')
        .then(res => res.json())
        .then(data => {
            let html = `
                <thead class="bg-gray-50 border-b border-gray-200 text-sm md:text-base text-gray-600 tracking-wider">
                    <tr>
                        <th class="py-4 px-6 font-semibold w-24">ID</th>
                        <th class="py-4 px-6 font-semibold">Tên bệnh nhân</th>
                        <th class="py-4 px-6 font-semibold">Điện thoại</th>
                        <th class="py-4 px-6 font-semibold">Giới tính</th>
                        <th class="py-4 px-6 font-semibold">Năm sinh</th>
                        <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
            `;

            data.patients.forEach(p => {

                let genderBadge = p.gender == 1 
                    ? `<span class="inline-flex items-center text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md text-xs font-semibold">Nam</span>` 
                    : `<span class="inline-flex items-center text-pink-700 bg-pink-50 px-2.5 py-1 rounded-md text-xs font-semibold">Nữ</span>`;

                html += `
            <tr id="row-${p.id}" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-3 px-6 font-medium text-gray-900">#${p.id}</td>
                <td class="py-3 px-6 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-base shadow-sm border border-blue-200">
                        ${p.full_name.substring(0, 1)}
                    </div>
                    <div class="font-medium text-gray-800">${p.full_name}</div>
                </td>
                <td class="py-3 px-6">${p.phone ?? '<em class="text-gray-400">Trống</em>'}</td>
                <td class="py-3 px-6">${genderBadge}</td>
                <td class="py-3 px-6">${p.birth_year}</td>
                <td class="py-3 px-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="edit(${p.id})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Xem / Sửa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button onclick="del(${p.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
            });
            
            html += `</tbody>`;

            patientTable.innerHTML = html;

        }).catch(e => {
            alert(e);
        });
}
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
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Bệnh nhân';
    id.value = "";
    fullName.value = "";
    phone.value = "";
    birthYear.value = "";
    gender.value = 1;
    address.value = "";
    
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
    
    message.innerHTML = "";
}

function openCreate() {
    resetForm();
    openModal();
}

function save() {
    const formData = new FormData();
    const method = 'POST';
    let isReset = true;
    if (id.value) {
        url = `/patients/${id.value}`;
        formData.append('_method', 'PUT');
        isReset = false;
    }
    else {
        url = `/patients`;

    }


    formData.append('full_name', fullName.value);
    formData.append('phone', phone.value);
    formData.append('birth_year', birthYear.value);
    formData.append('gender', gender.value);
    formData.append('address', address.value);

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    }).then(res => res.json()).then(data => {
        message.innerHTML = data.message;
        if (data.status == 'success') {
            isReset ? resetForm() : '';
            loadData();
            closeModal(); // Đóng thẻ nếu Thành công
        }
        else {
            // Hiển thị lỗi mới
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
    }).catch(e => {
        alert("Lỗi: " + e);
    })
}

function edit(nid) {
    const btn = document.getElementById('submitBtn');
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Cập nhật';
    openModal();
    fetch('/patients/' + nid)
        .then(res => res.json())
        .then(data => {
            id.value = data.patient.id;
            fullName.value = data.patient.full_name;
            phone.value = data.patient.phone;
            birthYear.value = data.patient.birth_year;
            gender.value = data.patient.gender;
            address.value = data.patient.address;
        })
        .catch(e => {
            alert("Lỗi" + e)
        });
}




function del(nid) {
    showDeleteConfirm(nid, 'mục Bệnh nhân này', '/patients');
}

loadData();


