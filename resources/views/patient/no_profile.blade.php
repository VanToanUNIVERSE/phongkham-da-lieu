<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo | Phòng Khám Da Liễu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 text-center border border-slate-100">
        <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Chưa tìm thấy hồ sơ!</h2>
        <p class="text-slate-500 mb-8 font-medium">Tài khoản này hiện chưa được liên kết với bất kỳ hồ sơ bệnh nhân nào trong hệ thống. Vui lòng liên hệ với lễ tân tại phòng khám để được hỗ trợ.</p>
        
        <div class="space-y-3">
            <a href="tel:1900123456" class="block w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Gọi hỗ trợ ngay
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 text-slate-500 font-bold hover:text-slate-800 transition-colors">
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</body>
</html>
