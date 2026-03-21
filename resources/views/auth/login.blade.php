<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng khám Da liễu DaVi - Đăng nhập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#d8f1e9] bg-[radial-gradient(circle_at_10%_20%,rgba(200,240,230,0.8)_0%,transparent_40%),radial-gradient(circle_at_80%_80%,rgba(180,225,215,0.8)_0%,transparent_40%),radial-gradient(circle_at_90%_10%,rgba(210,245,235,0.6)_0%,transparent_30%),radial-gradient(circle_at_20%_90%,rgba(190,230,220,0.9)_0%,transparent_50%)] relative z-0 min-h-screen flex items-center justify-center font-sans py-12 overflow-hidden">
    
    <!-- Abstract Tailwind Rounded Color Shapes -->
    <div class="absolute top-[20%] -left-[5%] w-[400px] h-[400px] bg-gradient-to-br from-[#a8e6cf] to-[#dcedc1] rounded-full blur-[80px] -z-10 opacity-70"></div>
    <div class="absolute bottom-[15%] right-[5%] w-[350px] h-[350px] bg-gradient-to-br from-[#a2ded0] to-[#cbd5e1] rounded-full blur-[100px] -z-10 opacity-80"></div>
    <div class="absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] w-[500px] h-[500px] bg-gradient-to-br from-[#e2f8f2] to-[#b5e4d9] rounded-full blur-[120px] -z-10 opacity-60"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        <!-- Floating Card -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-white/40 border-b-0 border-r-0">
            <div class="p-8 pb-10">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center p-2 mb-4 bg-white/50 rounded-full shadow-inner ring-1 ring-black/5">
                        <img src="{{ asset('images/logophongkham.png') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full border-2 border-white shadow-sm">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Phòng khám Da liễu DaVi</h2>
                    <p class="text-xs text-gray-500 mt-2 font-medium">Hệ thống quản lý phòng khám da liễu hiện đại</p>
                </div>
                
                @if($errors->any())
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-md animate-pulse">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('postLogin') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-600 mb-1 ml-1 uppercase tracking-wider">Tên Đăng Nhập / SĐT / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" 
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors sm:text-sm" 
                                placeholder="nguyenvana hoặc 0901234567" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-1 ml-1 uppercase tracking-wider">Mật Khẩu</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors sm:text-sm" 
                                placeholder="••••••••" required>
                            
                            <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5 eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                <svg class="w-5 h-5 eye-open hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center mt-6">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <div class="pt-4 pb-2">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#007bbf] hover:bg-[#0069a3] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Đăng nhập vào Hệ thống
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const eyeClosed = btn.querySelector('.eye-closed');
            const eyeOpen = btn.querySelector('.eye-open');

            if (input.type === 'password') {
                input.type = 'text';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeClosed.classList.remove('hidden');
                eyeOpen.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
