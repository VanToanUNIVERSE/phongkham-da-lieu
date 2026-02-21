@extends('layouts.app')

@section('content')
    <div id="modal"
        style="display:none; position:fixed; top:20%; left:35%; background:white; padding:20px; border:1px solid #ccc;">
        @include('admin.user_form')
        <button onclick="closeModal()">Đóng</button>
    </div>
    <h1>Dashboard Admin</h1>
    <button onclick="openModal()">+ Thêm người dùng</button>
    <h2>Danh sách người dùng</h2>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Vai trò</th>
                <th>Chức năng
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>
                        <a>Xem chi tiết</a>
                        <a>Sửa</a>
                        <a>Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function openModal() {
            document.getElementById("modal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>
    @if ($errors->any()|| session('success'))
        <script>
            window.onload = function() {
                openModal();
            }
        </script>
    @endif
@endsection
