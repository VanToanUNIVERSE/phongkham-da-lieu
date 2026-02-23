@extends('layouts.app')

@section('content')
    <h2>Quản lý lịch khám</h2>

    <button onclick="openCreate()" class="btn btn-primary">+ Thêm lịch khám</button>

    <table border="1" width="100%" id="appointmentTable">
        <tr>
            <th>Mã lịch khám</th>
            <th>Bác sỉ</th>
            <th>Bệnh nhân</th>
            <th>Ngày</th>
            <th>Giờ</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        @foreach ($appointments as $a)
            <tr id="row-{{ $a->id }}">
                <td>{{ $a->id }}</td>
                <td>{{ $a->doctor->user->full_name }}</td>
                <td>{{ $a->patient->full_name }}</td>
                <td>{{ $a->date }}</td>
                <td>{{ $a->time }}</td>
                <td>{{ $a->status }}</td>
                <td>
                    <button onclick="edit({{ $a->id }})">Sửa</button>
                    <button onclick="del({{ $a->id }})">Xóa</button>
                </td>
            </tr>
        @endforeach

    </table>

    {{-- MODAL --}}
    <div id="modal"
        style="display:none; position:fixed; top:20%; left:35%; background:white; padding:20px; border:1px solid #ccc;">
        <h3 id="title">Thêm lịch khám</h3>
        <input type="hidden" id="id">

        <select id="doctor_id">
            <option value="" selected>Chọn bác sỉ</option>
            @foreach ($doctors as $d)
                <option value="{{ $d->id }}">{{ $d->user->full_name }}</option>
            @endforeach
        </select><br><br>

        <select id="patient_id">
            <option value="" selected>Chọn bệnh nhân</option>
            @foreach ($patients as $p)
                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
            @endforeach
        </select><br><br>

        <input type="date" id="date" placeholder="Chọn ngày"><br><br>
        <input type="time" id="time" placeholder="Chọn giờ"><br><br>

        <select id="status">
            <option value="" selected>Chọn trạng thái</option>
            <option value="pending" >Đang chờ khám</option>
            <option value="inprocess" >Đang khám</option>
            <option value="complete" >Đã khám</option>
        </select><br><br>

        <button onclick="save()">Lưu</button>
        <button onclick="closeModal()">Đóng</button>
        <h3 id="message"></h3>
        <div id="errors">

        </div>
    </div>
    <script src="{{ asset('js/appointment_mg.js') }}"></script>
@endsection
