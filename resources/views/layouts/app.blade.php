<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (Auth::check())
            Admin - {{ Auth::user()->role->name }}
        @else
            Phòng khám Da liễu
        @endif
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased h-screen overflow-hidden flex">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Column --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- Header --}}
        <header class="bg-white shadow smflex items-center justify-between px-6 py-4 border-b border-gray-200 z-10">
            <div class="flex items-center gap-4">
               <img src="{{ asset('images/logophongkham.png') }}" class="h-10 w-auto" alt="Logo">
               <h1 class="text-xl font-bold text-gray-800 hidden md:block">Phòng khám Da liễu DaVi</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <span class="text-sm font-medium text-gray-600">
                    Xin chào, {{ Auth::user()->full_name ?? 'Admin' }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </header>

        {{-- Scrollable Content Area --}}
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        
        {{-- Shared Modals --}}
        @include('partials.confirm_delete_modal')
        
    </div>
</body>
</html>
