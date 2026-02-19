<form  method="POST" action="{{ route("postLogin") }}">
    @csrf
    <input type="text" name="username" placeholder="Nhập tên đăng nhập">
    <input type="password" name="password" placeholder="Nhập mật khẩu">
    <button type="submit">Đăng nhập</button>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color:red">{{ $error }}</p>
        @endforeach
    @endif


</form>
