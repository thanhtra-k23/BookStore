<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="fas fa-book-open me-2"></i>
            BookStore
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i> Trang chủ
                    </a>
                </li>
                
                <!-- Admin Menu (temporary - will be replaced with proper auth) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1"></i> Quản lý
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.sach.index') }}">
                            <i class="fas fa-book me-2"></i> Sách
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.theloai.index') }}">
                            <i class="fas fa-list me-2"></i> Thể loại
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.tacgia.index') }}">
                            <i class="fas fa-user-edit me-2"></i> Tác giả
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.nhaxuatban.index') }}">
                            <i class="fas fa-building me-2"></i> Nhà xuất bản
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.donhang.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.nguoidung.index') }}">
                            <i class="fas fa-users me-2"></i> Người dùng
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.magiamgia.index') }}">
                            <i class="fas fa-tags me-2"></i> Mã giảm giá
                        </a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}">
                        <i class="fas fa-th-large me-1"></i> Thể loại
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('authors') ? 'active' : '' }}" href="{{ route('authors') }}">
                        <i class="fas fa-users me-1"></i> Tác giả
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <form class="d-flex me-3" method="GET" action="{{ route('search') }}">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Tìm kiếm sách..." 
                           value="{{ request('q') }}" style="min-width: 200px;">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav align-items-center">
                <!-- Wishlist Button -->
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light btn-sm position-relative d-flex align-items-center" href="{{ route('wishlist.index') }}" title="Danh sách yêu thích">
                        <i class="fas fa-heart me-1"></i>
                        <span class="d-none d-md-inline">Yêu thích</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger wishlist-count" style="font-size: 0.65rem;">
                            0
                        </span>
                    </a>
                </li>

                <!-- Cart Button -->
                <li class="nav-item me-2">
                    <a class="btn btn-warning btn-sm position-relative d-flex align-items-center cart-btn" href="{{ route('cart.index') }}" title="Giỏ hàng">
                        <i class="fas fa-shopping-cart me-1"></i>
                        <span class="d-none d-md-inline">Giỏ hàng</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count" style="font-size: 0.65rem;">
                            0
                        </span>
                    </a>
                </li>

                @guest
                    <!-- Login & Register for guests -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary ms-2 px-3" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Đăng ký
                        </a>
                    </li>
                @else
                    <!-- User Dropdown for authenticated users -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle" width="24" height="24">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->ho_ten }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle" width="32" height="32">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ auth()->user()->ho_ten }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user-circle me-2"></i> Hồ sơ cá nhân
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('orders') }}">
                                <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                <i class="fas fa-heart me-2"></i> Danh sách yêu thích
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('account.addresses') }}">
                                <i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ
                            </a></li>
                            
                            @if(auth()->user()->vai_tro === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">
                                    <small class="text-muted">QUẢN TRỊ</small>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin
                                </a></li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('account.settings') }}">
                                <i class="fas fa-cog me-2"></i> Cài đặt tài khoản
                            </a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@push('styles')
<style>
    .user-avatar .avatar-placeholder {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, var(--primary-color), #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
    }
    
    .dropdown-header .user-avatar .avatar-placeholder {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }
    
    .btn-outline-primary {
        border-radius: 20px;
    }
    
    .navbar-nav .nav-link.btn {
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    .badge {
        font-size: 0.6rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Cart Button Styling */
    .cart-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        border: none !important;
        color: white !important;
        font-weight: 600;
        border-radius: 25px !important;
        padding: 0.5rem 1rem !important;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    
    .cart-btn:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        color: white !important;
    }
    
    .cart-btn i {
        font-size: 1rem;
    }
    
    /* Wishlist Button Styling */
    .navbar-nav .btn-outline-light {
        border-radius: 25px !important;
        padding: 0.5rem 1rem !important;
        transition: all 0.3s ease;
    }
    
    .navbar-nav .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        transform: translateY(-2px);
    }
    
    .navbar-nav .btn-outline-light i.fa-heart {
        color: #ef4444;
    }
    
    /* Badge Animation */
    .cart-count, .wishlist-count {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.1); }
    }
</style>
@endpush

<script>
    // Update cart and wishlist counts
    function updateCounts() {
        // Update cart count
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadges = document.querySelectorAll('.cart-count');
                cartBadges.forEach(badge => {
                    badge.textContent = data.count || 0;
                    badge.style.display = (data.count > 0) ? 'flex' : 'none';
                });
            })
            .catch(error => console.log('Cart count update failed'));

        // Update wishlist count
        fetch('/api/wishlist/count')
            .then(response => response.json())
            .then(data => {
                const wishlistBadges = document.querySelectorAll('.wishlist-count');
                wishlistBadges.forEach(badge => {
                    badge.textContent = data.count || 0;
                    badge.style.display = (data.count > 0) ? 'flex' : 'none';
                });
            })
            .catch(error => console.log('Wishlist count update failed'));
    }

    // Update counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCounts();
        
        // Update counts every 30 seconds
        setInterval(updateCounts, 30000);
    });

    // Update counts when items are added/removed
    window.updateCartCount = updateCounts;
    window.updateWishlistCount = updateCounts;
</script>