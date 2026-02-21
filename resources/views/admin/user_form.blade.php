<h2 id="formTitle">Thêm người dùng mới</h2>
<form id="userForm" method="POST" action="{{ route('users.store') }}">
    @csrf
    <input type="hidden" id="formMethod" name="_method" value="POST">
    <input id="f_username" type="text" name="username" placeholder="Nhập tên đăng nhập" value="{{ old('username') }}">

    @error('username')
        <div style="color:red">{{ $message }}</div>
    @enderror
    <input id="f_password" type="password" name="password" placeholder="Nhập mật khẩu">

    @error('password')
        <div style="color:red">{{ $message }}</div>
    @enderror
    <input id="f_fullname" type="text" name="full_name" placeholder="Nhập họ tên" value="{{ old('full_name') }}">

    @error('full_name')
        <div style="color:red">{{ $message }}</div>
    @enderror
    <label>
        <input id="g_male" type="radio" name="gender" value="1" {{ old('gender') == '1' ? 'checked' : '' }}>
        Nam
    </label>

    <label>
        <input id="g_female" type="radio" name="gender" value="0" {{ old('gender') == '0' ? 'checked' : '' }}>
        Nữ
    </label>

    @error('gender')
        <div style="color:red">{{ $message }}</div>
    @enderror
    <input id="f_birth" type="number" name="birth_year" placeholder="Nhập năm sinh">
    <input id="f_phone" type="number" name="phone" placeholder="Nhập số điện thoại">
    <select id="f_status" name="status">
        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Còn làm</option>
        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Đã nghỉ</option>
    </select>

    @error('status')
        <div style="color:red">{{ $message }}</div>
    @enderror
    <select id="f_role" name="role_id">
        <option value="1">Admin</option>
        <option value="2">Bác sĩ</option>
        <option value="3">Lễ tân</option>
        <option value="4">Nhân viên phát thuốc</option>
    </select>
    <button id="submitBtn" type="submit">Thêm</button>
    @if (session('success')) {
        <div style="color:green; border:1px solid green; padding:10px; margin-bottom:10px;">
            {{ session('success') }}
        </div>
        }
    @endif
</form>
