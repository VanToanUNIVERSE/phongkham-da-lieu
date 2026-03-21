<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (Auth::check())
            {{ Auth::user()->role->name }} - @yield('pageTitle', 'Dashboard')
        @else
            Phòng khám Da liễu
        @endif
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Specialized for sidebar */
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #020617;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1e293b;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased h-screen overflow-hidden flex">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Column --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gradient-to-br from-[#f8fafc] via-[#f1f5f9] to-[#e2e8f0]">
        
        {{-- Header --}}
        <header class="bg-white/80 backdrop-blur-md sticky top-0 border-b border-slate-200 z-30 px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="md:hidden">
                    {{-- Mobile menu button could go here --}}
                </div>
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest hidden md:block">
                    @yield('title', 'QUẢN LÝ HỆ THỐNG')
                </h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 pr-6 border-r border-slate-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->full_name ?? 'Administrator' }}</p>
                        <p class="text-[10px] font-medium text-blue-600 uppercase tracking-tighter">{{ Auth::user()->role->name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center text-blue-600 font-bold">
                        {{ substr(Auth::user()->full_name ?? 'A', 0, 1) }}
                    </div>
                </div>

                <a href="{{ route('profile.change-password') }}" class="group flex items-center gap-2 text-slate-400 hover:text-blue-600 transition-colors font-bold text-xs uppercase tracking-widest mr-4 pr-4 border-r border-slate-200">
                    <svg class="h-4 w-4 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span>Đổi mật khẩu</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="group flex items-center gap-2 text-slate-400 hover:text-red-600 transition-colors font-bold text-xs uppercase tracking-widest">
                        <span>Đăng xuất</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-10">
            <div class="max-w-7xl mx-auto">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
        {{-- Shared Modals --}}
        @include('partials.confirm_delete_modal')
        @include('partials.notifications')
        
    </div>
</body>
</html>
