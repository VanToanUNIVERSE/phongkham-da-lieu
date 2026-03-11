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
                        <label for="username" class="block text-xs font-semibold text-gray-600 mb-1 ml-1 uppercase tracking-wider">Tên Đăng Nhập Hoặc Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" 
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors sm:text-sm" 
                                placeholder="nguyenvana@davicare.com" required autofocus>
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
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors sm:text-sm" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
                                Quên mật khẩu?
                            </a>
                        </div>
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
</body>
</html>
