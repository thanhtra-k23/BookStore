@extends('layouts.pure-blade')

@section('title', $title ?? 'Chính sách vận chuyển')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 1rem;">
            🚚 Chính sách vận chuyển
        </h1>
        <p style="color: var(--secondary-color); font-size: 1.1rem;">
            Thông tin chi tiết về dịch vụ giao hàng của BookStore
        </p>
    </div>

    <div class="policy-content" style="max-width: 900px; margin: 0 auto;">
        <!-- Free Shipping Banner -->
        <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1.5rem 2rem; border-radius: 16px; text-align: center; margin-bottom: 2rem;">
            <h2 style="margin: 0; font-size: 1.5rem;">🎉 MIỄN PHÍ VẬN CHUYỂN</h2>
            <p style="margin: 0.5rem 0 0; opacity: 0.9;">Cho tất cả đơn hàng từ 300.000đ trở lên</p>
        </div>

        <!-- Section 1 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: var(--gradient-primary); color: white;">
                <h3 style="margin: 0;">📍 1. Phạm vi giao hàng</h3>
            </div>
            <div class="card-body">
                <p>BookStore giao hàng trên <strong>toàn quốc</strong>, bao gồm:</p>
                <ul>
                    <li>63 tỉnh thành trên cả nước</li>
                    <li>Các huyện đảo (thời gian giao hàng có thể lâu hơn)</li>
                </ul>
                <p><em>Lưu ý: Một số khu vực vùng sâu, vùng xa có thể phát sinh thêm phí vận chuyển.</em></p>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <h3 style="margin: 0;">⏱️ 2. Thời gian giao hàng</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Khu vực</th>
                            <th>Thời gian dự kiến</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nội thành TP.HCM, Hà Nội</td>
                            <td><strong>1-2 ngày</strong> làm việc</td>
                        </tr>
                        <tr>
                            <td>Các tỉnh thành lân cận</td>
                            <td><strong>2-3 ngày</strong> làm việc</td>
                        </tr>
                        <tr>
                            <td>Các tỉnh thành khác</td>
                            <td><strong>3-5 ngày</strong> làm việc</td>
                        </tr>
                        <tr>
                            <td>Vùng sâu, vùng xa, hải đảo</td>
                            <td><strong>5-7 ngày</strong> làm việc</td>
                        </tr>
                    </tbody>
                </table>
                <p><em>* Thời gian giao hàng được tính từ khi đơn hàng được xác nhận và không bao gồm ngày lễ, Chủ nhật.</em></p>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                <h3 style="margin: 0;">💰 3. Phí vận chuyển</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Giá trị đơn hàng</th>
                            <th>Phí vận chuyển</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Từ 300.000đ trở lên</td>
                            <td><span style="color: #10b981; font-weight: 700;">MIỄN PHÍ</span></td>
                        </tr>
                        <tr>
                            <td>Dưới 300.000đ (Nội thành)</td>
                            <td>20.000đ - 25.000đ</td>
                        </tr>
                        <tr>
                            <td>Dưới 300.000đ (Ngoại thành)</td>
                            <td>25.000đ - 35.000đ</td>
                        </tr>
                        <tr>
                            <td>Dưới 300.000đ (Tỉnh khác)</td>
                            <td>30.000đ - 50.000đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 4 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white;">
                <h3 style="margin: 0;">📦 4. Đối tác vận chuyển</h3>
            </div>
            <div class="card-body">
                <p>BookStore hợp tác với các đơn vị vận chuyển uy tín:</p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
                    <span style="padding: 0.5rem 1rem; background: #f1f5f9; border-radius: 8px;">🚛 Giao Hàng Nhanh (GHN)</span>
                    <span style="padding: 0.5rem 1rem; background: #f1f5f9; border-radius: 8px;">🚛 Giao Hàng Tiết Kiệm (GHTK)</span>
                    <span style="padding: 0.5rem 1rem; background: #f1f5f9; border-radius: 8px;">🚛 J&T Express</span>
                    <span style="padding: 0.5rem 1rem; background: #f1f5f9; border-radius: 8px;">🚛 Viettel Post</span>
                </div>
            </div>
        </div>

        <!-- Section 5 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899, #db2777); color: white;">
                <h3 style="margin: 0;">📋 5. Quy trình giao hàng</h3>
            </div>
            <div class="card-body">
                <ol style="padding-left: 1.5rem;">
                    <li style="margin-bottom: 1rem;">
                        <strong>Xác nhận đơn hàng:</strong> Sau khi đặt hàng, bạn sẽ nhận được email/SMS xác nhận
                    </li>
                    <li style="margin-bottom: 1rem;">
                        <strong>Đóng gói:</strong> Sách được đóng gói cẩn thận trong vòng 24h
                    </li>
                    <li style="margin-bottom: 1rem;">
                        <strong>Giao cho vận chuyển:</strong> Đơn hàng được bàn giao cho đơn vị vận chuyển
                    </li>
                    <li style="margin-bottom: 1rem;">
                        <strong>Theo dõi:</strong> Bạn có thể theo dõi đơn hàng qua tài khoản hoặc mã vận đơn
                    </li>
                    <li>
                        <strong>Nhận hàng:</strong> Kiểm tra hàng và thanh toán (nếu COD)
                    </li>
                </ol>
            </div>
        </div>

        <!-- Contact -->
        <div style="text-align: center; padding: 2rem; background: #f8fafc; border-radius: 16px;">
            <h3>Cần hỗ trợ về vận chuyển?</h3>
            <p style="color: var(--secondary-color);">Liên hệ hotline: <strong>0787 905 089</strong></p>
            <a href="{{ route('contact') }}" class="btn btn-primary">📞 Liên hệ ngay</a>
        </div>
    </div>
</div>
@endsection
