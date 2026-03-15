<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Hóa Đơn - #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-group {
            width: 48%;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f9f9f9;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 100px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">🖨️ Bắt đầu in ngay</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Đóng trang</button>
    </div>

    <div class="header">
        <h1>Phòng Khám Da Liễu Cao Cấp</h1>
        <p>Địa chỉ: 123 Đường ABC, Quận X, TP. Hồ Chí Minh</p>
        <p>Hotline: 0901 234 567 - Website: www.phongkhamdalieu.vn</p>
    </div>

    <div class="invoice-title">HÓA ĐƠN CHI TIẾT & KẾT QUẢ KHÁM</div>
    
    <div style="text-align: center; margin-bottom: 10px;">
        Mã hóa đơn: <strong>#{{ $invoice->id }}</strong> | Ngày lập: {{ $invoice->created_at->format('d/m/Y H:i') }}
    </div>

    <div class="info-section">
        <div class="info-group">
            <h3 style="border-bottom: 1px solid #333; padding-bottom: 5px;">THÔNG TIN BỆNH NHÂN</h3>
            <p><span class="info-label">Họ tên:</span> {{ $invoice->patient->full_name }}</p>
            <p><span class="info-label">SĐT:</span> {{ $invoice->patient->phone }}</p>
            <p><span class="info-label">Giới tính:</span> {{ $invoice->patient->gender == 1 ? 'Nam' : 'Nữ' }}</p>
            <p><span class="info-label">Năm sinh:</span> {{ $invoice->patient->birth_year }}</p>
            <p><span class="info-label">Địa chỉ:</span> {{ $invoice->patient->address }}</p>
        </div>
        <div class="info-group">
            <h3 style="border-bottom: 1px solid #333; padding-bottom: 5px;">THÔNG TIN KHÁM BỆNH</h3>
            <p><span class="info-label">Bác sĩ khám:</span> BS. {{ $invoice->medical_record->appointment->doctor->user->full_name ?? 'N/A' }}</p>
            <p><span class="info-label">Chẩn đoán:</span> {{ $invoice->medical_record->diagnosis ?? 'N/A' }}</p>
            <p><span class="info-label">Mã lịch hẹn:</span> #{{ $invoice->medical_record->appointment_id }}</p>
        </div>
    </div>

    <h3 style="border-bottom: 1px solid #333; padding-bottom: 5px;">CHI TIẾT DỊCH VỤ & THUỐC</h3>
    <table>
        <thead>
            <tr>
                <th>Nội dung</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Phí khám lâm sàng</td>
                <td>1</td>
                <td>{{ number_format($invoice->examination_fee, 0, ',', '.') }} VNĐ</td>
                <td>{{ number_format($invoice->examination_fee, 0, ',', '.') }} VNĐ</td>
            </tr>
            @if($invoice->medical_record->prescription && $invoice->medical_record->prescription->items->count() > 0)
                @foreach($invoice->medical_record->prescription->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->medicine->name }}</strong><br>
                            <small>HD: {{ $item->dosage }} / {{ $item->usage }}</small>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->medicine->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($item->quantity * $item->medicine->price, 0, ',', '.') }} VNĐ</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="total-section">
        <p>Phí dịch vụ: {{ number_format($invoice->examination_fee, 0, ',', '.') }} VNĐ</p>
        <p>Tiền thuốc: {{ number_format($invoice->medicine_fee, 0, ',', '.') }} VNĐ</p>
        <p class="total-amount">TỔNG CỘNG: {{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</p>
        <p><em>(Bằng chữ: ........................................................................................)</em></p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p><strong>Bệnh nhân</strong></p>
            <p>(Ký và ghi rõ họ tên)</p>
            <div class="signature-space"></div>
        </div>
        <div class="signature-box">
            <p><strong>Lễ tân / Kế toán</strong></p>
            <p>(Ký và ghi rõ họ tên)</p>
            <div class="signature-space"></div>
        </div>
    </div>

    <script>
        // Tự động hiện hộp thoại in khi trang load xong hoàn toàn
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
