<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu kết quả khám bệnh | Phòng Khám Da Liễu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-gradient {
            background: linear-gradient(135deg, #f0f9ff 0%, #e8f4fd 40%, #eef2ff 100%);
        }
    </style>
</head>
<body class="bg-gradient min-height-screen">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-600 font-bold mb-6 hover:translate-x-[-4px] transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại trang chủ
            </a>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-3">Tra cứu kết quả khám</h1>
            <p class="text-gray-600">Nhập thông tin bệnh nhân để xem chẩn đoán và đơn thuốc</p>
        </div>

        {{-- Lookup Form --}}
        <div class="glass rounded-3xl p-8 shadow-2xl border border-white mb-10">
            <form action="{{ route('public.lookup.search') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Số điện thoại</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           placeholder="Ví dụ: 0901234567"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mã lịch hẹn (ID)</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                           placeholder="Mã nhận được khi đặt lịch"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                    🔍 Tra cứu ngay
                </button>
            </form>

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Results Section --}}
        @if(isset($appointment))
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                {{-- Patient Info --}}
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                    <div class="flex flex-wrap justify-between items-start gap-4 border-bottom border-gray-100 pb-6 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $appointment->patient->full_name }}</h2>
                            <p class="text-blue-600 font-medium">Bệnh nhân #{{ $appointment->patient_id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Ngày khám</p>
                            <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} lúc {{ $appointment->time }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Kết luận bác sĩ</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-bold text-gray-700 mb-1">Bác sĩ phụ trách</p>
                                    <p class="text-gray-600 font-medium">BS. {{ $appointment->doctor->user->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700 mb-1">Chẩn đoán</p>
                                    <p class="text-gray-800 p-4 bg-blue-50 rounded-2xl border border-blue-100 italic">
                                        "{{ $appointment->medicalRecord->diagnosis ?? 'Đang cập nhật...' }}"
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700 mb-1">Kết quả lâm sàng</p>
                                    <p class="text-gray-600 whitespace-pre-line">{{ $appointment->medicalRecord->examination_results ?? 'Chưa có thông tin' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Đơn thuốc của bạn</h3>
                            @if($appointment->medicalRecord && $appointment->medicalRecord->prescription && $appointment->medicalRecord->prescription->items->count() > 0)
                                <div class="space-y-3">
                                    @foreach($appointment->medicalRecord->prescription->items as $item)
                                        <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-blue-200 transition-colors">
                                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold text-gray-800">{{ $item->medicine->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->dosage }} / {{ $item->usage }}</p>
                                            </div>
                                            <div class="text-right font-bold text-gray-400">
                                                x{{ $item->quantity }}
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="mt-4 p-4 border-t border-dashed border-gray-200 items-baseline flex flex-col">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Ghi chú từ bác sĩ</p>
                                        <p class="text-sm text-gray-600 italic mt-1">
                                            {{ $appointment->medicalRecord->doctor_note ?? 'Uống thuốc đúng liều lượng và tái khám theo lịch.' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-2xl p-8 text-center">
                                    <p class="text-gray-400 text-sm">Bác sĩ chưa kê đơn thuốc hoặc ca khám không cần dùng thuốc.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl shadow-blue-200 flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h4 class="text-xl font-bold mb-1">Cảm ơn bạn đã tin tưởng!</h4>
                        <p class="text-blue-100 text-sm">Mọi thắc mắc vui lòng liên hệ hotline: 0901 234 567</p>
                    </div>
                    <button onclick="window.print()" class="bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-6 rounded-xl backdrop-blur-md transition-all">
                        🖨️ In kết quả
                    </button>
                </div>
            </div>
        @elseif(!session('error') && !old('phone'))
            {{-- Placeholder when no search yet --}}
            <div class="text-center py-20 opacity-30">
                <div class="text-6xl mb-4">🩺</div>
                <p class="text-xl font-medium">Sẵn sàng tra cứu</p>
            </div>
        @endif
    </div>
</body>
</html>
