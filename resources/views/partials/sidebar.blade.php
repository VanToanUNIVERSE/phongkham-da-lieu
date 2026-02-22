@if (auth()->user()->role->name == 'Admin')
    <ul>
        <li><a href="{{ route('adminDashboard') }}">Quản lý người dùng</a></li>
        <li><a href="{{ route('patients.index') }}">Quản lý bệnh nhân</a></li>
        <li><a>Thống kê</a></li>
    </ul>
@endif
