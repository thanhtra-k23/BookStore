# 👥 DANH SÁCH TÀI KHOẢN HỆ THỐNG NHÀ SÁCH

## 🔐 THÔNG TIN ĐĂNG NHẬP

### 👑 TÀI KHOẢN ADMIN

| Thông tin | Chi tiết |
|-----------|----------|
| **📧 Email** | `admin@bookstore.vn` |
| **🔑 Mật khẩu** | `admin123` |
| **👨‍💼 Họ tên** | Administrator |
| **🎭 Vai trò** | Admin (Quản trị viên) |
| **🌐 Đăng nhập tại** | http://127.0.0.1:8000/login |
| **🏠 Dashboard** | http://127.0.0.1:8000/admin/dashboard |

**Quyền hạn Admin:**
- ✅ Quản lý sách (CRUD)
- ✅ Quản lý thể loại (CRUD)
- ✅ Quản lý tác giả (CRUD)
- ✅ Quản lý nhà xuất bản (CRUD)
- ✅ Quản lý đơn hàng
- ✅ Quản lý người dùng
- ✅ Quản lý mã giảm giá
- ✅ Xem thống kê và báo cáo

---

### 🛒 TÀI KHOẢN KHÁCH HÀNG

#### Khách hàng chính:
| Thông tin | Chi tiết |
|-----------|----------|
| **📧 Email** | `customer@bookstore.vn` |
| **🔑 Mật khẩu** | `customer123` |
| **👨‍💼 Họ tên** | Nguyễn Văn Khách |
| **📱 Số điện thoại** | 0987654321 |
| **📍 Địa chỉ** | TP.HCM, Việt Nam |
| **🎭 Vai trò** | Customer (Khách hàng) |

#### Khách hàng phụ 1:
| Thông tin | Chi tiết |
|-----------|----------|
| **📧 Email** | `lan.tran@gmail.com` |
| **🔑 Mật khẩu** | `123456` |
| **👨‍💼 Họ tên** | Trần Thị Lan |
| **📱 Số điện thoại** | 0912345678 |
| **📍 Địa chỉ** | Đà Nẵng, Việt Nam |
| **🎭 Vai trò** | Customer (Khách hàng) |

#### Khách hàng phụ 2:
| Thông tin | Chi tiết |
|-----------|----------|
| **📧 Email** | `nam.le@gmail.com` |
| **🔑 Mật khẩu** | `123456` |
| **👨‍💼 Họ tên** | Lê Văn Nam |
| **📱 Số điện thoại** | 0923456789 |
| **📍 Địa chỉ** | Hải Phòng, Việt Nam |
| **🎭 Vai trò** | Customer (Khách hàng) |

**Quyền hạn Khách hàng:**
- ✅ Xem danh sách sách
- ✅ Tìm kiếm sách
- ✅ Xem chi tiết sách
- ✅ Thêm sách vào giỏ hàng
- ✅ Thêm sách vào danh sách yêu thích
- ✅ Đặt hàng và thanh toán
- ✅ Xem lịch sử đơn hàng
- ✅ Quản lý thông tin cá nhân
- ✅ Đánh giá và nhận xét sách

---

## 🚀 HƯỚNG DẪN SỬ DỤNG

### Bước 1: Truy cập trang đăng nhập
```
🌐 URL: http://127.0.0.1:8000/login
```

### Bước 2: Chọn tài khoản muốn đăng nhập
- **Admin**: Sử dụng `admin@bookstore.vn` / `admin123`
- **Khách hàng**: Sử dụng một trong các tài khoản customer ở trên

### Bước 3: Sau khi đăng nhập
- **Admin**: Tự động chuyển đến `/admin/dashboard`
- **Khách hàng**: Ở lại trang chủ với menu đã đăng nhập

---

## 🔧 TÍNH NĂNG THEO VAI TRÒ

### 👑 Admin Dashboard
```
📊 Thống kê tổng quan
├── 📚 Quản lý sách
├── 📂 Quản lý thể loại  
├── ✍️ Quản lý tác giả
├── 🏢 Quản lý nhà xuất bản
├── 👥 Quản lý người dùng
├── 🛒 Quản lý đơn hàng
├── 🎫 Quản lý mã giảm giá
└── 📈 Báo cáo và thống kê
```

### 🛒 Customer Features
```
🏠 Trang chủ
├── 🔍 Tìm kiếm sách
├── 📚 Danh sách sách theo thể loại
├── 🛒 Giỏ hàng
├── ❤️ Danh sách yêu thích
├── 📦 Lịch sử đơn hàng
├── 👤 Thông tin cá nhân
└── 💳 Thanh toán
```

---

## 📝 LƯU Ý QUAN TRỌNG

### 🔒 Bảo mật:
- Mật khẩu mặc định nên được thay đổi sau lần đăng nhập đầu tiên
- Admin có quyền cao nhất, cần bảo vệ thông tin đăng nhập
- Khách hàng chỉ có quyền truy cập các tính năng mua sắm

### 🆕 Tạo tài khoản mới:
- Khách hàng có thể tự đăng ký tại: http://127.0.0.1:8000/register
- Admin có thể tạo tài khoản mới trong dashboard
- Tất cả tài khoản mới mặc định là vai trò "customer"

### 🔄 Quên mật khẩu:
- Có tính năng "Quên mật khẩu" trên trang đăng nhập
- Email reset sẽ được gửi đến địa chỉ email đã đăng ký

---

## 🎯 DEMO DATA

Hệ thống đã có sẵn:
- **3 cuốn sách** với hình ảnh đẹp
- **2 thể loại** (Văn học, Khoa học)
- **2 tác giả** (Nguyễn Du, Nam Cao)
- **2 nhà xuất bản**
- **4 tài khoản người dùng** (1 admin + 3 customer)

---

**🚀 HỆ THỐNG SẴN SÀNG SỬ DỤNG!**

Truy cập: http://127.0.0.1:8000 để bắt đầu khám phá!