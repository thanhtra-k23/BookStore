# Tính năng dành cho Khách hàng - BookStore

## Tổng quan
Tài liệu này mô tả các tính năng dành cho khách hàng đã được triển khai trong hệ thống nhà sách trực tuyến.

## 1. Quản lý Tài khoản

### 1.1 Đăng ký / Đăng nhập
- **Route**: `/register`, `/login`
- **Controller**: `AuthController`
- Đăng ký tài khoản với email
- Đăng nhập bằng email và mật khẩu
- Quên mật khẩu và đặt lại mật khẩu

### 1.2 Hồ sơ cá nhân
- **Route**: `/profile`
- **View**: `resources/views/account/profile.blade.php`
- **Controller**: `NguoiDungController@profile`

**Các tab trong trang hồ sơ:**
1. **Thông tin cá nhân**: Cập nhật họ tên, số điện thoại, địa chỉ, ngày sinh
2. **Đơn hàng của tôi**: Xem danh sách đơn hàng, lọc theo trạng thái
3. **Sổ địa chỉ**: Quản lý nhiều địa chỉ giao hàng
4. **Danh sách yêu thích**: Xem và quản lý sách yêu thích
5. **Đánh giá của tôi**: Xem các đánh giá đã gửi
6. **Cài đặt tài khoản**: Đổi mật khẩu, cài đặt thông báo

## 2. Tìm kiếm & Khám phá

### 2.1 Tìm kiếm thông minh (Autocomplete)
- **Route**: `/api/search/autocomplete`
- **Controller**: `HomeController@searchAutocomplete`
- Gợi ý sách, tác giả, thể loại khi gõ từ khóa
- Hiển thị kết quả theo nhóm với hình ảnh và giá

### 2.2 Tìm kiếm nâng cao
- **Route**: `/search`
- **View**: `resources/views/home/search.blade.php`
- Lọc theo thể loại, tác giả, khoảng giá
- Sắp xếp theo giá, mới nhất, phổ biến, đánh giá

### 2.3 Duyệt theo danh mục
- **Route**: `/category/{slug}`
- **View**: `resources/views/home/category.blade.php`

### 2.4 Duyệt theo tác giả
- **Route**: `/author/{slug}`
- **View**: `resources/views/home/author.blade.php`

## 3. Giỏ hàng & Thanh toán

### 3.1 Giỏ hàng
- **Route**: `/cart`
- **View**: `resources/views/cart/index.blade.php`
- **Controller**: `GioHangController`
- Hỗ trợ cả khách (session) và người dùng đăng nhập (database)
- Thêm/xóa/cập nhật số lượng sản phẩm
- Áp dụng mã giảm giá

### 3.2 Thanh toán
- **Route**: `/checkout`
- **View**: `resources/views/checkout/index.blade.php`
- Nhập thông tin giao hàng
- Chọn phương thức thanh toán (COD, chuyển khoản, thẻ)

## 4. Quản lý Đơn hàng

### 4.1 Danh sách đơn hàng
- **Route**: `/orders`
- **View**: Tích hợp trong `profile.blade.php`
- Lọc theo trạng thái: Tất cả, Chờ xử lý, Đang giao, Hoàn thành

### 4.2 Chi tiết đơn hàng
- **Route**: `/orders/{id}`
- **View**: `resources/views/account/order-detail.blade.php`
- Xem thông tin chi tiết đơn hàng
- Xem danh sách sản phẩm đã đặt

### 4.3 Theo dõi đơn hàng
- **Route**: `/orders/{id}/track`
- **View**: `resources/views/account/order-track.blade.php`
- Timeline trạng thái đơn hàng
- Hiển thị tiến trình giao hàng

### 4.4 Hủy đơn hàng
- **Route**: `/orders/{id}/cancel` (POST)
- **Controller**: `DonHangController@cancelUserOrder`
- Chỉ hủy được đơn hàng đang "Chờ xác nhận"

## 5. Danh sách Yêu thích (Wishlist)

### 5.1 Xem danh sách
- **Route**: `/wishlist`
- **View**: `resources/views/yeu_thich/index.blade.php`
- **Controller**: `YeuThichController@index`

### 5.2 Thêm/Xóa yêu thích
- **Route**: `/wishlist/toggle` (POST)
- **Controller**: `YeuThichController@toggle`
- Toggle trạng thái yêu thích của sách

### 5.3 Thêm tất cả vào giỏ
- **Route**: `/wishlist/add-to-cart` (POST)
- Thêm tất cả sách trong wishlist vào giỏ hàng

## 6. Đánh giá & Nhận xét

### 6.1 Đánh giá sản phẩm
- **Route**: `/orders/{id}/review`
- **View**: `resources/views/account/order-review.blade.php`
- Chỉ đánh giá được sách đã mua và đã giao
- Chọn số sao (1-5) và viết nhận xét

### 6.2 Gửi đánh giá
- **Route**: `/reviews` (POST)
- **Controller**: `DanhGiaController@store`
- Đánh giá được gửi và chờ admin duyệt

## 7. Thông báo

### 7.1 Email thông báo
- Xác nhận đơn hàng
- Cập nhật trạng thái đơn hàng
- Thông báo khuyến mãi (tùy chọn)

### 7.2 Cài đặt thông báo
- Bật/tắt nhận email
- Bật/tắt thông báo khuyến mãi
- Bật/tắt thông báo sách mới

## API Endpoints

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| GET | `/api/cart/count` | Lấy số lượng sản phẩm trong giỏ |
| GET | `/api/wishlist/count` | Lấy số lượng sách yêu thích |
| GET | `/api/search/autocomplete` | Tìm kiếm gợi ý |
| GET | `/api/books/{id}/reviews` | Lấy đánh giá của sách |
| POST | `/api/discount/validate` | Kiểm tra mã giảm giá |

## Files đã tạo/cập nhật

### Views
- `resources/views/account/profile.blade.php` - Trang hồ sơ
- `resources/views/account/order-detail.blade.php` - Chi tiết đơn hàng
- `resources/views/account/order-track.blade.php` - Theo dõi đơn hàng
- `resources/views/account/order-review.blade.php` - Đánh giá đơn hàng
- `resources/views/yeu_thich/index.blade.php` - Danh sách yêu thích

### Controllers
- `NguoiDungController.php` - Thêm profile(), updateProfile(), changePassword()
- `DonHangController.php` - Thêm showUserOrder(), trackOrder(), cancelUserOrder(), reviewOrder()
- `YeuThichController.php` - Cập nhật toggle() hỗ trợ ma_sach
- `HomeController.php` - Thêm searchAutocomplete()

### Routes
- Thêm routes cho profile, orders, addresses
- Thêm API route cho search autocomplete

### Layout
- `pure-blade.blade.php` - Thêm search autocomplete JavaScript

---
*Cập nhật: {{ date('d/m/Y') }}*
