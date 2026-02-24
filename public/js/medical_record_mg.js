const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const id = document.getElementById('id');
const appointmentId = document.getElementById('appointment_id');
const doctorId = document.getElementById('doctor_id');
const patientId = document.getElementById('patient_id');
const diagnosis = document.getElementById('diagnosis');
const examinationResult = document.getElementById('examination_result');
const message = document.getElementById('message');
const errors = document.getElementById('errors');
const table = document.getElementById('table');

function openModal() {
    modal.style.display = 'block';
}
function closeModal() {
    modal.style.display = 'none';
}

function resetForm() {
    id.value = "";
    appointmentId.value = "";
    doctorId.value = "";
    patientId.value = "";
    diagnosis.value='';
    examinationResult.value='';
    /* message.innerHTML = ""; */
    errors.innerHTML = "";
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
        table.innerHTML = `
        <tr>
            <th>Mã bệnh án</th>
            <th>Mã lịch khám</th>
            <th>Bác sỉ</th>
            <th>Bệnh nhân</th>
            <th>Chẩn đoán</th>
            <th>Kết quả khám</th>
            <th>Thao tác</th>
        </tr>
        `;
        
        data.medical_records.forEach(m => {
            table.innerHTML += `<tr id="row-${m.id}">
                <td>${m.id}</td>
                <td>${m.appointment_id}</td>
                <td>${m.doctor.user.full_name ?? ''}</td>
                <td>${m.patient.full_name ?? ''}</td>
                <td>${m.diagnosis}</td>
                <td>${m.examination_result}</td>
                <td>
                    <button onclick="edit(${m.id})">Sửa</button>
                    <button onclick="del(${m.id})">Xóa</button>
                </td>
            </tr>`
        });
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
    openModal();
    document.getElementById('title').innerText = "Sửa bệnh án";
    errors.innerHTML = '';
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
    if(!confirm("Bạn có chắc muốn xóa bệnh án này?")) return;
    const formData = new FormData();
    formData.append('_method', 'DELETE')
    fetch('/medical_records/' + nid, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status == 'success') {
            loadData();
        }
    })
    .catch(e => {
        alert("Lỗi: " + e);
    })
}


loadData();