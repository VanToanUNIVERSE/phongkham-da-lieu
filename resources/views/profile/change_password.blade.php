<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu | DaViCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-8">
            <div class="mb-8 flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold mb-4">D</div>
                <h1 class="text-2xl font-bold text-slate-800 text-center">Đổi mật khẩu</h1>
                <p class="text-slate-500 text-center text-sm mt-1">Vui lòng nhập mật khẩu mới để bảo mật tài khoản.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('current_password') border-rose-400 @enderror"
                        placeholder="••••••••">
                    @error('current_password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('password') border-rose-400 @enderror"
                        placeholder="Ít nhất 8 ký tự">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                        placeholder="Nhập lại mật khẩu mới">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                        Lưu mật khẩu
                    </button>
                    <a href="javascript:history.back()" class="block w-full text-center text-sm font-medium text-slate-400 hover:text-slate-600 mt-4 transition-colors">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
