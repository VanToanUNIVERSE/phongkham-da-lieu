<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân | DaViCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-8">
            {{-- Header --}}
            <div class="mb-8 flex flex-col items-center">
                {{-- Avatar preview --}}
                <div class="relative mb-4 group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                    <div class="w-24 h-24 rounded-full bg-blue-100 border-4 border-white shadow-md flex items-center justify-center text-blue-600 font-bold text-3xl overflow-hidden">
                        @if($user->avatar)
                            <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            <span id="avatarInitial">{{ substr($user->full_name ?? 'U', 0, 1) }}</span>
                            <img id="avatarPreview" class="w-full h-full object-cover hidden" alt="Avatar">
                        @endif
                    </div>
                    <div class="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Hồ sơ cá nhân</h1>
                <p class="text-slate-500 text-sm mt-1">Cập nhật thông tin tài khoản của bạn.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                {{-- Hidden file input --}}
                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">

                {{-- Full Name --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('full_name') border-rose-400 @enderror">
                    @error('full_name')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                        placeholder="0901234567">
                </div>

                {{-- Role (readonly) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Vai trò</label>
                    <input type="text" value="{{ $user->role->name }}" readonly
                        class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-400 cursor-not-allowed">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                        Lưu hồ sơ
                    </button>
                    <a href="javascript:history.back()" class="block w-full text-center text-sm font-medium text-slate-400 hover:text-slate-600 mt-4 transition-colors">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    const initial = document.getElementById('avatarInitial');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (initial) initial.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
