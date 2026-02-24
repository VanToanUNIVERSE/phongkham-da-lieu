@extends('layouts.app')

@section('content')
    <h2>Quản lý bệnh án</h2>

    <button onclick="openCreate()" class="btn btn-primary">+ Thêm lịch khám</button>

    <table border="1" width="100%" id="table">
        <tr>
            <th>Mã bệnh án</th>
            <th>Mã lịch khám</th>
            <th>Bác sỉ</th>
            <th>Bệnh nhân</th>
            <th>Chẩn đoán</th>
            <th>Kết quả khám</th>
            <th>Thao tác</th>
        </tr>
        @foreach ($medical_records as $m)
            <tr id="row-{{ $m->id }}">
                <td>{{ $m->id }}</td>
                <td>{{ $m->appointment_id }}</td>
                <td>{{ $m->doctor->user->full_name }}</td>
                <td>{{ $m->patient->full_name }}</td>
                <td>{{ $m->diagnosis }}</td>
                <td>{{ $m->examination_result }}</td>
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
        <h3 id="title">Thêm hồ sơ bệnh án</h3>
        <input type="hidden" id="id">

        <select id="appointment_id">
            <option value="" selected>Chọn lịch khám</option>
            @foreach ($appointments as $a)
                <option value="{{ $a->id }}">{{ $a->id }}</option>
            @endforeach
        </select><br><br>

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

        <input type="text" id="diagnosis" placeholder="Nhập chẩn đoán"><br><br>
        <input type="text" id="examination_result" placeholder="Nhập kết quả khám"><br><br>


        <button onclick="save()">Lưu</button>
        <button onclick="closeModal()">Đóng</button>
        <h3 id="message"></h3>
        <div id="errors">

        </div>
    </div>
    <script src="{{ asset('js/medical_record_mg.js') }}"></script>
@endsection
