<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thông tin cá nhân | DaViCare Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">D</div>
                <h1 class="text-xl font-bold text-slate-800">DaViCare <span class="text-blue-600">Patient</span></h1>
            </div>
            <a href="{{ route('patient.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại Dashboard
            </a>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Đăng xuất">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-12">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Thông tin cá nhân</h2>
            <p class="text-slate-500">Quản lý thông tin hồ sơ và ảnh đại diện của bạn.</p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            @csrf
            <div class="p-8 space-y-8">
                <!-- Avatar Section -->
                <div class="flex flex-col items-center gap-6 pb-8 border-b border-slate-100">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-3xl bg-slate-100 overflow-hidden border-4 border-white shadow-xl">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" id="avatarPreview" class="w-full h-full object-cover">
                            @else
                                <div id="avatarPlaceholder" class="w-full h-full flex items-center justify-center text-4xl font-bold text-blue-600 bg-blue-50">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <img id="avatarPreview" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <label for="avatarInput" class="absolute -bottom-2 -right-2 bg-blue-600 text-white p-2.5 rounded-2xl shadow-lg cursor-pointer hover:bg-blue-700 transition-all hover:scale-110 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                        </label>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-slate-700">Ảnh đại diện</p>
                        <p class="text-xs text-slate-400">JPG, PNG hoặc GIF. Tối đa 2MB.</p>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Họ và tên</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Số điện thoại</label>
                        <input type="text" value="{{ $user->username }}" disabled class="w-full px-5 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl text-slate-500 font-semibold cursor-not-allowed">
                        <p class="text-[10px] text-slate-400 ml-1 italic">(Số điện thoại dùng làm tên đăng nhập và không thể thay đổi)</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Năm sinh</label>
                        <input type="number" name="birth_year" value="{{ old('birth_year', $patient->birth_year) }}" min="1900" max="{{ date('Y') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Giới tính</label>
                        <div class="flex gap-4 p-1 bg-slate-50 rounded-2xl border border-slate-200">
                            <label class="flex-1">
                                <input type="radio" name="gender" value="1" {{ old('gender', $patient->gender) == 1 ? 'checked' : '' }} class="hidden peer">
                                <div class="text-center py-2.5 rounded-xl cursor-pointer transition-all font-bold text-sm text-slate-400 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">Nam</div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="gender" value="0" {{ old('gender', $patient->gender) == 0 ? 'checked' : '' }} class="hidden peer">
                                <div class="text-center py-2.5 rounded-xl cursor-pointer transition-all font-bold text-sm text-slate-400 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">Nữ</div>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Địa chỉ</label>
                        <textarea name="address" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700">{{ old('address', $patient->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('patient.dashboard') }}" class="px-8 py-3.5 rounded-2xl font-bold text-slate-500 hover:bg-slate-200 transition-all">Hủy bỏ</a>
                <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 active:translate-y-0">Lưu thay đổi</button>
            </div>
        </form>
    </main>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    const placeholder = document.getElementById('avatarPlaceholder');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
