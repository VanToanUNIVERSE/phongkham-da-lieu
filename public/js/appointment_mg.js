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
    aStatus.value = "";
    /* message.innerHTML = ""; */
    errors.innerHTML = "";
}

function openCreate() {
    openModal();
    resetForm();
}

function save() {
    let url = '/appointments';
    const method = 'POST';
    const formData = new FormData();
    if (id.value) {
        url = '/appointments/' + id.value;
        formData.append('_method', 'PUT');
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
                resetForm();
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

