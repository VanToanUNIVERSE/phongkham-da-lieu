<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Bệnh Nhân | Phòng Khám Da Liễu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
        }
        .time-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 8px;
        }
        .time-slot {
            padding: 8px 4px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
            color: #475569;
        }
        .time-slot:hover:not(.disabled) {
            border-color: #0ea5e9;
            color: #0ea5e9;
            background: #f0f9ff;
        }
        .time-slot.selected {
            background: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
            box-shadow: 0 4px 12px rgba(14,165,233,0.3);
        }
        .time-slot.disabled {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
            opacity: 0.6;
            text-decoration: line-through;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">D</div>
                <h1 class="text-xl font-bold text-slate-800">DaViCare <span class="text-blue-600">Patient</span></h1>
            </div>
            <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại trang chủ
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-700">{{ Auth::user()->full_name }}</p>
                <p class="text-xs text-slate-500">Bệnh nhân</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('patient.profile.edit') }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Chỉnh sửa thông tin">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
                <a href="{{ route('profile.change-password') }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Đổi mật khẩu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Đăng xuất">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Chào mừng trở lại, {{ $patient->full_name }}! 👋</h2>
                <p class="text-slate-500">Theo dõi lịch khám và sức khỏe của bạn tại đây.</p>
            </div>
            <button onclick="openBookingModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Đặt lịch khám mới
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Appointments & Records -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Upcoming Appointments -->
                <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Lịch hẹn sắp tới
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($upcomingAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingAppointments as $apt)
                                    <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/20 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="bg-blue-100 text-blue-600 p-3 rounded-xl font-bold text-center min-w-[3.5rem]">
                                                <div class="text-[10px] uppercase opacity-70">{{ \Carbon\Carbon::parse($apt->date)->format('M') }}</div>
                                                <div class="text-lg leading-none">{{ \Carbon\Carbon::parse($apt->date)->format('d') }}</div>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">BS. {{ $apt->doctor->user->full_name }}</p>
                                                <p class="text-sm text-slate-500">{{ $apt->time }} • Trạng thái: 
                                                    <span class="text-amber-600 font-medium">
                                                        @if($apt->status == 'unconfirmed') Chờ xác nhận
                                                        @elseif($apt->status == 'pending') Chờ khám
                                                        @elseif($apt->status == 'inprocess') Đang khám
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        @if(in_array($apt->status, ['unconfirmed', 'pending']))
                                            <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')">
                                                @csrf
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Hủy lịch">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-400">Bạn chưa có lịch hẹn nào sắp tới.</p>
                                <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 font-bold hover:underline">Đặt lịch ngay →</a>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Medical Records -->
                <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-emerald-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Hồ sơ khám bệnh gần đây
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentRecords->count() > 0)
                            <div class="space-y-6">
                                @foreach($recentRecords as $record)
                                    <div class="border-b border-slate-100 last:border-0 pb-6 last:pb-0">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</p>
                                                <p class="font-bold text-slate-800">Chẩn đoán: {{ $record->diagnosis }}</p>
                                            </div>
                                            <p class="text-sm font-medium text-slate-500">BS. {{ $record->doctor->user->full_name }}</p>
                                        </div>
                                        @if($record->prescription)
                                            <div class="bg-slate-50 rounded-xl p-3 flex flex-wrap gap-2">
                                                @foreach($record->prescription->items as $item)
                                                    <span class="bg-white border border-slate-200 px-3 py-1 rounded-lg text-xs font-medium text-slate-600">
                                                        {{ $item->medicine->name }} x{{ $item->quantity }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-slate-400 py-8">Chưa có hồ sơ khám bệnh nào.</p>
                        @endif
                    </div>
                </section>
            </div>

            <!-- Right Column: Invoices & Stats -->
            <div class="space-y-8">
                <!-- Recent Invoices -->
                <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-amber-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Hóa đơn thanh toán
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentInvoices->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentInvoices as $invoice)
                                    <div class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50 transition-colors">
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">#{{ $invoice->id }} - {{ number_format($invoice->total_amount) }}đ</p>
                                            <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase {{ $invoice->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $invoice->status == 'paid' ? 'Đã thu' : 'Chờ thu' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-slate-400 py-8">Chưa có hóa đơn nào.</p>
                        @endif
                    </div>
                </section>

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
                    <h4 class="font-bold mb-2">Thông tin hỗ trợ</h4>
                    <p class="text-sm text-blue-100 mb-4 font-medium opacity-90">Nếu có bất kỳ thắc mắc nào về kết quả khám, vui lòng liên hệ hotline phòng khám.</p>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-md">
                        <p class="text-xs text-blue-100 uppercase font-black tracking-widest mb-1">Hotline 24/7</p>
                        <p class="text-xl font-bold">1900 123 456</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Booking Modal -->
    <div id="bookingModal" class="modal flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Đặt lịch khám mới
                </h3>
                <button onclick="closeBookingModal()" class="text-slate-400 hover:text-rose-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Họ và tên</label>
                            <input type="text" id="b_name" value="{{ $patient->full_name }}" readonly class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-medium cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Số điện thoại</label>
                            <input type="text" id="b_phone" value="{{ $patient->phone }}" readonly class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-medium cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Chọn bác sĩ</label>
                        <select id="b_doctor" onchange="updateAvailability()" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">-- Chọn bác sĩ (Không bắt buộc) --</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}">BS. {{ $doc->user->full_name }} ({{ $doc->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ngày khám</label>
                        <input type="date" id="b_date" onchange="updateAvailability()" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Giờ khám</label>
                        <div id="b_time_slots" class="time-grid mt-2">
                            <!-- Slots will be rendered here -->
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Triệu chứng (Nếu có)</label>
                        <textarea id="b_note" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Ví dụ: Nổi mẩn đỏ, ngứa..."></textarea>
                    </div>
                </div>

                <div id="modalMsg" class="mt-4 hidden p-3 rounded-lg text-sm font-medium"></div>

                <div class="mt-8">
                    <button id="submitBookingBtn" onclick="submitBooking()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-2">
                        <span>Xác nhận đặt lịch</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const ALL_SLOTS = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
        let selectedTime = null;

        function openBookingModal() {
            document.getElementById('bookingModal').style.display = 'flex';
            updateAvailability();
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }

        async function updateAvailability() {
            const docId = document.getElementById('b_doctor').value;
            const date = document.getElementById('b_date').value;
            const container = document.getElementById('b_time_slots');

            if (!date) return;

            container.innerHTML = '<p class="text-xs text-slate-400 col-span-full text-center py-2 italic">Đang tải lịch trống...</p>';

            try {
                // Fetch booked slots
                const url = `{{ route('booked.slots') }}?date=${date}${docId ? '&doctor_id='+docId : ''}`;
                const response = await fetch(url);
                const res = await response.json();
                const bookedTimes = res.booked_times || [];

                container.innerHTML = '';
                ALL_SLOTS.forEach(slot => {
                    const isBooked = bookedTimes.includes(slot);
                    const div = document.createElement('div');
                    div.className = `time-slot ${isBooked ? 'disabled' : ''} ${selectedTime === slot ? 'selected' : ''}`;
                    div.textContent = slot;
                    if (!isBooked) {
                        div.onclick = () => {
                            selectedTime = slot;
                            updateAvailabilityUI();
                        };
                    }
                    container.appendChild(div);
                });
            } catch (e) {
                container.innerHTML = '<p class="text-xs text-rose-500 col-span-full text-center">Lỗi tải lịch trống.</p>';
            }
        }

        function updateAvailabilityUI() {
            document.querySelectorAll('.time-slot').forEach(el => {
                el.classList.remove('selected');
                if (el.textContent === selectedTime) el.classList.add('selected');
            });
        }

        async function submitBooking() {
            const btn = document.getElementById('submitBookingBtn');
            const msg = document.getElementById('modalMsg');
            
            const payload = {
                full_name: document.getElementById('b_name').value,
                phone: document.getElementById('b_phone').value,
                date: document.getElementById('b_date').value,
                time: selectedTime,
                doctor_id: document.getElementById('b_doctor').value,
                note: document.getElementById('b_note').value,
                gender: {{ $patient->gender ?? 'null' }}
            };

            if (!payload.time) {
                showMsg('Vui lòng chọn giờ khám!', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            try {
                const res = await fetch('{{ route("public.booking") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                }).then(r => r.json());

                if (res.status === 'success') {
                    showMsg('Đặt lịch thành công! Trang sẽ tải lại sau 2 giây...', 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    const error = res.errors ? Object.values(res.errors).flat().join('<br>') : res.message;
                    showMsg(error || 'Có lỗi xảy ra', 'error');
                    btn.disabled = false;
                    btn.innerHTML = 'Xác nhận đặt lịch';
                }
            } catch (e) {
                showMsg('Lỗi kết nối máy chủ.', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Xác nhận đặt lịch';
            }
        }

        function showMsg(text, type) {
            const msg = document.getElementById('modalMsg');
            msg.innerHTML = text;
            msg.className = `mt-4 p-3 rounded-lg text-sm font-medium ${type === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}`;
            msg.classList.remove('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeBookingModal();
            }
        }
    </script>
</body>
</html>
