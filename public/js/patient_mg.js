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
function loadData(){
     console.log("loadData running...");
    fetch('/patients/loadData')
    .then(res=>res.json())
    .then(data=>{

        let html = `
        <tr>
            <th>Tên</th>
            <th>Điện thoại</th>
            <th>Giới tính</th>
            <th>Năm sinh</th>
            <th></th>
        </tr>`;

        data.patients.forEach(p=>{

            html += `
            <tr id="row-${p.id}">
                <td>${p.full_name}</td>
                <td>${p.phone ?? ''}</td>
                <td>${p.gender}</td>
                <td>${p.birth_year}</td>
                <td>
                    <button onclick="edit(${p.id})">Sửa</button>
                    <button onclick="del(${p.id})">Xóa</button>
                </td>
            </tr>`;
        });

        patientTable.innerHTML = html;

    }).catch(e => {
        alert(e);
    });
}
function openModal() {
    modal.style.display = 'block';
}
function closeModal() {
    modal.style.display = 'none';
}
function resetForm() {
    id.value = "";
    fullName.value = "";
    phone.value = "";
    birthYear.value = "";
    gender.value = 1;
    address.value = "";
    errors.innerHTML = "";
    /* message.innerHTML = ""; */
}

function openCreate() {
    resetForm();
    openModal();
}

function save() {
    if (id.value) {
        url = `/patients/${id.value}`;
        method = 'PUT';
    }
    else {
        url = `/patients`;
        method = 'POST';
    }

    const formData = new FormData();
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
            resetForm();
            loadData();
        }
        else {
            let html = '';

            for (let field in data.errors) {
                html += `<p>${data.errors[field][0]}</p>`;
            }

            errors.innerHTML = html;
        }
    }).catch(e => {
        alert("Lỗi: " + e);
    })
}

loadData();