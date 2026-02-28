@extends('layouts.app')

@section('content')
    <h2>Quản lý thuốc</h2>

    <button onclick="openCreate()" class="btn btn-primary">+ Thêm thuốc</button>

    <table border="1" width="100%" id="table">
        <tr>
            <th>Mã thuốc</th>
            <th>Tên thuốc</th>
            <th>Đơn vị</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Hạn sử dụng</th>
            {{-- <th>Mô tả</th> --}}
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        @foreach ($medicines as $m)
            <tr id="row-{{ $m->id }}">
                <td>{{ $m->id }}</td>
                <td>{{ $m->name }}</td>
                <td>{{ $m->unit }}</td>
                <td>{{ $m->stock }}</td>
                <td>{{ $m->price }}</td>
                <td>{{ $m->expiry_date }}</td>
                {{-- <td>{{ $m->description }}</td> --}}
                <td>{{ $m->is_active }}</td>
                <td>
                    <button onclick="edit({{ $m->id }})">Sửa</button>
                    <button onclick="del({{ $m->id }})">Xóa</button>
                </td>
            </tr>
        @endforeach

    </table>

    {{-- MODAL --}}
    <div id="modal"
        style="display:none; position:fixed; top:20%; left:35%; background:white; padding:20px; border:1px solid #ccc;">
        <h3 id="title">Thêm thuốc</h3>
        <input type="hidden" id="id">

        <input type="text" id="name" placeholder="Nhập tên thuốc"><br><br>
        <input type="text" id="unit" placeholder="Nhập đơn vị"><br><br>
        <input type="number" id="stock" placeholder="Nhập số lượng"><br><br>
        <input type="number" id="price" placeholder="Nhập đơn giá"><br><br>
        <input type="date" id="expiry_date" placeholder="Nhập HSD"><br><br>
        <input type="text" aria-rowspan="4" id="description" placeholder="Nhập mô tả"><br><br>
        <select id="is_active">
            <option value="1" selected>Hoạt động</option>
            <option value="0" selected>Bị thu hồi</option>
        </select><br><br>

        <button onclick="save()">Lưu</button>
        <button onclick="closeModal()">Đóng</button>
        <h3 id="message"></h3>
        <div id="errors">

        </div>
    </div>
    <script src="{{ asset('js/medicine_mg.js') }}"></script>
@endsection
