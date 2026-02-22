@extends('layouts.app')

@section('content')

<h2>Quản lý bệnh nhân</h2>

<button onclick="openCreate()" class="btn btn-primary">+ Thêm bệnh nhân</button>

<table border="1" width="100%" id="patientTable">
</table>

{{-- MODAL --}}
<div id="modal" style="display:none; position:fixed; top:20%; left:35%; background:white; padding:20px; border:1px solid #ccc;">
    <h3 id="title">Thêm bệnh nhân</h3>
    <input type="hidden" id="id">

    <input id="full_name" placeholder="Tên"><br><br>
    <input id="phone" placeholder="Phone"><br><br>

    <select id="gender">
        <option value="1" selected>Nam</option>
        <option value="0">Nữ</option>
    </select><br><br>

    <input id="birth_year" placeholder="Năm sinh"><br><br>
    <input id="address" placeholder="Địa chỉ"><br><br>

    <button onclick="save()">Lưu</button>
    <button onclick="closeModal()">Đóng</button>
    <h3 id="message"></h3>
    <div id="errors">

    </div>
</div>
<script src="{{ asset('js/patient_mg.js') }}"></script>
@endsection