@extends('layouts.pure-blade')

@section('title', $title ?? 'Chính sách đổi trả')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 1rem;">
            ↩️ Chính sách đổi trả
        </h1>
        <p style="color: var(--secondary-color); font-size: 1.1rem;">
            Cam kết đổi trả dễ dàng, bảo vệ quyền lợi khách hàng
        </p>
    </div>

    <div class="policy-content" style="max-width: 900px; margin: 0 auto;">
        <!-- Highlight Banner -->
        <div style="background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; padding: 1.5rem 2rem; border-radius: 16px; text-align: center; margin-bottom: 2rem;">
            <h2 style="margin: 0; font-size: 1.5rem;">✅ ĐỔI TRẢ TRONG 7 NGÀY</h2>
            <p style="margin: 0.5rem 0 0; opacity: 0.9;">Hoàn tiền 100% nếu sản phẩm lỗi do nhà sản xuất</p>
        </div>

        <!-- Section 1 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <h3 style="margin: 0;">✅ 1. Điều kiện đổi trả</h3>
            </div>
            <div class="card-body">
                <p><strong>BookStore chấp nhận đổi trả trong các trường hợp sau:</strong></p>
                <ul>
                    <li>Sách bị lỗi in ấn: mờ chữ, thiếu trang, in ngược, in sai...</li>
                    <li>Sách bị hư hỏng trong quá trình vận chuyển: rách, ướt, bẩn...</li>
                    <li>Giao sai sách so với đơn đặt hàng</li>
                    <li>Sách không đúng mô tả trên website</li>
                </ul>
                <div style="background: #fef3c7; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    <strong>⚠️ Lưu ý:</strong> Sách phải còn nguyên tem, nhãn, bao bì và chưa qua sử dụng.
                </div>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                <h3 style="margin: 0;">❌ 2. Trường hợp không áp dụng</h3>
            </div>
            <div class="card-body">
                <p><strong>BookStore không chấp nhận đổi trả trong các trường hợp:</strong></p>
                <ul>
                    <li>Sách đã qua sử dụng, có dấu hiệu đã đọc</li>
                    <li>Sách bị hư hỏng do lỗi của khách hàng</li>
                    <li>Sách đã bị viết, vẽ, gạch xóa</li>
                    <li>Sách không còn nguyên tem, nhãn, bao bì</li>
                    <li>Quá thời hạn đổi trả 7 ngày</li>
                    <li>Sách thuộc chương trình khuyến mãi đặc biệt (có ghi chú không đổi trả)</li>
                </ul>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <h3 style="margin: 0;">⏱️ 3. Thời hạn đổi trả</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Loại yêu cầu</th>
                            <th>Thời hạn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Đổi sách mới (cùng loại)</td>
                            <td><strong>7 ngày</strong> kể từ ngày nhận hàng</td>
                        </tr>
                        <tr>
                            <td>Đổi sách khác</td>
                            <td><strong>7 ngày</strong> kể từ ngày nhận hàng</td>
                        </tr>
                        <tr>
                            <td>Hoàn tiền</td>
                            <td><strong>7 ngày</strong> kể từ ngày nhận hàng</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 4 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                <h3 style="margin: 0;">📋 4. Quy trình đổi trả</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">1</div>
                        <div>
                            <strong>Liên hệ BookStore</strong>
                            <p style="margin: 0.25rem 0 0; color: var(--secondary-color);">Gọi hotline 0787 905 089 hoặc gửi email đến support@bookstore.vn</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">2</div>
                        <div>
                            <strong>Cung cấp thông tin</strong>
                            <p style="margin: 0.25rem 0 0; color: var(--secondary-color);">Mã đơn hàng, lý do đổi trả, hình ảnh sản phẩm lỗi (nếu có)</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">3</div>
                        <div>
                            <strong>Xác nhận yêu cầu</strong>
                            <p style="margin: 0.25rem 0 0; color: var(--secondary-color);">BookStore xác nhận và hướng dẫn gửi trả sản phẩm</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">4</div>
                        <div>
                            <strong>Gửi trả sản phẩm</strong>
                            <p style="margin: 0.25rem 0 0; color: var(--secondary-color);">Đóng gói cẩn thận và gửi về địa chỉ BookStore</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">5</div>
                        <div>
                            <strong>Hoàn tất</strong>
                            <p style="margin: 0.25rem 0 0; color: var(--secondary-color);">Nhận sách mới hoặc hoàn tiền trong 3-5 ngày làm việc</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white;">
                <h3 style="margin: 0;">💰 5. Phương thức hoàn tiền</h3>
            </div>
            <div class="card-body">
                <ul>
                    <li><strong>Thanh toán COD:</strong> Hoàn tiền qua chuyển khoản ngân hàng</li>
                    <li><strong>Thanh toán online:</strong> Hoàn tiền về tài khoản/ví điện tử đã thanh toán</li>
                    <li><strong>Thời gian hoàn tiền:</strong> 3-5 ngày làm việc sau khi nhận được sản phẩm trả</li>
                </ul>
            </div>
        </div>

        <!-- Contact -->
        <div style="text-align: center; padding: 2rem; background: #f8fafc; border-radius: 16px;">
            <h3>Cần hỗ trợ đổi trả?</h3>
            <p style="color: var(--secondary-color);">Hotline: <strong>0787 905 089</strong> | Email: <strong>support@bookstore.vn</strong></p>
            <a href="{{ route('contact') }}" class="btn btn-primary">📞 Liên hệ ngay</a>
        </div>
    </div>
</div>
@endsection
