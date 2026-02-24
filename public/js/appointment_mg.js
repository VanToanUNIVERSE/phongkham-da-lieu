const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');
const id = document.getElementById('id');
const doctorId = document.getElementById('doctor_id');
const patientId = document.getElementById('patient_id');
const date = document.getElementById('date');
const time = document.getElementById('time');
const aStatus = document.getElementById('status');
const message = document.getElementById('message');
const errors = document.getElementById('errors');
const table = document.getElementById('appointmentTable');
function openModal() {
    modal.style.display = 'block';
}
function closeModal() {
    modal.style.display = 'none';
}

function resetForm() {
    id.value = "";
    doctorId.value = "";
    patientId.value = "";
    date.value='';
    time.value='';
    aStatus.value = "";
    /* message.innerHTML = ""; */
    errors.innerHTML = "";
}

function loadData() {
    fetch('/appointments/loadData')
    .then(res => res.json())
    .then(data => {
        table.innerHTML = `
        <tr>
            <th>Mã lịch khám</th>
            <th>Bác sỉ</th>
            <th>Bệnh nhân</th>
            <th>Ngày</th>
            <th>Giờ</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        `;
        
        data.appointments.forEach(a => {
            table.innerHTML += `<tr id="row-${a.id}">
                <td>${a.id}</td>
                <td>${a.doctor.user.full_name ?? ''}</td>
                <td>${a.patient.full_name ?? ''}</td>
                <td>${a.date}</td>
                <td>${a.time}</td>
                <td>${a.status}</td>
                <td>
                    <button onclick="edit(${a.id})">Sửa</button>
                    <button onclick="del(${a.id})">Xóa</button>
                </td>
            </tr>`
        });
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
    document.getElementById('title').innerText = "Sửa lịch khám";
    errors.innerHTML = '';
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
    if(!confirm("Bạn có chắc muốn xóa lịch bệnh này?")) return;
    const formData = new FormData();
    formData.append('_method', 'DELETE')
    fetch('/appointments/' + nid, {
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
