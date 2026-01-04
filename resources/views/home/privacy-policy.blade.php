@extends('layouts.pure-blade')

@section('title', $title ?? 'Chính sách bảo mật')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 1rem;">
            🔒 Chính sách bảo mật
        </h1>
        <p style="color: var(--secondary-color); font-size: 1.1rem;">
            Cam kết bảo vệ thông tin cá nhân của khách hàng
        </p>
        <p style="color: var(--secondary-color); font-size: 0.9rem;">
            Cập nhật lần cuối: {{ date('d/m/Y') }}
        </p>
    </div>

    <div class="policy-content" style="max-width: 900px; margin: 0 auto;">
        <!-- Highlight Banner -->
        <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1.5rem 2rem; border-radius: 16px; text-align: center; margin-bottom: 2rem;">
            <h2 style="margin: 0; font-size: 1.5rem;">🛡️ BẢO MẬT TUYỆT ĐỐI</h2>
            <p style="margin: 0.5rem 0 0; opacity: 0.9;">Thông tin của bạn được mã hóa và bảo vệ theo tiêu chuẩn quốc tế</p>
        </div>

        <!-- Section 1 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: var(--gradient-primary); color: white;">
                <h3 style="margin: 0;">📋 1. Thông tin chúng tôi thu thập</h3>
            </div>
            <div class="card-body">
                <p>Khi bạn sử dụng BookStore, chúng tôi có thể thu thập các thông tin sau:</p>
                <ul>
                    <li><strong>Thông tin cá nhân:</strong> Họ tên, email, số điện thoại, địa chỉ giao hàng</li>
                    <li><strong>Thông tin tài khoản:</strong> Tên đăng nhập, mật khẩu (được mã hóa)</li>
                    <li><strong>Thông tin giao dịch:</strong> Lịch sử đơn hàng, phương thức thanh toán</li>
                    <li><strong>Thông tin kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt, thiết bị sử dụng</li>
                    <li><strong>Cookies:</strong> Để cải thiện trải nghiệm người dùng</li>
                </ul>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <h3 style="margin: 0;">🎯 2. Mục đích sử dụng thông tin</h3>
            </div>
            <div class="card-body">
                <p>Chúng tôi sử dụng thông tin của bạn để:</p>
                <ul>
                    <li>Xử lý và giao đơn hàng</li>
                    <li>Liên hệ xác nhận đơn hàng và hỗ trợ khách hàng</li>
                    <li>Gửi thông tin khuyến mãi, sách mới (nếu bạn đồng ý)</li>
                    <li>Cải thiện chất lượng dịch vụ và trải nghiệm người dùng</li>
                    <li>Phân tích xu hướng mua sắm để đề xuất sách phù hợp</li>
                    <li>Ngăn chặn gian lận và bảo vệ an ninh hệ thống</li>
                </ul>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                <h3 style="margin: 0;">🔐 3. Bảo mật thông tin</h3>
            </div>
            <div class="card-body">
                <p>BookStore cam kết bảo vệ thông tin của bạn bằng các biện pháp:</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div style="padding: 1rem; background: #f0f9ff; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔒</div>
                        <strong>Mã hóa SSL</strong>
                        <p style="font-size: 0.85rem; color: var(--secondary-color); margin: 0.5rem 0 0;">256-bit encryption</p>
                    </div>
                    <div style="padding: 1rem; background: #f0fdf4; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🛡️</div>
                        <strong>Firewall</strong>
                        <p style="font-size: 0.85rem; color: var(--secondary-color); margin: 0.5rem 0 0;">Tường lửa bảo vệ</p>
                    </div>
                    <div style="padding: 1rem; background: #fef3c7; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔑</div>
                        <strong>Hash Password</strong>
                        <p style="font-size: 0.85rem; color: var(--secondary-color); margin: 0.5rem 0 0;">Mật khẩu được mã hóa</p>
                    </div>
                    <div style="padding: 1rem; background: #fdf4ff; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                        <strong>Giám sát 24/7</strong>
                        <p style="font-size: 0.85rem; color: var(--secondary-color); margin: 0.5rem 0 0;">Theo dõi liên tục</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white;">
                <h3 style="margin: 0;">🤝 4. Chia sẻ thông tin</h3>
            </div>
            <div class="card-body">
                <p><strong>Chúng tôi KHÔNG bán hoặc cho thuê thông tin cá nhân của bạn.</strong></p>
                <p>Thông tin chỉ được chia sẻ trong các trường hợp:</p>
                <ul>
                    <li><strong>Đối tác vận chuyển:</strong> Để giao hàng đến bạn</li>
                    <li><strong>Cổng thanh toán:</strong> Để xử lý giao dịch (VNPAY, MoMo)</li>
                    <li><strong>Yêu cầu pháp lý:</strong> Khi có yêu cầu từ cơ quan có thẩm quyền</li>
                </ul>
            </div>
        </div>

        <!-- Section 5 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899, #db2777); color: white;">
                <h3 style="margin: 0;">👤 5. Quyền của bạn</h3>
            </div>
            <div class="card-body">
                <p>Bạn có các quyền sau đối với thông tin cá nhân:</p>
                <ul>
                    <li><strong>Quyền truy cập:</strong> Xem thông tin cá nhân đã cung cấp</li>
                    <li><strong>Quyền chỉnh sửa:</strong> Cập nhật thông tin không chính xác</li>
                    <li><strong>Quyền xóa:</strong> Yêu cầu xóa tài khoản và dữ liệu</li>
                    <li><strong>Quyền từ chối:</strong> Hủy đăng ký nhận email marketing</li>
                    <li><strong>Quyền khiếu nại:</strong> Liên hệ nếu có vấn đề về bảo mật</li>
                </ul>
            </div>
        </div>

        <!-- Section 6 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <h3 style="margin: 0;">🍪 6. Cookies</h3>
            </div>
            <div class="card-body">
                <p>BookStore sử dụng cookies để:</p>
                <ul>
                    <li>Ghi nhớ thông tin đăng nhập</li>
                    <li>Lưu giỏ hàng của bạn</li>
                    <li>Phân tích lưu lượng truy cập</li>
                    <li>Cá nhân hóa trải nghiệm mua sắm</li>
                </ul>
                <p>Bạn có thể tắt cookies trong cài đặt trình duyệt, tuy nhiên một số tính năng có thể không hoạt động đầy đủ.</p>
            </div>
        </div>

        <!-- Section 7 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #64748b, #475569); color: white;">
                <h3 style="margin: 0;">📝 7. Thay đổi chính sách</h3>
            </div>
            <div class="card-body">
                <p>BookStore có thể cập nhật chính sách bảo mật này theo thời gian. Mọi thay đổi sẽ được thông báo qua:</p>
                <ul>
                    <li>Email đến địa chỉ đã đăng ký</li>
                    <li>Thông báo trên website</li>
                </ul>
                <p>Việc tiếp tục sử dụng dịch vụ sau khi có thay đổi đồng nghĩa với việc bạn chấp nhận chính sách mới.</p>
            </div>
        </div>

        <!-- Contact -->
        <div style="text-align: center; padding: 2rem; background: #f8fafc; border-radius: 16px;">
            <h3>Câu hỏi về bảo mật?</h3>
            <p style="color: var(--secondary-color);">Email: <strong>privacy@bookstore.vn</strong> | Hotline: <strong>0787 905 089</strong></p>
            <a href="{{ route('contact') }}" class="btn btn-primary">📞 Liên hệ ngay</a>
        </div>
    </div>
</div>
@endsection
