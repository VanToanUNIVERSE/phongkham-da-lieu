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
            <form action="{{ route('public.lookup.search') }}" method="POST" class="flex flex-col md:flex-row gap-6 items-end justify-center">
                @csrf
                <div class="w-full md:w-1/2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Số điện thoại đăng ký khám</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           placeholder="Ví dụ: 0901234567"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-lg text-center font-medium tracking-wide">
                </div>
                
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] h-[52px]">
                    🔍 Tra cứu ngay
                </button>
            </form>

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl text-sm font-medium flex items-center justify-center gap-3 max-w-2xl mx-auto">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Results Section --}}
        @if(isset($patient))
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-2">Hồ sơ bệnh nhân: {{ $patient->full_name }}</h2>
                    <p class="text-blue-600 font-bold bg-blue-50 inline-block px-4 py-1.5 rounded-full border border-blue-100">Lịch sử khám bệnh</p>
                </div>

                @if($records && $records->count() > 0)
                    @foreach($records as $record)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 relative">
                            <!-- Header của mỗi Records -->
                            <div class="bg-gray-50/80 px-8 py-5 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm text-center min-w-[5rem]">
                                        <div class="text-xs font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($record->created_at)->translatedFormat('F') }}</div>
                                        <div class="text-2xl font-black text-blue-600 leading-none">{{ \Carbon\Carbon::parse($record->created_at)->format('d') }}</div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium mb-1">Thời gian khám</p>
                                        <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($record->created_at)->format('H:i - d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-green-200">
                                        Đã hoàn thành
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Nội dung -->
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <!-- Cột Trái: Chẩn đoán & Hóa đơn -->
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                Bác sĩ phụ trách
                                            </h3>
                                            <p class="text-gray-800 font-bold bg-gray-50 px-4 py-2 rounded-lg border border-gray-100 inline-block">
                                                BS. {{ $record->doctor->user->full_name ?? 'N/A' }}
                                            </p>
                                        </div>

                                        <div>
                                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Chẩn đoán bệnh
                                            </h3>
                                            <p class="text-gray-800 p-4 bg-purple-50/50 rounded-2xl border border-purple-100 font-medium">
                                                {{ $record->diagnosis ?? 'Không có thông tin chẩn đoán' }}
                                            </p>
                                        </div>

                                        @if($record->examination_results)
                                            <div>
                                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Kết quả lâm sàng</h3>
                                                <p class="text-gray-600 whitespace-pre-line text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $record->examination_results }}</p>
                                            </div>
                                        @endif

                                        <!-- Hóa đơn -->
                                        <div class="mt-8 pt-6 border-t border-gray-100">
                                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                Thanh toán
                                            </h3>
                                            @if($record->invoice)
                                                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                                                    <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-3 text-sm">
                                                        <span class="text-gray-500 font-medium">Mã hóa đơn:</span>
                                                        <span class="font-bold">#{{ $record->invoice->id }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center mb-4">
                                                        <span class="text-gray-500 font-medium">Tổng thanh toán:</span>
                                                        <span class="text-xl font-black text-rose-600">{{ number_format($record->invoice->total_amount, 0, ',', '.') }}đ</span>
                                                    </div>
                                                    <div>
                                                        @if($record->invoice->status == 'paid')
                                                            <div class="w-full text-center bg-emerald-50 text-emerald-700 py-2 rounded-xl font-bold text-sm border border-emerald-100">Đã thanh toán</div>
                                                        @elseif($record->invoice->status == 'pending')
                                                            <div class="w-full text-center bg-amber-50 text-amber-700 py-2 rounded-xl font-bold text-sm border border-amber-100">Chưa thanh toán</div>
                                                        @else
                                                            <div class="w-full text-center bg-gray-50 text-gray-600 py-2 rounded-xl font-bold text-sm border border-gray-200">Đã hủy</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                                                    Chưa có hóa đơn cho lần khám này.
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Cột Phải: Đơn thuốc -->
                                    <div class="bg-blue-50/30 rounded-3xl p-6 border border-blue-50/50">
                                        <h3 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            Đơn thuốc bác sĩ kê
                                        </h3>
                                        
                                        @if($record->prescription && $record->prescription->items->count() > 0)
                                            <div class="space-y-3">
                                                @foreach($record->prescription->items as $item)
                                                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl shadow-sm border border-white hover:border-blue-200 transition-colors">
                                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black flex-shrink-0 text-sm mt-0.5">
                                                            {{ $loop->iteration }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex justify-between items-start">
                                                                <p class="font-bold text-gray-800">{{ $item->medicine->name }}</p>
                                                                <span class="font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-xs ml-2 whitespace-nowrap">x{{ $item->quantity }}</span>
                                                            </div>
                                                            <div class="mt-1 space-y-1">
                                                                <p class="text-xs text-gray-500 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Liều lượng: {{ $item->dosage }}</p>
                                                                <p class="text-xs text-gray-500 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Cách dùng: {{ $item->usage }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                
                                                @if($record->doctor_note)
                                                    <div class="mt-6 p-5 border-t border-dashed border-blue-200 bg-white rounded-2xl relative">
                                                        <div class="absolute -top-3 left-4 bg-blue-100 text-blue-700 text-[10px] font-black uppercase px-2 py-1 rounded">Lời dặn</div>
                                                        <p class="text-sm text-gray-700 italic mt-2 leading-relaxed">
                                                            "{{ $record->doctor_note }}"
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="h-40 flex flex-col items-center justify-center text-center bg-white rounded-2xl border border-dashed border-gray-200">
                                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                <p class="text-gray-400 text-sm font-medium">Không có đơn thuốc<br>cho lần khám này.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-gray-100">
                        <div class="text-6xl mb-4 opacity-50">📁</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Hồ sơ trống</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Chưa tìm thấy bất kỳ lịch sử khám bệnh nào gắn với số điện thoại này. Nếu bạn vừa khám xong, vui lòng đợi lễ tân cập nhật hệ thống.</p>
                    </div>
                @endif
                
                <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-2xl flex items-center justify-between gap-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 text-white/5 text-9xl">🩺</div>
                    <div class="relative z-10 w-full text-center">
                        <h4 class="text-xl font-bold mb-1 line-clamp-1">Phòng Khám Da Liễu Hutech</h4>
                        <p class="text-gray-400 text-sm">Cảm ơn bạn đã tin tưởng dịch vụ của chúng tôi.</p>
                    </div>
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
