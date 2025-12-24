# 📚 BookStore - Laravel E-commerce Application

## 🎯 Mô tả dự án
Hệ thống bán sách trực tuyến được xây dựng bằng Laravel với giao diện hiện đại và trải nghiệm người dùng tối ưu.

## ✨ Tính năng chính

### 🛒 Nghiệp vụ bán hàng
- ✅ Trang chủ với sách nổi bật và mới nhất
- ✅ Danh mục sách theo thể loại
- ✅ Tìm kiếm nâng cao với bộ lọc
- ✅ Trang chi tiết sản phẩm với đánh giá
- ✅ Giỏ hàng và danh sách yêu thích
- ✅ Quy trình thanh toán hoàn chỉnh
- ✅ Hệ thống đánh giá và nhận xét

### 🎨 UX/UI
- ✅ Responsive design cho mọi thiết bị
- ✅ Pure Blade templates (không Bootstrap)
- ✅ Breadcrumb navigation
- ✅ Loading states và error handling
- ✅ Modern CSS với animations

### 🔧 Quản trị
- ✅ Dashboard quản trị viên
- ✅ Quản lý sách, tác giả, thể loại
- ✅ Quản lý đơn hàng và khách hàng
- ✅ Hệ thống mã giảm giá

## 🛠️ Công nghệ sử dụng
- **Backend**: Laravel Framework
- **Database**: MySQL + Eloquent ORM
- **Frontend**: Pure Blade Templates
- **Styling**: Custom CSS (Grid + Flexbox)
- **Icons**: Font Awesome
- **JavaScript**: Vanilla JS + AJAX

## 📋 Yêu cầu hệ thống
- PHP >= 8.0
- MySQL >= 5.7
- Composer
- Node.js (optional)

## 🚀 Cài đặt

1. Clone repository:
```bash
git clone https://github.com/thanhtra-k23/BookStore.git
cd BookStore
```

2. Cài đặt dependencies:
```bash
composer install
```

3. Tạo file .env:
```bash
cp .env.example .env
```

4. Cấu hình database trong .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookstore
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Chạy migrations:
```bash
php artisan migrate
```

7. Khởi động server:
```bash
php artisan serve
```

## 👥 Tài khoản demo
- **Admin**: admin@bookstore.vn / admin123
- **Customer**: customer@bookstore.vn / customer123

## 📊 Kết quả test
- ✅ 100% success rate (17/17 pages)
- ✅ All business workflows functional
- ✅ Mobile responsive
- ✅ Production ready

## 📱 Screenshots

### Trang chủ
![Homepage](https://via.placeholder.com/800x400/2563eb/ffffff?text=BookStore+Homepage)

### Trang chi tiết sách
![Book Detail](https://via.placeholder.com/800x400/16a34a/ffffff?text=Book+Detail+Page)

### Giỏ hàng
![Shopping Cart](https://via.placeholder.com/800x400/dc2626/ffffff?text=Shopping+Cart)

### Admin Dashboard
![Admin Dashboard](https://via.placeholder.com/800x400/7c3aed/ffffff?text=Admin+Dashboard)

## 🏗️ Cấu trúc dự án

```
BookStore/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── AdminController.php
│   │   └── ...
│   └── Models/
│       ├── Sach.php
│       ├── TheLoai.php
│       └── ...
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── pure-blade.blade.php
│       ├── home/
│       ├── books/
│       └── ...
├── public/
│   ├── css/
│   ├── images/
│   └── ...
└── database/
    └── migrations/
```

## 🔧 API Endpoints

### Public APIs
- `GET /api/cart/count` - Lấy số lượng sản phẩm trong giỏ
- `GET /api/wishlist/count` - Lấy số lượng sản phẩm yêu thích
- `POST /api/discount/validate` - Validate mã giảm giá

### Admin APIs
- `GET /admin/stats` - Thống kê dashboard
- `GET /admin/revenue-chart` - Biểu đồ doanh thu
- `GET /admin/top-selling` - Sách bán chạy

## 🧪 Testing

Chạy tests:
```bash
php artisan test
```

Kiểm tra code quality:
```bash
./vendor/bin/phpstan analyse
```

## 🚀 Deployment

### Heroku
1. Tạo app trên Heroku
2. Thêm MySQL addon
3. Cấu hình environment variables
4. Deploy từ GitHub

### Railway
1. Connect GitHub repository
2. Cấu hình database
3. Set environment variables
4. Deploy automatically

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📝 Changelog

### v1.0.0 (2024-12-24)
- ✅ Initial release
- ✅ Complete e-commerce functionality
- ✅ Admin dashboard
- ✅ Responsive design
- ✅ MySQL integration

## 🐛 Bug Reports

Nếu bạn tìm thấy bug, vui lòng tạo issue với:
- Mô tả chi tiết bug
- Steps to reproduce
- Expected behavior
- Screenshots (nếu có)

## 📄 License

MIT License - xem file [LICENSE](LICENSE) để biết thêm chi tiết.

## 👨‍💻 Tác giả

**Thành Trà** - [GitHub](https://github.com/thanhtra-k23)

## 🙏 Acknowledgments

- Laravel Framework team
- Font Awesome icons
- MySQL database
- All contributors

---

<div align="center">
  <p>Được phát triển với ❤️ bằng Laravel</p>
  <p>⭐ Nếu project này hữu ích, hãy cho một star nhé!</p>
</div>