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
                        <button onclick="viewUser({{ $user->id }})">
                            Xem
                        </button>
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

        function viewUser(id) {

            fetch('/users/' + id)
                .then(res => res.json())
                .then(user => {

                    document.getElementById('f_username').value = user.username;
                    document.getElementById('f_fullname').value = user.full_name;
                    document.getElementById('f_birth').value = user.birth_year;
                    document.getElementById('f_phone').value = user.phone;

                    // gender
                    if (user.gender === 'male') {
                        document.getElementById('g_male').checked = true;
                    } else {
                        document.getElementById('g_female').checked = true;
                    }

                    // select
                    document.getElementById('f_status').value = user.status;
                    document.getElementById('f_role').value = user.role_id;

                    openModal();
                });
        }
    </script>
    @if ($errors->any() || session('success'))
        <script>
            window.onload = function() {
                openModal();
            }
        </script>
    @endif
@endsection
