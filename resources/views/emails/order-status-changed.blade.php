<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
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
        .content {
            padding: 30px;
        }
        .status-icon {
            text-align: center;
            font-size: 60px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
        }
        .status-cho_xac_nhan { background: #fef3c7; color: #92400e; }
        .status-da_xac_nhan { background: #dbeafe; color: #1e40af; }
        .status-dang_giao { background: #e0e7ff; color: #3730a3; }
        .status-da_giao { background: #dcfce7; color: #166534; }
        .status-da_huy { background: #fee2e2; color: #991b1b; }
        
        .order-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
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
        .timeline {
            margin: 30px 0;
        }
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        .timeline-icon.active {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
        }
        .timeline-icon.pending {
            background: #e2e8f0;
            color: #64748b;
        }
        .timeline-content h4 {
            margin: 0 0 5px;
            color: #1e293b;
        }
        .timeline-content p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 20px 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 BookStore</h1>
            <p>Cập nhật trạng thái đơn hàng</p>
        </div>
        
        <div class="content">
            @php
                $icons = [
                    'cho_xac_nhan' => '⏳',
                    'da_xac_nhan' => '✅',
                    'dang_giao' => '🚚',
                    'da_giao' => '📦',
                    'da_huy' => '❌',
                ];
            @endphp
            
            <div class="status-icon">{{ $icons[$newStatus] ?? '📋' }}</div>
            
            <h2 style="text-align: center; margin-bottom: 10px;">
                Đơn hàng #{{ $donHang->ma_don }}
            </h2>
            
            <p style="text-align: center;">
                <span class="status-badge status-{{ $newStatus }}">
                    {{ $statusText }}
                </span>
            </p>

            <p style="text-align: center; margin-top: 20px;">
                Xin chào <strong>{{ $donHang->ho_ten_nguoi_nhan }}</strong>,<br>
                Trạng thái đơn hàng của bạn đã được cập nhật.
            </p>

            <div class="order-info">
                <div class="info-row">
                    <span>Mã đơn hàng:</span>
                    <strong>#{{ $donHang->ma_don }}</strong>
                </div>
                <div class="info-row">
                    <span>Ngày đặt:</span>
                    <span>{{ $donHang->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span>Tổng tiền:</span>
                    <strong style="color: #16a34a;">{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</strong>
                </div>
            </div>

            <h3>📍 Tiến trình đơn hàng</h3>
            <div class="timeline">
                @php
                    $statuses = [
                        'cho_xac_nhan' => ['icon' => '📝', 'title' => 'Đặt hàng', 'desc' => 'Đơn hàng đã được tạo'],
                        'da_xac_nhan' => ['icon' => '✅', 'title' => 'Xác nhận', 'desc' => 'Đơn hàng đã được xác nhận'],
                        'dang_giao' => ['icon' => '🚚', 'title' => 'Đang giao', 'desc' => 'Đơn hàng đang được vận chuyển'],
                        'da_giao' => ['icon' => '📦', 'title' => 'Hoàn thành', 'desc' => 'Đơn hàng đã giao thành công'],
                    ];
                    $currentIndex = array_search($newStatus, array_keys($statuses));
                    if ($newStatus === 'da_huy') $currentIndex = -1;
                @endphp
                
                @foreach($statuses as $key => $status)
                    @php
                        $index = array_search($key, array_keys($statuses));
                        $isActive = $index <= $currentIndex;
                    @endphp
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $isActive ? 'active' : 'pending' }}">
                            {{ $status['icon'] }}
                        </div>
                        <div class="timeline-content">
                            <h4>{{ $status['title'] }}</h4>
                            <p>{{ $status['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
                
                @if($newStatus === 'da_huy')
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: #fee2e2; color: #dc2626;">
                        ❌
                    </div>
                    <div class="timeline-content">
                        <h4 style="color: #dc2626;">Đã hủy</h4>
                        <p>Đơn hàng đã bị hủy</p>
                    </div>
                </div>
                @endif
            </div>

            @if($newStatus === 'dang_giao')
            <div style="background: #eff6ff; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #1e40af;">
                    <strong>📞 Lưu ý:</strong> Vui lòng giữ điện thoại để nhận cuộc gọi từ shipper. 
                    Đơn hàng sẽ được giao trong 2-3 ngày làm việc.
                </p>
            </div>
            @endif

            @if($newStatus === 'da_giao')
            <div style="background: #f0fdf4; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #166534;">
                    <strong>🎉 Cảm ơn bạn!</strong> Đơn hàng đã được giao thành công. 
                    Hy vọng bạn hài lòng với sản phẩm. Đừng quên đánh giá sách nhé!
                </p>
            </div>
            @endif

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('orders') }}" class="btn">Xem chi tiết đơn hàng</a>
            </div>
        </div>

        <div class="footer">
            <p>📚 <strong>BookStore</strong> - Nhà sách trực tuyến</p>
            <p>📞 0787905089 | ✉️ contact@bookstore.vn</p>
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} BookStore. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
