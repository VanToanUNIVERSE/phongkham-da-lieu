@extends('layouts.app')

@section('content')
    <h2>Quản lý đơn thuốc</h2>

    <button onclick="openCreate()" class="btn btn-primary">+ Thêm đơn</button>

    <table border="1" width="100%" id="table">
        <tr>
            <th>Mã đơn</th>
            <th>Mã bệnh án</th>
            <th>Nhân viên phát thuốc</th>
            <th>Nội dụng</th>
            <th>Trạng thaí</th>
            <th>Thao tác</th>
        </tr>
        @foreach ($prescriptions as $p)
            <tr id="row-{{ $p->id }}">
                <td>{{ $p->id }}</td>
                <td>{{ $p->medical_record_id }}</td>
                <td>{{ $p->user->full_name }}</td>
                <td>{{ $p->content }}</td>
                <td>{{ $p->dispense_status }}</td>
                <td>
                    <button onclick="edit({{ $p->id }})">Sửa</button>
                    <button onclick="del({{ $p->id }})">Xóa</button>
                </td>
            </tr>
        @endforeach

    </table>

    {{-- MODAL --}}
    <div id="modal"
    style="display:none; position:fixed; top:10%; left:25%; width:50%; background:white; padding:20px; border:1px solid #ccc;">

    <h3 id="title">Thêm đơn thuốc</h3>

    <input type="hidden" id="id">

    <!-- HEADER -->
    <select id="medical_record_id">
        <option value="">Chọn mã bệnh án</option>
        @foreach ($medical_records as $a)
            <option value="{{ $a->id }}">{{ $a->id }}</option>
        @endforeach
    </select><br><br>

    <select id="user_id">
        <option value="">Chọn nhân viên phát thuốc</option>
        @foreach ($users as $s)
            <option value="{{ $s->id }}">{{ $s->full_name }}</option>
        @endforeach
    </select><br><br>

    <input type="text" id="content" placeholder="Nhập nội dung">

    <select id="dispense_status">
        <option value="Chưa phát">Chưa phát</option>
        <option value="Đã phát">Đã phát</option>
    </select>

    <hr>

    <!-- DETAIL THUỐC -->
    <h4>Chi tiết thuốc</h4>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Thuốc</th>
                <th>Số lượng</th>
                <th>Liều dùng</th>
                <th>Cách dùng</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="medicine-items">
        </tbody>
    </table>

    <br>
    <button onclick="addMedicineRow({{ $medicines }})">+ Thêm thuốc</button>

    <hr>


    <h3 id="message"></h3>
    <div id="errors"></div>
    <button onclick="save()">Lưu</button>
    <button onclick="closeModal()">Đóng</button>

</div>
    <script>
    window.medicines = @json($medicines);
</script>
    <script src="{{ asset('js/prescription_mg.js') }}"></script>
@endsection
