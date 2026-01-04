<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            font-size: 60px;
            margin-bottom: 20px;
        }
        .order-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-info h3 {
            margin-top: 0;
            color: #2563eb;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #64748b;
        }
        .info-value {
            font-weight: 600;
            color: #1e293b;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th {
            background: #1e293b;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            color: #16a34a;
            border-top: 2px solid #16a34a;
            padding-top: 15px;
            margin-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 20px 30px;
            text-align: center;
        }
        .footer a {
            color: #60a5fa;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 BookStore</h1>
            <p>Xác nhận đơn hàng thành công</p>
        </div>
        
        <div class="content">
            <div class="success-icon">✅</div>
            
            <h2 style="text-align: center; color: #16a34a;">Cảm ơn bạn đã đặt hàng!</h2>
            
            <p style="text-align: center;">
                Xin chào <strong>{{ $donHang->ho_ten_nguoi_nhan }}</strong>,<br>
                Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.
            </p>

            <div class="order-info">
                <h3>📋 Thông tin đơn hàng</h3>
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value">#{{ $donHang->ma_don }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">{{ $donHang->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span class="status-badge">{{ $donHang->trang_thai_text }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thanh toán:</span>
                    <span class="info-value">{{ $donHang->phuong_thuc_thanh_toan == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}</span>
                </div>
            </div>

            <div class="order-info">
                <h3>📍 Địa chỉ giao hàng</h3>
                <p style="margin: 0;">
                    <strong>{{ $donHang->ho_ten_nguoi_nhan }}</strong><br>
                    📞 {{ $donHang->so_dien_thoai }}<br>
                    🏠 {{ $donHang->dia_chi_giao }}
                </p>
                @if($donHang->ghi_chu)
                <p style="margin-top: 10px; color: #64748b;">
                    <em>Ghi chú: {{ $donHang->ghi_chu }}</em>
                </p>
                @endif
            </div>

            <h3>🛒 Chi tiết sản phẩm</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chiTiet as $item)
                    <tr>
                        <td>{{ $item->sach->ten_sach }}</td>
                        <td style="text-align: center;">{{ $item->so_luong }}</td>
                        <td style="text-align: right;">{{ number_format($item->don_gia, 0, ',', '.') }}đ</td>
                        <td style="text-align: right;">{{ number_format($item->thanh_tien, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($donHang->tong_tien_hang, 0, ',', '.') }}đ</span>
                </div>
                @if($donHang->tien_giam > 0)
                <div class="total-row" style="color: #dc2626;">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($donHang->tien_giam, 0, ',', '.') }}đ</span>
                </div>
                @endif
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span>{{ $donHang->phi_van_chuyen > 0 ? number_format($donHang->phi_van_chuyen, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                </div>
                <div class="total-row final">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('orders') }}" class="btn">Xem đơn hàng của tôi</a>
            </div>

            <p style="text-align: center; color: #64748b; font-size: 14px;">
                Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc hotline.
            </p>
        </div>

        <div class="footer">
            <p>📚 <strong>BookStore</strong> - Nhà sách trực tuyến</p>
            <p>📍 Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long</p>
            <p>📞 0787905089 | ✉️ contact@bookstore.vn</p>
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} BookStore. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
