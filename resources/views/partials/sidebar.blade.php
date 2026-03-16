@if (auth()->check() && auth()->user()->role->name == 'Admin')
    <aside class="w-72 bg-slate-950 text-slate-400 flex-shrink-0 flex flex-col h-screen transition-all duration-300 border-r border-slate-900">
        <!-- Sidebar Header -->
        <div class="px-6 py-8">
             <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-white text-lg font-bold tracking-tight block">DAVI CLINIC</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Management System</span>
                </div>
             </div>
        </div>

        <nav class="flex-1 px-4 space-y-8 overflow-y-auto custom-scrollbar pb-10">
            <!-- GROUP: DASHBOARD -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em] mb-4">Hệ thống</p>
                <div class="space-y-1">
                    <a href="{{ route('adminDashboard') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('adminDashboard') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span class="font-semibold text-sm">Quản lý người dùng</span>
                    </a>
                </div>
            </div>

            <!-- GROUP: CORE BUSINESS -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em] mb-4">Nghiệp vụ chuyên môn</p>
                <div class="space-y-1">
                    <a href="{{ route('patients.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('patients.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span class="font-semibold text-sm">Quản lý bệnh nhân</span>
                    </a>
                    <a href="{{ route('appointments.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('appointments.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="font-semibold text-sm">Quản lý lịch khám</span>
                    </a>
                    <a href="{{ route('medical_records.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('medical_records.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-semibold text-sm">Hồ sơ bệnh án</span>
                    </a>
                </div>
            </div>

            <!-- GROUP: PHARMACY -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em] mb-4">Dược phẩm & Thuốc</p>
                <div class="space-y-1">
                    <a href="{{ route('medicines.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('medicines.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        <span class="font-semibold text-sm">Kho thuốc</span>
                    </a>
                    <a href="{{ route('prescriptions.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('prescriptions.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span class="font-semibold text-sm">Đơn thuốc</span>
                    </a>
                    <a href="{{ route('pharmacy.transactions') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pharmacy.transactions') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        <span class="font-semibold text-sm">Lịch sử xuất nhập kho</span>
                    </a>
                </div>
            </div>

            <!-- GROUP: FINANCE -->
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em] mb-4">Tài chính & Báo cáo</p>
                <div class="space-y-1">
                    <a href="{{ route('invoices.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('invoices.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="font-semibold text-sm">Hóa đơn</span>
                    </a>
                    <a href="{{ route('reports.index') }}" 
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-900 hover:text-white' }}">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span class="font-semibold text-sm">Thống kê doanh thu</span>
                    </a>
                </div>
            </div>
        </nav>
        

    </aside>
@elseif (auth()->check() && in_array(auth()->user()->role->name, ['Lễ tân', 'Lễ tân']))
    <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col items-center">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-center border-b border-slate-800 w-full mb-6 mt-4">
             <span class="text-white text-lg font-semibold tracking-wider">LỄ TÂN PANEL</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 w-full">
            <a href="{{ route('reception.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reception.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Trang chủ
            </a>

            <a href="{{ route('reception.appointments') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reception.appointments') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Tiếp nhận bệnh nhân
            </a>

            <a href="{{ route('reception.patients') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reception.patients') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Quản lý bệnh nhân
            </a>

            <a href="{{ route('reception.invoices') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reception.invoices') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Quản lý hóa đơn
            </a>
        </nav>
        
        <div class="p-4 w-full border-t border-slate-800 text-xs text-center text-slate-500">
            Dự án Phòng Khám Da Liễu<br>&copy; 2026
        </div>
    </aside>
@elseif (auth()->check() && auth()->user()->role->name == 'Bác sĩ')
    <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col items-center">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-center border-b border-slate-800 w-full mb-6 mt-4">
             <span class="text-white text-lg font-semibold tracking-wider">BÁC SĨ PANEL</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 w-full">
            <a href="{{ route('doctor.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('doctor.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Trang chủ
            </a>

            <a href="{{ route('doctor.appointments') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('doctor.appointments') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                Lịch khám hôm nay
            </a>

            <a href="{{ route('doctor.medical_records') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('doctor.medical_records') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Quản lý hồ sơ
            </a>

            <a href="{{ route('doctor.prescriptions') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('doctor.prescriptions') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Quản lý đơn thuốc
            </a>
        </nav>
        
        <div class="p-4 w-full border-t border-slate-800 text-xs text-center text-slate-500">
            Dự án Phòng Khám Da Liễu<br>&copy; 2026
        </div>
    </aside>
@elseif (auth()->check() && auth()->user()->role->name == 'Nhân viên phát thuốc')
    <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col items-center">
        <div class="h-16 flex items-center justify-center border-b border-slate-800 w-full mb-6 mt-4">
             <span class="text-white text-lg font-semibold tracking-wider">DƯỢC SĨ PANEL</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 w-full">
            <a href="{{ route('pharmacy.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pharmacy.dashboard') ? 'bg-violet-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Trang chủ
            </a>

            <a href="{{ route('pharmacy.dispense') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pharmacy.dispense') ? 'bg-violet-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Phát thuốc
            </a>

            <a href="{{ route('pharmacy.inventory') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pharmacy.inventory') ? 'bg-violet-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                Kho thuốc
            </a>
        </nav>

        <div class="p-4 w-full border-t border-slate-800 text-xs text-center text-slate-500">
            Dự án Phòng Khám Da Liễu<br>&copy; 2026
        </div>
    </aside>
@endif
