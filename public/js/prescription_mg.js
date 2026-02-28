const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const modal = document.getElementById('modal');


const id = document.getElementById('id');
const medicalRecordId = document.getElementById('medical_record_id');
const userId = document.getElementById('user_id');
const content = document.getElementById('content');
const dispenseStatus = document.getElementById('dispense_status');

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
    medicalRecordId.value = "";
    userId.value = "";
    content.value = "";
    dispenseStatus.value = '';
    /* message.innerHTML = ""; */
    errors.innerHTML = "";
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
        <td>
            <select class="medicine_id">
                ${options}
            </select>
        </td>
        <td><input type="number" class="quantity" min="1"></td>
        <td><input type="text" class="dosage"></td>
        <td><input type="text" class="usage"></td>
        <td><button type="button">X</button></td>
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
                html += `
        <tr id="row-${m.id}">
            <td>${m.id}</td>
            <td>${m.medical_record_id}</td>
            <td>${m.user.full_name}</td>
            <td>${m.content}</td>
            <td>${m.dispense_status}</td>
            <td>
                <button onclick="edit(${m.id})">Sửa</button>
                <button onclick="del(${m.id})">Xóa</button>
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


function edit(id, medicines) {

    openModal();

    document.getElementById('title').innerText = "Sửa đơn thuốc";
    errors.innerHTML = '';
    message.innerHTML = '';

    fetch('/prescriptions/' + id)
        .then(res => res.json())
        .then(res => {

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
            p.items.forEach(item => {
                addMedicineRowEdit(item, medicines);
            });

        })
        .catch(e => {
            alert("Lỗi: " + e);
        });
}

function addMedicineRowEdit(item, medicines) {


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
        <td>
            <select class="medicine_id">
                ${options}
            </select>
        </td>
        <td><input type="number" class="quantity" value="${item.quantity}"></td>
        <td><input type="text" class="dosage" value="${item.dosage ?? ''}"></td>
        <td><input type="text" class="usage" value="${item.usage ?? ''}"></td>
        <td><button type="button">X</button></td>
    `;

    tr.querySelector("button").onclick = function () {
        tr.remove();
    };

    document.getElementById("medicine-items").appendChild(tr);
}


function del(nid) {
    if (!confirm("Bạn có chắc muốn xóa thuốc?")) return;
    const formData = new FormData();
    formData.append('_method', 'DELETE')
    fetch('/prescriptions/' + nid, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status == 'success') {
                loadData();
            }
        })
        .catch(e => {
            alert("Lỗi: " + e);
        })
}

loadData();