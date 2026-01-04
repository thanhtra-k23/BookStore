@extends('layouts.pure-blade')

@section('title', $title ?? 'Câu hỏi thường gặp')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--dark-color); margin-bottom: 1rem;">
            ❓ Câu hỏi thường gặp
        </h1>
        <p style="color: var(--secondary-color); font-size: 1.1rem;">
            Tìm câu trả lời cho những thắc mắc phổ biến của bạn
        </p>
    </div>

    <!-- FAQ Categories -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-12">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem;">
                <button class="faq-tab active" onclick="showFaqCategory('all')">📋 Tất cả</button>
                <button class="faq-tab" onclick="showFaqCategory('order')">📦 Đơn hàng</button>
                <button class="faq-tab" onclick="showFaqCategory('payment')">💳 Thanh toán</button>
                <button class="faq-tab" onclick="showFaqCategory('shipping')">🚚 Vận chuyển</button>
                <button class="faq-tab" onclick="showFaqCategory('account')">👤 Tài khoản</button>
            </div>
        </div>
    </div>

    <!-- FAQ Items -->
    <div class="faq-container">
        <!-- Đơn hàng -->
        <div class="faq-item" data-category="order">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>📦 Làm thế nào để đặt hàng?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Để đặt hàng tại BookStore, bạn thực hiện các bước sau:</p>
                <ol>
                    <li>Tìm kiếm và chọn sách bạn muốn mua</li>
                    <li>Nhấn nút "Thêm vào giỏ hàng"</li>
                    <li>Vào giỏ hàng và kiểm tra đơn hàng</li>
                    <li>Nhấn "Thanh toán" và điền thông tin giao hàng</li>
                    <li>Chọn phương thức thanh toán và xác nhận đơn hàng</li>
                </ol>
            </div>
        </div>

        <div class="faq-item" data-category="order">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>📋 Làm sao để theo dõi đơn hàng?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Bạn có thể theo dõi đơn hàng bằng cách:</p>
                <ul>
                    <li>Đăng nhập vào tài khoản</li>
                    <li>Vào mục "Đơn hàng của tôi"</li>
                    <li>Chọn đơn hàng cần theo dõi để xem chi tiết trạng thái</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" data-category="order">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>❌ Tôi có thể hủy đơn hàng không?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Bạn có thể hủy đơn hàng khi đơn hàng chưa được xử lý hoặc đang chờ xác nhận. Sau khi đơn hàng đã được giao cho đơn vị vận chuyển, bạn không thể hủy đơn hàng.</p>
            </div>
        </div>

        <!-- Thanh toán -->
        <div class="faq-item" data-category="payment">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>💳 BookStore hỗ trợ những phương thức thanh toán nào?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Chúng tôi hỗ trợ các phương thức thanh toán sau:</p>
                <ul>
                    <li><strong>COD (Thanh toán khi nhận hàng)</strong> - Thanh toán tiền mặt khi nhận sách</li>
                    <li><strong>Chuyển khoản ngân hàng</strong> - Chuyển khoản trước khi giao hàng</li>
                    <li><strong>VNPAY</strong> - Thanh toán qua cổng VNPAY</li>
                    <li><strong>MoMo</strong> - Thanh toán qua ví điện tử MoMo</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" data-category="payment">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>🔒 Thanh toán online có an toàn không?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Hoàn toàn an toàn! BookStore sử dụng các cổng thanh toán uy tín như VNPAY và MoMo với công nghệ mã hóa SSL 256-bit. Thông tin thanh toán của bạn được bảo mật tuyệt đối.</p>
            </div>
        </div>

        <!-- Vận chuyển -->
        <div class="faq-item" data-category="shipping">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>🚚 Thời gian giao hàng là bao lâu?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Thời gian giao hàng phụ thuộc vào khu vực:</p>
                <ul>
                    <li><strong>Nội thành TP.HCM, Hà Nội:</strong> 1-2 ngày làm việc</li>
                    <li><strong>Các tỉnh thành khác:</strong> 3-5 ngày làm việc</li>
                    <li><strong>Vùng sâu, vùng xa:</strong> 5-7 ngày làm việc</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" data-category="shipping">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>💰 Phí vận chuyển được tính như thế nào?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Phí vận chuyển được tính dựa trên:</p>
                <ul>
                    <li>Khoảng cách giao hàng</li>
                    <li>Trọng lượng đơn hàng</li>
                </ul>
                <p><strong>🎉 Miễn phí vận chuyển cho đơn hàng từ 300.000đ!</strong></p>
            </div>
        </div>

        <!-- Tài khoản -->
        <div class="faq-item" data-category="account">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>👤 Làm sao để tạo tài khoản?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Để tạo tài khoản, bạn nhấn vào nút "Đăng ký" ở góc trên bên phải, sau đó điền đầy đủ thông tin: họ tên, email, số điện thoại và mật khẩu. Xác nhận email để hoàn tất đăng ký.</p>
            </div>
        </div>

        <div class="faq-item" data-category="account">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>🔑 Tôi quên mật khẩu, phải làm sao?</span>
                <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">
                <p>Nếu quên mật khẩu, bạn có thể:</p>
                <ol>
                    <li>Nhấn vào "Quên mật khẩu" ở trang đăng nhập</li>
                    <li>Nhập email đã đăng ký</li>
                    <li>Kiểm tra email và nhấn vào link đặt lại mật khẩu</li>
                    <li>Tạo mật khẩu mới</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div style="text-align: center; margin-top: 3rem; padding: 2rem; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 16px;">
        <h3 style="margin-bottom: 1rem;">Không tìm thấy câu trả lời?</h3>
        <p style="color: var(--secondary-color); margin-bottom: 1.5rem;">Liên hệ với chúng tôi để được hỗ trợ trực tiếp</p>
        <a href="{{ route('contact') }}" class="btn btn-primary">📞 Liên hệ hỗ trợ</a>
    </div>
</div>

<style>
    .faq-tab {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .faq-tab:hover, .faq-tab.active {
        background: var(--gradient-primary);
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: white;
        border-color: transparent;
    }
    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-item {
        background: white;
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .faq-question {
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    .faq-question:hover {
        background: #f8fafc;
    }
    .faq-icon {
        font-size: 1.5rem;
        color: var(--primary-color);
        transition: transform 0.3s ease;
    }
    .faq-item.active .faq-icon {
        transform: rotate(45deg);
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        padding: 0 1.5rem;
        background: #f8fafc;
    }
    .faq-item.active .faq-answer {
        max-height: 500px;
        padding: 1.25rem 1.5rem;
    }
    .faq-answer ul, .faq-answer ol {
        padding-left: 1.5rem;
        margin: 0.5rem 0;
    }
    .faq-answer li {
        margin-bottom: 0.5rem;
    }
</style>

<script>
function toggleFaq(element) {
    const item = element.parentElement;
    item.classList.toggle('active');
}

function showFaqCategory(category) {
    // Update tabs
    document.querySelectorAll('.faq-tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter items
    document.querySelectorAll('.faq-item').forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
