@if (auth()->user()->role->name == 'Admin')
    <ul>
        <li><a>Quản lý người dùng</a></li>
        <li><a>Thống kê</a></li>
    </ul>
@endif
