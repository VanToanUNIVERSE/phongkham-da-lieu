@if (auth()->user()->role->name == 'Admin')
    <ul>
        <li><a href="{{ route('adminDashboard') }}">Quản lý người dùng</a></li>
        <li><a href="{{ route('patients.index') }}">Quản lý bệnh nhân</a></li>
        <li><a href="{{ route('appointments.index') }}">Quản lý lịch khám</a></li>
        <li><a href="{{ route('medical_records.index') }}">Quản lý bệnh án</a></li>
        <li><a href="{{ route('medicines.index') }}">Quản lý thuốc</a></li>
        <li><a href="{{ route('prescriptions.index') }}">Quản lý đơn thuốc</a></li>
        <li><a>Thống kê</a></li>
    </ul>
@endif
