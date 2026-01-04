# BẢO MẬT HỆ THỐNG - SECURITY FEATURES

## 1. Tổng quan

Hệ thống Nhà Sách Online được xây dựng với các tính năng bảo mật đầy đủ theo tiêu chuẩn Laravel.

---

## 2. Rate Limiting (Giới hạn tần suất)

### 2.1 Cấu hình trong `AppServiceProvider.php`

| Loại | Giới hạn | Mục đích |
|------|----------|----------|
| `login` | 5 lần/phút | Chống brute force đăng nhập |
| `register` | 3 lần/phút | Chống spam đăng ký |
| `password-reset` | 3 lần/phút | Chống spam quên mật khẩu |
| `cart` | 30 lần/phút | Giới hạn thao tác giỏ hàng |
| `checkout` | 5 lần/phút | Chống spam đặt hàng |
| `search` | 30 lần/phút | Giới hạn tìm kiếm |
| `api` | 60 lần/phút | Giới hạn API calls |
| `admin` | 100 lần/phút | Giới hạn admin actions |

### 2.2 Áp dụng trong Routes

```php
// Đăng nhập - 5 lần/phút
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

// Checkout - 5 lần/phút
Route::post('/checkout/process', [DonHangController::class, 'processCheckout'])
    ->middleware('throttle:checkout');

// API - 60 lần/phút
Route::prefix('api')->middleware(['throttle:api'])->group(function () {
    // ...
});
```

### 2.3 Thông báo lỗi

Khi vượt quá giới hạn, hệ thống sẽ hiển thị thông báo tiếng Việt:
- "Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau 1 phút."
- "Bạn đã thử đăng ký quá nhiều lần. Vui lòng thử lại sau 1 phút."

---

## 3. CSRF Protection

### 3.1 Tự động bảo vệ

Laravel tự động bảo vệ tất cả các form POST/PUT/DELETE với CSRF token.

```blade
<form method="POST" action="/login">
    @csrf
    <!-- form fields -->
</form>
```

### 3.2 AJAX Requests

```javascript
// Tự động thêm CSRF token vào header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

---

## 4. Mã hóa mật khẩu

### 4.1 Bcrypt Hashing

```php
// Khi đăng ký
$user->password = bcrypt($request->password);

// Khi kiểm tra
if (Hash::check($request->password, $user->password)) {
    // Đăng nhập thành công
}
```

### 4.2 Độ mạnh mật khẩu

Validation yêu cầu:
- Tối thiểu 8 ký tự
- Có thể thêm: chữ hoa, chữ thường, số, ký tự đặc biệt

---

## 5. SQL Injection Prevention

### 5.1 Eloquent ORM

Tất cả queries sử dụng Eloquent ORM với prepared statements:

```php
// An toàn - sử dụng Eloquent
$sach = Sach::where('ten_sach', 'like', "%{$search}%")->get();

// An toàn - sử dụng Query Builder
$sach = DB::table('sach')
    ->where('ten_sach', 'like', "%{$search}%")
    ->get();
```

### 5.2 Không sử dụng Raw Queries

Tránh sử dụng raw SQL queries với user input.

---

## 6. XSS Prevention

### 6.1 Blade Escaping

```blade
{{-- Tự động escape HTML --}}
{{ $user->name }}

{{-- Không escape (chỉ dùng khi cần thiết) --}}
{!! $trustedHtml !!}
```

### 6.2 Content Security Policy

Headers được cấu hình để ngăn chặn inline scripts không mong muốn.

---

## 7. Phân quyền (Authorization)

### 7.1 Middleware

```php
// AdminMiddleware.php
public function handle($request, Closure $next)
{
    if (!auth()->check() || auth()->user()->vai_tro !== 'admin') {
        abort(403, 'Không có quyền truy cập');
    }
    return $next($request);
}
```

### 7.2 Vai trò người dùng

| Vai trò | Quyền hạn |
|---------|-----------|
| `admin` | Toàn quyền quản trị |
| `user` | Mua hàng, xem đơn hàng |
| `guest` | Xem sách, giỏ hàng session |

---

## 8. Session Security

### 8.1 Cấu hình Session

```php
// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'lax',
```

### 8.2 Session Regeneration

```php
// Sau khi đăng nhập
$request->session()->regenerate();

// Sau khi đăng xuất
$request->session()->invalidate();
$request->session()->regenerateToken();
```

---

## 9. Validation

### 9.1 Form Validation

```php
$request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
    'so_dien_thoai' => 'required|regex:/^[0-9]{10,11}$/',
]);
```

### 9.2 Custom Error Messages

```php
$messages = [
    'email.required' => 'Vui lòng nhập email',
    'email.email' => 'Email không hợp lệ',
    'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
];
```

---

## 10. File Upload Security

### 10.1 Validation

```php
$request->validate([
    'hinh_anh' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
]);
```

### 10.2 Storage

- Files được lưu trong `storage/app/public`
- Không cho phép execute files
- Rename files với unique ID

---

## 11. Checklist Bảo mật

| Tính năng | Trạng thái |
|-----------|------------|
| ✅ Rate Limiting | Đã cấu hình |
| ✅ CSRF Protection | Tự động |
| ✅ Password Hashing | Bcrypt |
| ✅ SQL Injection | Eloquent ORM |
| ✅ XSS Prevention | Blade escaping |
| ✅ Authorization | Middleware |
| ✅ Session Security | Configured |
| ✅ Input Validation | Form validation |
| ✅ File Upload | Validated |
| ✅ HTTPS Ready | Configured |

---

*Tài liệu bảo mật - Cập nhật: 24/12/2024*
