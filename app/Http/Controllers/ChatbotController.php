<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'Chưa cấu hình API Key cho chatbot.'], 500);
        }

        $history = $request->input('history', []);
        
        // Thêm thông tin bác sĩ vào Context
        $doctorsQuery = \App\Models\Doctor::with('user')->get();
        $doctorListDesc = "";
        if ($doctorsQuery->count() > 0) {
            $doctorListDesc = "\n- Danh sách bác sĩ hiện tại:\n";
            foreach($doctorsQuery as $doc) {
                $name = $doc->user->full_name ?? 'Chưa rõ tên';
                $spec = $doc->specialty ?? 'Da Liễu tổng quát';
                $doctorListDesc .= "  + Bác sĩ $name (Chuyên khoa: $spec)\n";
            }
        }

        // Thông tin thời gian thực
        $now = \Carbon\Carbon::now();
        $currentTimeInfo = "\n- Thời gian hiện tại của hệ thống: " . $now->format('H:i, d/m/Y') . " (Thứ " . ($now->dayOfWeek == 0 ? "Chủ Nhật" : $now->dayOfWeek + 1) . ")\n";

        // Thông tin cho bot (System prompt/Context)
        $systemPrompt = "Bạn là nhân viên tư vấn nhiệt tình, chuyên nghiệp của Phòng Khám Da Liễu chuyên sâu." . $currentTimeInfo . "
Thông tin tham khảo:
- Địa chỉ: 123 Đường Da Liễu, Phường Bình Thạnh, TP. Hồ Chí Minh
- Hotline/Điện thoại: (028) 3800 1234 hoặc 0901 234 567
- Giờ làm việc: Thứ Hai - Thứ Sáu (8:00 - 17:00), Thứ Bảy (8:00 - 12:00), Chủ Nhật nghỉ.
- Dịch vụ chính: Trị mụn trứng cá, Viêm da cơ địa, Nấm da, Vảy nến, Da liễu trẻ em..." . $doctorListDesc . "
- Quy trình đặt lịch: Bạn CÓ KHẢ NĂNG trực tiếp ĐẶT LỊCH cho khách hàng. Hãy hỏi thông tin khách: Họ tên, Số điện thoại (bắt buộc). Ngày khám (YYYY-MM-DD), Giờ khám (ví dụ 08:00, 14:30), Bác sĩ (có thể trống, hệ thống sẽ tự xếp bác sĩ trống). 
- QUAN TRỌNG: Nếu khách nói 'ngày mai', 'thứ hai tuần tới', v.v. hãy tự tính toán dựa trên 'Thời gian hiện tại của hệ thống' ở trên để ra đúng ngày YYYY-MM-DD khi gọi hàm `book_appointment`.
- Khi người dùng cung cấp đủ thông tin, hãy gọi hàm `book_appointment`. Không trả lời chung chung nữa, hãy GỌI HÀM.
  
Nhiệm vụ: Trả lời ngắn gọn, thân thiện (dùng đại từ 'Dạ', 'Chào bạn', 'phòng khám mình'), giới thiệu đúng tên bác sĩ đang làm việc nếu khách hỏi. Chủ động đề nghị đặt lịch giúp khách. Khi đã có kết quả trả về từ hàm đặt lịch, hãy thông báo rõ mã khám hoặc lỗi nếu có.";

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'contents' => $history,
            'tools' => [
                [
                    'functionDeclarations' => [
                        [
                            'name' => 'book_appointment',
                            'description' => 'Gọi hàm này để tạo lịch hẹn vào hệ thống khi đã thu thập đủ Họ tên, Số điện thoại, Ngày, Giờ từ khách.',
                            'parameters' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'full_name' => ['type' => 'STRING', 'description' => 'Họ và tên của bệnh nhân'],
                                    'phone' => ['type' => 'STRING', 'description' => 'Số điện thoại của bệnh nhân (VD: 0912345678)'],
                                    'date' => ['type' => 'STRING', 'description' => 'Ngày khám theo định dạng YYYY-MM-DD (VD: 2026-03-25)'],
                                    'time' => ['type' => 'STRING', 'description' => 'Giờ khám (VD: 08:00, 08:30, 09:00, 09:30, 10:00, 10:30, 11:00, 11:30, 13:30, 14:00, 14:30, 15:00, 15:30, 16:00, 16:30)'],
                                    'doctor_name' => ['type' => 'STRING', 'description' => 'Tên bác sĩ bệnh nhân chọn. Có thể để trống nếu bệnh nhân chọn Bất kỳ.']
                                ],
                                'required' => ['full_name', 'phone', 'date', 'time']
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 800
            ]
        ];

        try {
            $response = Http::withoutVerifying()->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Trường hợp AI gọi hàm (Function Calling)
                if (isset($data['candidates'][0]['content']['parts'][0]['functionCall'])) {
                    $funcCall = $data['candidates'][0]['content']['parts'][0]['functionCall'];
                    $name = $funcCall['name'];
                    $args = $funcCall['args'];

                    if ($name === 'book_appointment') {
                        $fullName = $args['full_name'] ?? '';
                        $phone = $args['phone'] ?? '';
                        $date = $args['date'] ?? '';
                        $time = $args['time'] ?? '';
                        $doctorName = $args['doctor_name'] ?? null;

                        // Tìm hoặc tạo bệnh nhân
                        $patient = \App\Models\Patient::firstOrCreate(
                            ['phone' => $phone],
                            ['full_name' => $fullName]
                        );
                        if (!$patient->wasRecentlyCreated && $patient->full_name !== $fullName) {
                            $patient->update(['full_name' => $fullName]);
                        }

                        // Xếp bác sĩ
                        $doctorId = null;
                        if ($doctorName) {
                            $doctor = \App\Models\Doctor::whereHas('user', function($q) use ($doctorName) {
                                $q->where('full_name', 'like', "%$doctorName%");
                            })->first();
                            $doctorId = $doctor?->id;
                        }

                        if (!$doctorId) {
                            $busyIds = \App\Models\Appointment::whereDate('date', $date)
                                ->where('time', $time)
                                ->whereIn('status', ['pending', 'inprocess', 'complete'])
                                ->pluck('doctor_id')->toArray();
                            $freeDoctor = \App\Models\Doctor::whereNotIn('id', $busyIds)->first();
                            $doctorId = $freeDoctor?->id;
                        }

                        if (!$doctorId) {
                            $funcResult = "Thất bại: Tất cả bác sĩ đều kín lịch vào giờ $time ngày $date. Yêu cầu khách chọn giờ khác.";
                        } else {
                            $apt = \App\Models\Appointment::create([
                                'patient_id' => $patient->id,
                                'doctor_id'  => $doctorId,
                                'date'       => $date,
                                'time'       => $time,
                                'status'     => 'unconfirmed',
                            ]);

                            // Tự tạo User Account nếu chưa có
                            $existingUser = \App\Models\User::where('username', $patient->phone)->first();
                            if (!$existingUser) {
                                $rolePatient = \App\Models\Role::where('name', 'Bệnh nhân')->first();
                                if ($rolePatient) {
                                    $newUser = \App\Models\User::create([
                                        'username' => $patient->phone,
                                        'full_name' => $patient->full_name,
                                        'password' => \Illuminate\Support\Facades\Hash::make($patient->phone),
                                        'role_id' => $rolePatient->id,
                                    ]);
                                    $patient->update(['user_id' => $newUser->id]);
                                }
                            } else {
                                $patient->update(['user_id' => $existingUser->id]);
                            }

                            $funcResult = "Thành công: Lịch hẹn mã #{$apt->id} vào $time ngày $date. Hãy thông báo dặn khách đến phòng khám ĐÚNG GIỜ, và nhắc khách có thể tra cứu/đăng nhập hệ thống bằng TÀI KHOẢN VÀ MẬT KHẨU LÀ SỐ ĐIỆN THOẠI ($phone). Phải nhắc tài khoản/password cho họ.";
                        }

                        // Chuẩn bị gửi lại kết quả hàm cho Gemini
                        $payload['contents'][] = $data['candidates'][0]['content']; // Nối functionCall vào lịch sử
                        $payload['contents'][] = [
                            'role' => 'user', // Đối với Gemini V1beta, thường dùng role 'user' hoặc 'function'
                            'parts' => [
                                [
                                    'functionResponse' => [
                                        'name' => 'book_appointment',
                                        'response' => ['result' => $funcResult]
                                    ]
                                ]
                            ]
                        ];

                        $res2 = Http::withoutVerifying()->post($url, $payload);
                        if ($res2->successful()) {
                            $data2 = $res2->json();
                            $text = $data2['candidates'][0]['content']['parts'][0]['text'] ?? 'Đã hoàn tất đặt lịch!';
                            return response()->json(['status' => 'success', 'reply' => $text]);
                        }
                    }
                }

                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Chuyển markdown cơ bản thành html nếu cần, hoặc trả về text cho frontend tự xử lý.
                return response()->json([
                    'status' => 'success',
                    'reply' => $text
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi kết nối AI: ' . $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
}
