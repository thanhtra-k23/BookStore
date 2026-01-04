@extends('layouts.pure-blade')

@section('title', 'Tài khoản của tôi - BookStore')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> › <span class="active">Tài khoản của tôi</span>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-3">
            <div class="card" style="position: sticky; top: 100px;">
                <div style="text-align: center; padding: 1.5rem;">
                    <div class="avatar-circle" style="width: 80px; height: 80px; border-radius: 50%; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem;">
                        {{ substr($user->ho_ten ?? 'U', 0, 1) }}
                    </div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $user->ho_ten ?? 'Khách' }}</h3>
                    <p style="color: var(--secondary-color); font-size: 0.9rem;">{{ $user->email ?? '' }}</p>
                </div>
                
                <!-- Navigation Menu -->
                <div style="border-top: 1px solid #e2e8f0;">
                    <a href="#profile" class="profile-tab active" data-tab="profile" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        👤 Thông tin cá nhân
                    </a>
                    <a href="#orders" class="profile-tab" data-tab="orders" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        📦 Đơn hàng của tôi
                        @if(isset($orderCount) && $orderCount > 0)
                            <span class="badge badge-primary" style="margin-left: auto;">{{ $orderCount }}</span>
                        @endif
                    </a>
                    <a href="#addresses" class="profile-tab" data-tab="addresses" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        📍 Sổ địa chỉ
                    </a>
                    <a href="#wishlist" class="profile-tab" data-tab="wishlist" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        ❤️ Danh sách yêu thích
                        @if(isset($wishlistCount) && $wishlistCount > 0)
                            <span class="badge badge-danger" style="margin-left: auto;">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                    <a href="#reviews" class="profile-tab" data-tab="reviews" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        ⭐ Đánh giá của tôi
                    </a>
                    <a href="#settings" class="profile-tab" data-tab="settings" style="display: flex; align-items: center; padding: 1rem; text-decoration: none; color: var(--dark-color); border-left: 3px solid transparent;">
                        ⚙️ Cài đặt tài khoản
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-9">
            <!-- Profile Tab -->
            <div class="tab-content" id="profile-content">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">👤 Thông tin cá nhân</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="ho_ten" value="{{ $user->ho_ten ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" readonly style="background: #f1f5f9;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" name="so_dien_thoai" value="{{ $user->so_dien_thoai ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" name="ngay_sinh" value="{{ $user->ngay_sinh ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control" name="dia_chi" rows="3">{{ $user->dia_chi ?? '' }}</textarea>
                            </div>
                            <div style="text-align: right;">
                                <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="tab-content" id="orders-content" style="display: none;">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="card-title">📦 Đơn hàng của tôi</h2>
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;" onclick="filterOrders('all')">Tất cả</button>
                            <button class="btn btn-warning" style="padding: 0.5rem 1rem; font-size: 0.85rem;" onclick="filterOrders('pending')">Chờ xử lý</button>
                            <button class="btn btn-info" style="padding: 0.5rem 1rem; font-size: 0.85rem;" onclick="filterOrders('shipping')">Đang giao</button>
                            <button class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.85rem;" onclick="filterOrders('completed')">Hoàn thành</button>
                        </div>
                    </div>
                    <div id="ordersList">
                        @forelse($orders ?? [] as $order)
                        <div class="order-item" data-status="{{ $order->trang_thai }}" style="padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                            <div class="row" style="align-items: center;">
                                <div class="col-2">
                                    <div style="font-weight: 600;">#{{ $order->ma_don }}</div>
                                    <small style="color: var(--secondary-color);">{{ $order->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="col-3">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        @if($order->chiTiet->first() && $order->chiTiet->first()->sach)
                                            <img src="{{ $order->chiTiet->first()->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                                                 alt="Book" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        @endif
                                        <div>
                                            <div style="font-size: 0.9rem; font-weight: 500;">{{ $order->chiTiet->first()->sach->ten_sach ?? 'Sản phẩm' }}</div>
                                            @if($order->chiTiet->count() > 1)
                                                <small style="color: var(--secondary-color);">và {{ $order->chiTiet->count() - 1 }} sản phẩm khác</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2" style="text-align: center;">
                                    @php
                                        $statusColors = [
                                            'cho_xac_nhan' => 'warning',
                                            'da_xac_nhan' => 'info',
                                            'dang_giao' => 'primary',
                                            'da_giao' => 'success',
                                            'da_huy' => 'danger'
                                        ];
                                        $statusTexts = [
                                            'cho_xac_nhan' => 'Chờ xác nhận',
                                            'da_xac_nhan' => 'Đã xác nhận',
                                            'dang_giao' => 'Đang giao',
                                            'da_giao' => 'Hoàn thành',
                                            'da_huy' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$order->trang_thai] ?? 'secondary' }}">
                                        {{ $statusTexts[$order->trang_thai] ?? $order->trang_thai }}
                                    </span>
                                </div>
                                <div class="col-2" style="text-align: center;">
                                    <div style="font-weight: 700; color: var(--primary-color);">{{ number_format($order->tong_tien) }}đ</div>
                                </div>
                                <div class="col-3" style="text-align: right;">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">👁️ Xem</a>
                                    @if($order->trang_thai == 'dang_giao')
                                        <a href="{{ route('orders.track', $order->id) }}" class="btn btn-info" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">🚚 Theo dõi</a>
                                    @endif
                                    @if($order->trang_thai == 'cho_xac_nhan')
                                        <button onclick="cancelOrder({{ $order->id }})" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">❌ Hủy</button>
                                    @endif
                                    @if($order->trang_thai == 'da_giao')
                                        <a href="{{ route('orders.review', $order->id) }}" class="btn btn-warning" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">⭐ Đánh giá</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; padding: 3rem;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
                            <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Chưa có đơn hàng nào</h3>
                            <p style="color: var(--secondary-color); margin-bottom: 1.5rem;">Hãy khám phá và mua sắm những cuốn sách yêu thích!</p>
                            <a href="{{ route('search') }}" class="btn btn-primary">🔍 Khám phá sách</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div class="tab-content" id="addresses-content" style="display: none;">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="card-title">📍 Sổ địa chỉ</h2>
                        <button class="btn btn-primary" onclick="showAddAddressModal()">➕ Thêm địa chỉ</button>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="row">
                            @forelse($addresses ?? [] as $address)
                            <div class="col-6" style="margin-bottom: 1rem;">
                                <div class="card" style="border: 2px solid {{ $address->is_default ? 'var(--primary-color)' : '#e2e8f0' }};">
                                    <div style="padding: 1rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                            <h4 style="font-size: 1rem; margin: 0;">{{ $address->ten_dia_chi ?? 'Địa chỉ' }}</h4>
                                            @if($address->is_default)
                                                <span class="badge badge-primary">Mặc định</span>
                                            @endif
                                        </div>
                                        <p style="color: var(--secondary-color); margin-bottom: 0.5rem; font-size: 0.9rem;">
                                            <strong>{{ $address->ho_ten }}</strong><br>
                                            {{ $address->so_dien_thoai }}<br>
                                            {{ $address->dia_chi_chi_tiet }}<br>
                                            {{ $address->phuong_xa }}, {{ $address->quan_huyen }}, {{ $address->tinh_thanh }}
                                        </p>
                                        <div style="display: flex; gap: 0.5rem;">
                                            @if(!$address->is_default)
                                                <button onclick="setDefaultAddress({{ $address->id }})" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Đặt mặc định</button>
                                            @endif
                                            <button onclick="editAddress({{ $address->id }})" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">✏️ Sửa</button>
                                            <button onclick="deleteAddress({{ $address->id }})" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">🗑️ Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12" style="text-align: center; padding: 2rem;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📍</div>
                                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Chưa có địa chỉ nào</h3>
                                <p style="color: var(--secondary-color); margin-bottom: 1rem;">Thêm địa chỉ để thuận tiện cho việc giao hàng</p>
                                <button class="btn btn-primary" onclick="showAddAddressModal()">➕ Thêm địa chỉ đầu tiên</button>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlist Tab -->
            <div class="tab-content" id="wishlist-content" style="display: none;">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="card-title">❤️ Danh sách yêu thích</h2>
                        @if(isset($wishlist) && count($wishlist) > 0)
                            <button class="btn btn-success" onclick="addAllToCart()">🛒 Thêm tất cả vào giỏ</button>
                        @endif
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="row">
                            @forelse($wishlist ?? [] as $item)
                            <div class="col-4" style="margin-bottom: 1.5rem;">
                                <div class="card hover-lift" style="height: 100%;">
                                    <div style="position: relative; overflow: hidden; border-radius: 12px 12px 0 0;">
                                        <img src="{{ $item->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                                             alt="{{ $item->sach->ten_sach }}" 
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                        <button onclick="removeFromWishlist({{ $item->sach->ma_sach }})" 
                                                style="position: absolute; top: 10px; right: 10px; background: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; box-shadow: var(--shadow-sm);">
                                            ❌
                                        </button>
                                    </div>
                                    <div style="padding: 1rem;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach, 'slug' => $item->sach->slug]) }}" style="text-decoration: none; color: var(--dark-color);">
                                                {{ $item->sach->ten_sach }}
                                            </a>
                                        </h4>
                                        <p style="color: var(--secondary-color); font-size: 0.85rem; margin-bottom: 0.5rem;">
                                            {{ $item->sach->tacGia->ten_tac_gia ?? 'Chưa rõ tác giả' }}
                                        </p>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-weight: 700; color: var(--danger-color);">{{ number_format($item->sach->gia_khuyen_mai ?? $item->sach->gia_ban) }}đ</span>
                                            <button onclick="addToCartFromWishlist({{ $item->sach->ma_sach }})" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">🛒 Thêm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12" style="text-align: center; padding: 3rem;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">❤️</div>
                                <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Danh sách yêu thích trống</h3>
                                <p style="color: var(--secondary-color); margin-bottom: 1.5rem;">Hãy thêm những cuốn sách yêu thích vào danh sách</p>
                                <a href="{{ route('search') }}" class="btn btn-primary">🔍 Khám phá sách</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-content" id="reviews-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">⭐ Đánh giá của tôi</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        @forelse($reviews ?? [] as $review)
                        <div class="review-item" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="row">
                                <div class="col-2">
                                    <img src="{{ $review->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                                         alt="{{ $review->sach->ten_sach }}" 
                                         style="width: 100%; border-radius: 8px;">
                                </div>
                                <div class="col-10">
                                    <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem;">
                                        <a href="{{ route('book.detail', ['id' => $review->sach->ma_sach, 'slug' => $review->sach->slug]) }}" style="text-decoration: none; color: var(--dark-color);">
                                            {{ $review->sach->ten_sach }}
                                        </a>
                                    </h4>
                                    <div style="color: #f59e0b; margin-bottom: 0.5rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->diem_so)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        <span style="color: var(--secondary-color); margin-left: 0.5rem;">({{ $review->diem_so }}/5)</span>
                                    </div>
                                    <p style="color: var(--secondary-color); margin-bottom: 0.5rem;">{{ $review->noi_dung }}</p>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <small style="color: var(--secondary-color);">Đánh giá ngày {{ $review->created_at->format('d/m/Y') }}</small>
                                        <span class="badge badge-{{ $review->trang_thai == 'da_duyet' ? 'success' : ($review->trang_thai == 'cho_duyet' ? 'warning' : 'danger') }}">
                                            {{ $review->trang_thai == 'da_duyet' ? 'Đã duyệt' : ($review->trang_thai == 'cho_duyet' ? 'Chờ duyệt' : 'Bị từ chối') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; padding: 3rem;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">⭐</div>
                            <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Chưa có đánh giá nào</h3>
                            <p style="color: var(--secondary-color); margin-bottom: 1.5rem;">Hãy mua sách và chia sẻ cảm nhận của bạn!</p>
                            <a href="{{ route('search') }}" class="btn btn-primary">🔍 Khám phá sách</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-content" id="settings-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">⚙️ Cài đặt tài khoản</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <!-- Change Password -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">🔐 Đổi mật khẩu</h3>
                            <form id="changePasswordForm" method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="form-label">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="form-label">Mật khẩu mới</label>
                                            <input type="password" class="form-control" name="new_password" required minlength="6">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="form-label">Xác nhận mật khẩu</label>
                                            <input type="password" class="form-control" name="new_password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">🔑 Đổi mật khẩu</button>
                            </form>
                        </div>

                        <!-- Notification Settings -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">🔔 Cài đặt thông báo</h3>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" id="emailNotifications" checked style="width: 18px; height: 18px;">
                                    <span>Nhận thông báo qua email</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" id="orderUpdates" checked style="width: 18px; height: 18px;">
                                    <span>Cập nhật trạng thái đơn hàng</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" id="promotions" style="width: 18px; height: 18px;">
                                    <span>Thông báo khuyến mãi</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" id="newBooks" style="width: 18px; height: 18px;">
                                    <span>Sách mới ra mắt</span>
                                </label>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div>
                            <h3 style="font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #fee2e2; color: var(--danger-color);">⚠️ Vùng nguy hiểm</h3>
                            <p style="color: var(--secondary-color); margin-bottom: 1rem;">Xóa tài khoản sẽ xóa vĩnh viễn tất cả dữ liệu của bạn. Hành động này không thể hoàn tác.</p>
                            <button onclick="confirmDeleteAccount()" class="btn btn-danger">🗑️ Xóa tài khoản</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Address Modal -->
<div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="width: 500px; max-height: 90vh; overflow-y: auto;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" id="addressModalTitle">Thêm địa chỉ mới</h3>
            <button onclick="closeAddressModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">×</button>
        </div>
        <div style="padding: 1.5rem;">
            <form id="addressForm">
                <input type="hidden" name="address_id" id="addressId">
                <div class="form-group">
                    <label class="form-label">Tên địa chỉ (VD: Nhà, Công ty)</label>
                    <input type="text" class="form-control" name="ten_dia_chi" id="tenDiaChi" required>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Họ và tên người nhận</label>
                            <input type="text" class="form-control" name="ho_ten" id="hoTenNguoiNhan" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="so_dien_thoai" id="soDienThoaiNhan" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Địa chỉ chi tiết</label>
                    <input type="text" class="form-control" name="dia_chi_chi_tiet" id="diaChiChiTiet" placeholder="Số nhà, tên đường..." required>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <input type="text" class="form-control" name="tinh_thanh" id="tinhThanh" required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label">Quận/Huyện</label>
                            <input type="text" class="form-control" name="quan_huyen" id="quanHuyen" required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label">Phường/Xã</label>
                            <input type="text" class="form-control" name="phuong_xa" id="phuongXa" required>
                        </div>
                    </div>
                </div>
                <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <input type="checkbox" name="is_default" id="isDefault">
                    <span>Đặt làm địa chỉ mặc định</span>
                </label>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeAddressModal()" class="btn btn-secondary">Hủy</button>
                    <button type="submit" class="btn btn-primary">💾 Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-tab.active {
        background: linear-gradient(135deg, #eff6ff, #f0f9ff);
        border-left-color: var(--primary-color) !important;
        color: var(--primary-color) !important;
        font-weight: 600;
    }
    .profile-tab:hover {
        background: #f8fafc;
    }
    .order-item:hover {
        background: #f8fafc;
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
document.querySelectorAll('.profile-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        this.classList.add('active');
        document.getElementById(this.dataset.tab + '-content').style.display = 'block';
    });
});

// Filter orders
function filterOrders(status) {
    document.querySelectorAll('.order-item').forEach(item => {
        if (status === 'all') {
            item.style.display = 'block';
        } else {
            const itemStatus = item.dataset.status;
            const statusMap = {
                'pending': ['cho_xac_nhan', 'da_xac_nhan'],
                'shipping': ['dang_giao'],
                'completed': ['da_giao']
            };
            item.style.display = statusMap[status]?.includes(itemStatus) ? 'block' : 'none';
        }
    });
}

// Cancel order
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                showToast('Đã hủy đơn hàng thành công!', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        });
    }
}

// Wishlist functions
function removeFromWishlist(bookId) {
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ sach_id: bookId })
    }).then(r => r.json()).then(data => {
        showToast('Đã xóa khỏi danh sách yêu thích!', 'success');
        location.reload();
    });
}

function addToCartFromWishlist(bookId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ ma_sach: bookId, so_luong: 1 })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            showToast('Đã thêm vào giỏ hàng!', 'success');
            updateCartCount();
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'danger');
        }
    });
}

// Address functions
function showAddAddressModal() {
    document.getElementById('addressModalTitle').textContent = 'Thêm địa chỉ mới';
    document.getElementById('addressForm').reset();
    document.getElementById('addressId').value = '';
    document.getElementById('addressModal').style.display = 'flex';
}

function closeAddressModal() {
    document.getElementById('addressModal').style.display = 'none';
}

function editAddress(id) {
    // Load address data and show modal
    document.getElementById('addressModalTitle').textContent = 'Chỉnh sửa địa chỉ';
    document.getElementById('addressId').value = id;
    document.getElementById('addressModal').style.display = 'flex';
}

function deleteAddress(id) {
    if (confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
        fetch(`/account/addresses/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(() => {
            showToast('Đã xóa địa chỉ!', 'success');
            location.reload();
        });
    }
}

function setDefaultAddress(id) {
    fetch(`/account/addresses/${id}/default`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(() => {
        showToast('Đã đặt làm địa chỉ mặc định!', 'success');
        location.reload();
    });
}

// Delete account
function confirmDeleteAccount() {
    if (confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác!')) {
        if (confirm('Xác nhận lần cuối: TẤT CẢ dữ liệu của bạn sẽ bị xóa vĩnh viễn!')) {
            // Delete account logic
            showToast('Chức năng đang phát triển', 'info');
        }
    }
}
</script>
@endpush
