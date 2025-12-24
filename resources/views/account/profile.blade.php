@extends('layouts.app')

@section('title', 'Tài khoản của tôi - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tài khoản của tôi</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card card-modern">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-circle mx-auto mb-3">
                            <i class="fas fa-user fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Nguyễn Văn A</h5>
                        <p class="text-muted small mb-0">nguyenvana@email.com</p>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="editProfile()">
                            <i class="fas fa-edit me-1"></i>
                            Chỉnh sửa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="card card-modern mt-4">
                <div class="list-group list-group-flush">
                    <a href="#profile" class="list-group-item list-group-item-action active" data-tab="profile">
                        <i class="fas fa-user me-2"></i>
                        Thông tin cá nhân
                    </a>
                    <a href="#orders" class="list-group-item list-group-item-action" data-tab="orders">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Đơn hàng của tôi
                        <span class="badge bg-primary rounded-pill float-end">3</span>
                    </a>
                    <a href="#addresses" class="list-group-item list-group-item-action" data-tab="addresses">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Sổ địa chỉ
                    </a>
                    <a href="#wishlist" class="list-group-item list-group-item-action" data-tab="wishlist">
                        <i class="fas fa-heart me-2"></i>
                        Danh sách yêu thích
                        <span class="badge bg-danger rounded-pill float-end">5</span>
                    </a>
                    <a href="#reviews" class="list-group-item list-group-item-action" data-tab="reviews">
                        <i class="fas fa-star me-2"></i>
                        Đánh giá của tôi
                    </a>
                    <a href="#settings" class="list-group-item list-group-item-action" data-tab="settings">
                        <i class="fas fa-cog me-2"></i>
                        Cài đặt tài khoản
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Profile Tab -->
            <div class="tab-content" id="profile">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Thông tin cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="profileForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="ho_ten" value="Nguyễn Văn A">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="nguyenvana@email.com">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="so_dien_thoai" value="0787905089">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" name="ngay_sinh" value="1990-01-01">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select class="form-select" name="gioi_tinh">
                                        <option value="nam" selected>Nam</option>
                                        <option value="nu">Nữ</option>
                                        <option value="khac">Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nghề nghiệp</label>
                                    <input type="text" class="form-control" name="nghe_nghiep" value="Kỹ sư phần mềm">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control" name="dia_chi" rows="3">Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long</textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="tab-content d-none" id="orders">
                <div class="card card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Đơn hàng của tôi
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active" data-filter="all">Tất cả</button>
                            <button class="btn btn-outline-primary" data-filter="pending">Chờ xử lý</button>
                            <button class="btn btn-outline-primary" data-filter="shipping">Đang giao</button>
                            <button class="btn btn-outline-primary" data-filter="completed">Hoàn thành</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="ordersList">
                            <!-- Sample orders -->
                            <div class="order-item border-bottom p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="fw-semibold">#DH001</div>
                                        <small class="text-muted">15/12/2024</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/50x60/667eea/ffffff?text=Book" 
                                                 alt="Book" class="rounded me-2" style="width: 40px; height: 50px;">
                                            <div>
                                                <div class="fw-semibold small">Tư duy nhanh và chậm</div>
                                                <small class="text-muted">và 2 sản phẩm khác</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="fw-bold text-primary">450.000đ</div>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewOrder('DH001')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="reorder('DH001')">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="cancelOrder('DH001')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-item border-bottom p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="fw-semibold">#DH002</div>
                                        <small class="text-muted">10/12/2024</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/50x60/667eea/ffffff?text=Book" 
                                                 alt="Book" class="rounded me-2" style="width: 40px; height: 50px;">
                                            <div>
                                                <div class="fw-semibold small">Đắc nhân tâm</div>
                                                <small class="text-muted">1 sản phẩm</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-info">Đang giao</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="fw-bold text-primary">120.000đ</div>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewOrder('DH002')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="trackOrder('DH002')">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-item p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="fw-semibold">#DH003</div>
                                        <small class="text-muted">05/12/2024</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/50x60/667eea/ffffff?text=Book" 
                                                 alt="Book" class="rounded me-2" style="width: 40px; height: 50px;">
                                            <div>
                                                <div class="fw-semibold small">Sapiens</div>
                                                <small class="text-muted">và 1 sản phẩm khác</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-success">Hoàn thành</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="fw-bold text-primary">380.000đ</div>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewOrder('DH003')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="reorder('DH003')">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="reviewOrder('DH003')">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div class="tab-content d-none" id="addresses">
                <div class="card card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Sổ địa chỉ
                        </h5>
                        <button class="btn btn-primary btn-sm" onclick="addAddress()">
                            <i class="fas fa-plus me-1"></i>
                            Thêm địa chỉ
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-semibold mb-0">Địa chỉ nhà</h6>
                                            <span class="badge bg-primary">Mặc định</span>
                                        </div>
                                        <p class="text-muted mb-2">
                                            <strong>Nguyễn Văn A</strong><br>
                                            0787905089<br>
                                            Khóm 9, Phường Nguyệt Hóa<br>
                                            Tỉnh Vĩnh Long
                                        </p>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editAddress(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteAddress(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-semibold mb-0">Địa chỉ công ty</h6>
                                        </div>
                                        <p class="text-muted mb-2">
                                            <strong>Nguyễn Văn A</strong><br>
                                            0787905089<br>
                                            456 Đường DEF, Phường UVW<br>
                                            Tỉnh Vĩnh Long
                                        </p>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary" onclick="setDefaultAddress(2)">
                                                Đặt mặc định
                                            </button>
                                            <button class="btn btn-outline-primary" onclick="editAddress(2)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteAddress(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlist Tab -->
            <div class="tab-content d-none" id="wishlist">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-heart me-2"></i>
                            Danh sách yêu thích
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="wishlistItems">
                            <!-- Wishlist items will be loaded here -->
                            <div class="col-12 text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-content d-none" id="reviews">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            Đánh giá của tôi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="reviewsList">
                            <!-- Sample reviews -->
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="https://via.placeholder.com/100x120/667eea/ffffff?text=Book" 
                                             alt="Book" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-9">
                                        <h6 class="fw-semibold mb-2">Tư duy nhanh và chậm</h6>
                                        <div class="text-warning mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <p class="text-muted mb-2">
                                            Cuốn sách rất hay, giúp tôi hiểu rõ hơn về cách thức hoạt động của bộ não. 
                                            Tác giả trình bày rất dễ hiểu và có nhiều ví dụ thực tế.
                                        </p>
                                        <small class="text-muted">Đánh giá ngày 10/12/2024</small>
                                    </div>
                                </div>
                            </div>

                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="https://via.placeholder.com/100x120/667eea/ffffff?text=Book" 
                                             alt="Book" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-9">
                                        <h6 class="fw-semibold mb-2">Đắc nhân tâm</h6>
                                        <div class="text-warning mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p class="text-muted mb-2">
                                            Cuốn sách kinh điển về kỹ năng giao tiếp. Rất hữu ích cho công việc và cuộc sống.
                                        </p>
                                        <small class="text-muted">Đánh giá ngày 05/12/2024</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-content d-none" id="settings">
                <div class="card card-modern">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Cài đặt tài khoản
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Change Password -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Đổi mật khẩu</h6>
                            <form id="changePasswordForm">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mật khẩu hiện tại</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Xác nhận mật khẩu</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key me-2"></i>
                                    Đổi mật khẩu
                                </button>
                            </form>
                        </div>

                        <hr>

                        <!-- Notification Settings -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Cài đặt thông báo</h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    Nhận thông báo qua email
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="orderUpdates" checked>
                                <label class="form-check-label" for="orderUpdates">
                                    Cập nhật trạng thái đơn hàng
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="promotions">
                                <label class="form-check-label" for="promotions">
                                    Thông báo khuyến mãi
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="newBooks">
                                <label class="form-check-label" for="newBooks">
                                    Sách mới ra mắt
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- Privacy Settings -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Cài đặt riêng tư</h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="profilePublic">
                                <label class="form-check-label" for="profilePublic">
                                    Hiển thị hồ sơ công khai
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="showReviews" checked>
                                <label class="form-check-label" for="showReviews">
                                    Hiển thị đánh giá của tôi
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="showWishlist">
                                <label class="form-check-label" for="showWishlist">
                                    Hiển thị danh sách yêu thích
                                </label>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary" onclick="saveSettings()">
                                <i class="fas fa-save me-2"></i>
                                Lưu cài đặt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .list-group-item-action.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .order-item:hover {
        background-color: #f8f9fa;
    }
    
    .review-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('[data-tab]').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            document.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('d-none'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.remove('d-none');
            
            // Load tab content if needed
            loadTabContent(this.dataset.tab);
        });
    });

    // Load tab content
    function loadTabContent(tab) {
        switch(tab) {
            case 'wishlist':
                loadWishlist();
                break;
            case 'orders':
                loadOrders();
                break;
        }
    }

    // Load wishlist
    function loadWishlist() {
        const container = document.getElementById('wishlistItems');
        
        fetch('/api/wishlist/items')
            .then(response => response.json())
            .then(data => {
                if (data.items && data.items.length > 0) {
                    let html = '';
                    data.items.forEach(item => {
                        html += `
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card card-modern h-100">
                                    <div class="card-body text-center">
                                        <img src="${item.anh_bia_url || '/images/no-image.png'}" 
                                             alt="${item.ten_sach}" 
                                             class="img-fluid mb-3 rounded" 
                                             style="max-height: 150px; object-fit: cover;">
                                        <h6 class="card-title">${item.ten_sach}</h6>
                                        <p class="text-primary fw-bold">
                                            ${formatPrice(item.gia_khuyen_mai || item.gia_ban)}đ
                                        </p>
                                        <div class="btn-group w-100">
                                            <button class="btn btn-primary btn-sm" onclick="addToCart(${item.sach_id})">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                            <a href="/books/${item.sach_id}" class="btn btn-outline-primary btn-sm">
                                                Xem chi tiết
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm" onclick="removeFromWishlist(${item.sach_id})">
                                                <i class="fas fa-heart-broken"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-heart text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted mb-3">Danh sách yêu thích trống</h5>
                            <p class="text-muted mb-4">Hãy thêm những cuốn sách yêu thích vào danh sách</p>
                            <a href="/search" class="btn btn-primary">
                                <i class="fas fa-book me-2"></i>
                                Khám phá sách
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading wishlist:', error);
                container.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Có lỗi xảy ra khi tải danh sách yêu thích</p>
                    </div>
                `;
            });
    }

    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        showLoading();
        
        fetch('/api/profile/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Cập nhật thông tin thành công!', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra!', 'danger');
        });
    });

    // Change password form
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        if (data.new_password !== data.confirm_password) {
            showToast('Mật khẩu xác nhận không khớp!', 'danger');
            return;
        }
        
        showLoading();
        
        fetch('/api/profile/change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Đổi mật khẩu thành công!', 'success');
                this.reset();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra!', 'danger');
        });
    });

    // Order actions
    function viewOrder(orderId) {
        window.location.href = `/orders/${orderId}`;
    }

    function trackOrder(orderId) {
        window.location.href = `/orders/${orderId}/track`;
    }

    function cancelOrder(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            // Cancel order logic
            showToast('Đã hủy đơn hàng thành công!', 'success');
        }
    }

    function reorder(orderId) {
        // Reorder logic
        showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
    }

    function reviewOrder(orderId) {
        window.location.href = `/orders/${orderId}/review`;
    }

    // Address actions
    function addAddress() {
        // Add address modal or page
        showToast('Chức năng đang phát triển!', 'info');
    }

    function editAddress(addressId) {
        // Edit address modal or page
        showToast('Chức năng đang phát triển!', 'info');
    }

    function deleteAddress(addressId) {
        if (confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
            showToast('Đã xóa địa chỉ thành công!', 'success');
        }
    }

    function setDefaultAddress(addressId) {
        showToast('Đã đặt làm địa chỉ mặc định!', 'success');
    }

    // Wishlist actions
    function addToCart(bookId) {
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                sach_id: bookId,
                so_luong: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Đã thêm vào giỏ hàng!', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        });
    }

    function removeFromWishlist(bookId) {
        if (confirm('Bạn có chắc chắn muốn xóa khỏi danh sách yêu thích?')) {
            fetch('/api/wishlist/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sach_id: bookId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Đã xóa khỏi danh sách yêu thích!', 'success');
                    loadWishlist(); // Reload wishlist
                } else {
                    showToast(data.message || 'Có lỗi xảy ra!', 'danger');
                }
            });
        }
    }

    // Save settings
    function saveSettings() {
        const settings = {
            email_notifications: document.getElementById('emailNotifications').checked,
            order_updates: document.getElementById('orderUpdates').checked,
            promotions: document.getElementById('promotions').checked,
            new_books: document.getElementById('newBooks').checked,
            profile_public: document.getElementById('profilePublic').checked,
            show_reviews: document.getElementById('showReviews').checked,
            show_wishlist: document.getElementById('showWishlist').checked
        };
        
        fetch('/api/profile/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(settings)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Đã lưu cài đặt thành công!', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        });
    }

    // Format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
</script>
@endpush