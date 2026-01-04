# TỔNG HỢP NGHIỆP VỤ TRANG ADMIN

## 1. Quản lý Kho sách (Catalog Management) ✅

### 1.1 Quản lý Sách (CRUD)
- **Route**: `/admin/sach`
- **Controller**: `SachController`
- **Views**: `resources/views/sach/`
- **Chức năng**:
  - ✅ Danh sách sách với phân trang, tìm kiếm, lọc
  - ✅ Thêm sách mới (tên, tác giả, NXB, giá, số lượng, mô tả)
  - ✅ Sửa thông tin sách
  - ✅ Xóa sách (soft delete)
  - ✅ Upload ảnh bìa sách
  - ✅ Quản lý trạng thái (active/inactive)
  - ✅ Bulk actions (xóa nhiều, đổi trạng thái)

### 1.2 Quản lý Thể loại
- **Route**: `/admin/theloai`
- **Controller**: `TheLoaiController`
- **Chức năng**:
  - ✅ CRUD thể loại
  - ✅ Hỗ trợ thể loại cha-con (parent_id)
  - ✅ Tự động tạo slug

### 1.3 Quản lý Tác giả
- **Route**: `/admin/tacgia`
- **Controller**: `TacGiaController`
- **Chức năng**:
  - ✅ CRUD tác giả
  - ✅ Tiểu sử, quốc tịch
  - ✅ Ảnh đại diện

### 1.4 Quản lý Nhà xuất bản
- **Route**: `/admin/nhaxuatban`
- **Controller**: `NhaXuatBanController`
- **Chức năng**:
  - ✅ CRUD nhà xuất bản
  - ✅ Thông tin liên hệ

---

## 2. Quản lý Đơn hàng (Order Management) ✅

### 2.1 Danh sách đơn hàng
- **Route**: `/admin/donhang`
- **Controller**: `DonHangController`
- **Chức năng**:
  - ✅ Danh sách đơn hàng với phân trang
  - ✅ Lọc theo trạng thái
  - ✅ Tìm kiếm theo mã đơn, khách hàng
  - ✅ Sắp xếp theo ngày, tổng tiền

### 2.2 Chi tiết đơn hàng
- **Chức năng**:
  - ✅ Xem chi tiết sản phẩm trong đơn
  - ✅ Thông tin khách hàng, địa chỉ giao
  - ✅ Lịch sử thay đổi trạng thái

### 2.3 Cập nhật trạng thái
- **Các trạng thái**:
  - `cho_xac_nhan` → Chờ xác nhận
  - `da_xac_nhan` → Đã xác nhận
  - `dang_giao` → Đang giao hàng
  - `da_giao` → Đã giao
  - `da_huy` → Đã hủy
- **Chức năng**:
  - ✅ Cập nhật trạng thái đơn hàng
  - ✅ Gửi email thông báo khi thay đổi trạng thái

### 2.4 In hóa đơn
- **Route**: `/admin/donhang/{id}/print`
- **Chức năng**:
  - ✅ Xuất hóa đơn dạng HTML để in

---

## 3. Quản lý Người dùng & Phân quyền ✅

### 3.1 Quản lý khách hàng
- **Route**: `/admin/nguoidung`
- **Controller**: `NguoiDungController`
- **Chức năng**:
  - ✅ Danh sách người dùng
  - ✅ Xem thông tin chi tiết
  - ✅ Xem lịch sử đơn hàng
  - ✅ Khóa/mở khóa tài khoản

### 3.2 Phân quyền
- **Vai trò**:
  - `admin` - Quản trị viên (toàn quyền)
  - `user` - Khách hàng
- **Middleware**: `AdminMiddleware`
- **Chức năng**:
  - ✅ Kiểm tra quyền truy cập admin
  - ✅ Chuyển đổi vai trò người dùng

---

## 4. Marketing & Khuyến mãi ✅

### 4.1 Mã giảm giá (Coupon)
- **Route**: `/admin/magiamgia`
- **Controller**: `MaGiamGiaController`
- **Chức năng**:
  - ✅ Tạo mã giảm giá
  - ✅ Loại giảm: phần trăm hoặc số tiền cố định
  - ✅ Thiết lập ngày bắt đầu/kết thúc
  - ✅ Giới hạn số lần sử dụng
  - ✅ Điều kiện áp dụng (giá trị đơn tối thiểu)
  - ✅ Bật/tắt mã giảm giá

### 4.2 Sách khuyến mãi
- **Chức năng**:
  - ✅ Thiết lập giá khuyến mãi cho từng sách
  - ✅ Hiển thị % giảm giá trên trang sản phẩm

---

## 5. Thống kê & Báo cáo (Analytics) ✅

### 5.1 Dashboard
- **Route**: `/admin/dashboard`
- **Hiển thị**:
  - ✅ Tổng số sách, đơn hàng hôm nay
  - ✅ Doanh thu tháng
  - ✅ Số khách hàng
  - ✅ Đơn hàng theo trạng thái

### 5.2 Biểu đồ doanh thu
- **Chức năng**:
  - ✅ Biểu đồ doanh thu 7 ngày/30 ngày/12 tháng
  - ✅ Cập nhật realtime qua AJAX

### 5.3 Sách bán chạy
- **Chức năng**:
  - ✅ Top 10 sách bán chạy nhất
  - ✅ Số lượng đã bán, doanh thu

### 5.4 Cảnh báo tồn kho
- **Chức năng**:
  - ✅ Danh sách sách sắp hết hàng (≤10)
  - ✅ Hiển thị trên dashboard

### 5.5 Phân bố thể loại
- **Chức năng**:
  - ✅ Biểu đồ tròn phân bố sách theo thể loại

---

## 6. API Endpoints

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/admin/stats` | GET | Lấy thống kê tổng quan |
| `/admin/revenue-chart` | GET | Dữ liệu biểu đồ doanh thu |
| `/admin/top-selling` | GET | Sách bán chạy |
| `/admin/activities` | GET | Hoạt động gần đây |
| `/api/cart/count` | GET | Số lượng giỏ hàng |
| `/api/discount/validate` | POST | Kiểm tra mã giảm giá |

---

## 7. Cấu trúc Routes Admin

```php
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Resources
    Route::resource('sach', SachController::class);
    Route::resource('tacgia', TacGiaController::class);
    Route::resource('theloai', TheLoaiController::class);
    Route::resource('nhaxuatban', NhaXuatBanController::class);
    Route::resource('donhang', DonHangController::class);
    Route::resource('nguoidung', NguoiDungController::class);
    Route::resource('magiamgia', MaGiamGiaController::class);
    
    // Custom actions
    Route::post('donhang/{id}/update-status', [DonHangController::class, 'updateStatus']);
    Route::get('donhang/{id}/print', [DonHangController::class, 'print']);
});
```

---

## 8. Giao diện Admin

### Layout: `layouts/admin.blade.php` (MỚI)
- **Sidebar cố định bên trái** với navigation links
- **Header với đồng hồ thời gian thực** (cập nhật mỗi giây)
- **Quick stats bar** hiển thị:
  - Đơn hàng hôm nay
  - Doanh thu hôm nay
  - Khách hàng mới
  - Sách sắp hết
- **Dropdown menus** cho thông báo và user profile
- **Responsive design** với mobile toggle
- **Stats API** tự động refresh mỗi 60 giây
- CSS inline (không phụ thuộc file CSS bên ngoài)

### Dashboard: `admin/dashboard.blade.php`
- Stats cards với icons
- Quick action buttons (6 nút tắt)
- Biểu đồ doanh thu (Chart.js) - 7 ngày/30 ngày/12 tháng
- Biểu đồ phân bố thể loại (Doughnut chart)
- Bảng đơn hàng gần đây
- Cảnh báo tồn kho
- Sách bán chạy (Top 10)

### API Stats Endpoint: `/admin/stats`
Trả về JSON với các trường:
```json
{
  "orders_today": 5,
  "revenue_today": 1500000,
  "new_users": 2,
  "low_stock": 3,
  "pending_orders": 4,
  "books": {...},
  "orders": {...},
  "revenue": {...},
  "customers": {...}
}
```

---

*Cập nhật: 24/12/2024*
