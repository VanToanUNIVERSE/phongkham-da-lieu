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
    mediceName.value = "";
    unit.value = "";
    stock.value = "";
    price.value = '';
    expiryDate.value = '';
    description.value = '';
    isActive.value = '1';
    /* message.innerHTML = ""; */
    errors.innerHTML = "";
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
        <tr>
            <th>Mã thuốc</th>
            <th>Tên thuốc</th>
            <th>Đơn vị</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Hạn sử dụng</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        `;

            

            data.medicines.forEach(m => {
                html += `
        <tr id="row-${m.id}">
            <td>${m.id}</td>
            <td>${m.name}</td>
            <td>${m.unit}</td>
            <td>${m.stock}</td>
            <td>${Number(m.price).toLocaleString()} đ</td>
            <td>${m.expiry_date ?? ''}</td>
            <td>
                ${m.is_active == 1
                        ? '<span style="color:green">Hoạt động</span>'
                        : '<span style="color:red">Ngưng</span>'}
            </td>
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
    document.getElementById('title').innerText = "Sửa thông tin thuốc";
    errors.innerHTML = '';
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
    if (!confirm("Bạn có chắc muốn xóa thuốc?")) return;
    const formData = new FormData();
    formData.append('_method', 'DELETE')
    fetch('/medicines/' + nid, {
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