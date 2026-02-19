@extends('layouts.app')

@section('content')
    <h1>Dashboard Admin</h1>
    <h2>Danh sách người dùng<h2>
        <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Vai trò</th>
                <th>Chức năng<th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
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
@endsection
