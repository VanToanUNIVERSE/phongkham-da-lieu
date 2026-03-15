const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const id = document.getElementById('id');
const appointmentId = document.getElementById('appointment_id');
const doctorId = document.getElementById('doctor_id');
const patientId = document.getElementById('patient_id');
const diagnosis = document.getElementById('diagnosis');
const examinationResult = document.getElementById('examination_result');
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
    if(btn) btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lưu Hồ sơ';
    id.value = "";
    appointmentId.value = "";
    doctorId.value = "";
    patientId.value = "";
    diagnosis.value='';
    examinationResult.value='';
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

function openCreate() {
    openModal();
    resetForm();
    message.innerHTML = "";
}

function loadData() {
    fetch('/medical_records/loadData')
    .then(res => res.json())
    .then(data => {
        let html = `
            <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase tracking-widest font-black">
                <tr>
                    <th class="py-4 px-6 w-24">Mã Hồ Sơ</th>
                    <th class="py-4 px-6">Bác sĩ chủ trị</th>
                    <th class="py-4 px-6">Tên bệnh nhân</th>
                    <th class="py-4 px-6 w-1/4">Chẩn đoán</th>
                    <th class="py-4 px-6 w-1/4">Kết quả khám</th>
                    <th class="py-4 px-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600 bg-white">
        `;

        data.medical_records.forEach(m => {
            html += `
            <tr id="row-${m.id}" class="hover:bg-slate-50 transition-colors group">
                <td class="py-4 px-6">
                    <span class="inline-flex items-center text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md text-xs font-black tracking-wide">#${m.id}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="font-bold text-slate-800 flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-xs shadow-sm border border-slate-200">
                            ${m.doctor?.user?.full_name ? m.doctor.user.full_name.substring(0, 1) : '?'}
                        </div>
                        BS. ${m.doctor?.user?.full_name ?? ''}
                    </div>
                </td>
                <td class="py-4 px-6">
                    <div class="font-bold text-slate-800 flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-xs shadow-sm border border-slate-200">
                            ${m.patient?.full_name ? m.patient.full_name.substring(0, 1) : '?'}
                        </div>
                        ${m.patient?.full_name ?? ''}
                    </div>
                </td>
                <td class="py-4 px-6 text-sm text-slate-600 leading-relaxed font-medium">${m.diagnosis ? m.diagnosis.substring(0,30) + (m.diagnosis.length > 30 ? '...' : '') : '<em class="text-slate-400 font-normal">Trống</em>'}</td>
                <td class="py-4 px-6 text-sm text-emerald-700 font-medium bg-emerald-50/30">${m.examination_result ? m.examination_result.substring(0,30) + (m.examination_result.length > 30 ? '...' : '')  : '<em class="text-slate-400 font-normal">Trống</em>'}</td>
                <td class="py-4 px-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="edit(${m.id})" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded-xl transition-all shadow-sm active:scale-95" title="Xem / Sửa">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button onclick="del(${m.id})" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white rounded-xl transition-all shadow-sm active:scale-95" title="Xóa">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
    let url = '/medical_records';
    const method = 'POST';
    const formData = new FormData();
    let isReset = true;
    if (id.value) {
        url = '/medical_records/' + id.value;
        formData.append('_method', 'PUT');
        isReset = false;
    }
    formData.append('appointment_id', appointmentId.value);
    formData.append('doctor_id', doctorId.value);
    formData.append('patient_id', patientId.value);
    formData.append('diagnosis', diagnosis.value);
    formData.append('examination_result', examinationResult.value);

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
                // Hiển thị lỗi mới form Hồ Sơ Khám
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
    document.getElementById('title').innerText = "Sửa Hồ sơ khám";
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
    fetch('/medical_records/' + nid)
    .then(res => res.json())
    .then(data => {
        const medical_record = data.medical_record;
        id.value = medical_record.id;
        appointmentId.value = medical_record.appointment_id;
        doctorId.value = medical_record.doctor_id;
        patientId.value = medical_record.patient_id;
        diagnosis.value = medical_record.diagnosis;
        examinationResult.value = medical_record.examination_result;
    })
    .catch(e => {
        alert("Lỗi: " + e);
    })
}


function del(nid) {
    showDeleteConfirm(nid, 'Hồ sơ khám này', '/medical_records');
}


loadData();



