const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const id = document.getElementById('id');
const doctorId = document.getElementById('doctor_id');
const patientId = document.getElementById('patient_id');
const date = document.getElementById('date');
const time = document.getElementById('time');
const aStatus = document.getElementById('status');
const message = document.getElementById('message');
const table = document.getElementById('appointmentTable');
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
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Lịch hẹn';
    id.value = "";
    doctorId.value = "";
    patientId.value = "";
    date.value='';
    time.value='';
    aStatus.value = "";
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
}

function loadData() {
    fetch('/appointments/loadData')
    .then(res => res.json())
    .then(data => {
        let html = `
            <thead class="bg-gray-50 border-b border-gray-200 text-sm md:text-base text-gray-600 tracking-wider">
                <tr>
                    <th class="py-4 px-6 font-semibold w-24">Mã Hẹn</th>
                    <th class="py-4 px-6 font-semibold">Bác sĩ phụ trách</th>
                    <th class="py-4 px-6 font-semibold">Bệnh nhân</th>
                    <th class="py-4 px-6 font-semibold text-center">Ngày hẹn</th>
                    <th class="py-4 px-6 font-semibold text-center">Giờ hẹn</th>
                    <th class="py-4 px-6 font-semibold">Trạng thái</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
        `;

        data.appointments.forEach(a => {
            
            let statusBadge = '';
            if (a.status === 'pending') {
                statusBadge = `<span class="inline-flex items-center text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-yellow-100">Đang chờ khám</span>`;
            } else if (a.status === 'inprocess') {
                statusBadge = `<span class="inline-flex items-center text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-blue-100">Đang khám</span>`;
            } else if (a.status === 'complete') {
                 statusBadge = `<span class="inline-flex items-center text-green-700 bg-green-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-green-100">Đã hoàn thành</span>`;
            } else {
                 statusBadge = `<span class="inline-flex items-center text-gray-700 bg-gray-50 px-2.5 py-1 rounded-md text-xs font-semibold border border-gray-200">${a.status}</span>`;
            }

            html += `
            <tr id="row-${a.id}" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-3 px-6 font-medium text-gray-900">#${a.id}</td>
                <td class="py-3 px-6">
                    <div class="font-medium text-blue-600">BS. ${a.doctor.user.full_name ?? ''}</div>
                </td>
                <td class="py-3 px-6">
                    <div class="font-medium text-gray-800">${a.patient.full_name ?? ''}</div>
                </td>
                <td class="py-3 px-6 text-center font-medium">${a.date}</td>
                <td class="py-3 px-6 text-center font-medium">${a.time}</td>
                <td class="py-3 px-6">${statusBadge}</td>
                <td class="py-3 px-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="edit(${a.id})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Xem / Sửa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button onclick="del(${a.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
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

function openCreate() {
    openModal();
    resetForm();
    message.innerHTML = "";
}

function save() {
    let url = '/appointments';
    const method = 'POST';
    const formData = new FormData();
    let isReset = true;
    if (id.value) {
        url = '/appointments/' + id.value;
        formData.append('_method', 'PUT');
        isReset = false;
    }
    formData.append('doctor_id', doctorId.value);
    formData.append('patient_id', patientId.value);
    formData.append('date', date.value);
    formData.append('time', time.value);
    formData.append('status', aStatus.value);

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
                let html = '';
                for (let field in data.errors) {
                    html += `<p>${data.errors[field][0]}</p>`;
                }
                errors.innerHTML = html;

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
    document.getElementById('title').innerText = "Sửa lịch khám";
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
    fetch('/appointments/' + nid)
    .then(res => res.json())
    .then(data => {
        const appointment = data.appointment;
        id.value = appointment.id;
        doctorId.value = appointment.doctor_id;
        patientId.value = appointment.patient_id;
        date.value = appointment.date;
        time.value = appointment.time;
        aStatus.value = appointment.status;
    })
    .catch(e => {
        alert("Lỗi: " + e);
    })
}

function del(nid) {
    showDeleteConfirm(nid, 'mục Lịch khám này', '/appointments');
}

// Gọi hàm loadData khi trang được tải xong để cập nhật dữ liệu bảng
loadData();




