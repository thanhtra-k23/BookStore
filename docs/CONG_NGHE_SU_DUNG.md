# CÔNG NGHỆ SỬ DỤNG TRONG DỰ ÁN NHÀ SÁCH LARAVEL

## 1. TỔNG QUAN CÔNG NGHỆ

### Backend Framework
- **Laravel 10.x** - PHP Framework hiện đại với MVC pattern
- **PHP 8.1+** - Ngôn ngữ lập trình server-side

### Database
- **SQLite/MySQL** - Hệ quản trị CSDL quan hệ
- **Eloquent ORM** - Object-Relational Mapping của Laravel

### Frontend
- **Blade Template Engine** - Template engine của Laravel
- **Bootstrap 5** - CSS Framework responsive
- **Font Awesome 6** - Icon library
- **JavaScript/jQuery** - Client-side scripting

---

## 2. CHI TIẾT CÔNG NGHỆ THEO CONTROLLER

### HomeController - Trang công khai
| Hàm | Công nghệ sử dụng |
|-----|-------------------|
| `index()` | Eloquent Scopes (active, inStock), Eager Loading (with), Query Builder (orderBy, limit) |
| `search()` | Request Input, Conditional Query, Pagination với appends(), LengthAwarePaginator |
| `category()` | Route Model Binding, firstOrFail(), Pagination |
| `bookDetail()` | findOrFail(), Nested Eager Loading, Auth::check(), whereHas() |
| `searchAutocomplete()` | JSON Response, Collection map(), Multiple queries |

### GioHangController - Giỏ hàng
| Hàm | Công nghệ sử dụng |
|-----|-------------------|
| `index()` | Auth::check(), Eager Loading, Collection sum() với callback |
| `add()` | Request Input, JSON Response, Session Management, Eloquent create/update |
| `update()` | Request Validation, firstOrFail(), Redirect with flash |
| `remove()` | JSON Response, Eloquent delete(), number_format() |
| `getCount()` | JSON Response, Eloquent sum(), Session get() |
| `getItems()` | Collection map(), Eager Loading, collect()->values() |

### SachController - Quản lý sách (CRUD)
| Hàm | Công nghệ sử dụng |
|-----|-------------------|
| `index()` | Eager Loading, Query Scopes, Conditional Query, Pagination |
| `store()` | Request Validation, Str::slug(), File Upload (storeAs), Mass Assignment |
| `update()` | Storage::delete(), withTrashed(), Eloquent update() |
| `destroy()` | Business Logic check, Soft Delete, Storage delete |
| `bulkAction()` | whereIn(), whereDoesntHave(), Bulk update |

### AuthController - Xác thực
| Hàm | Công nghệ sử dụng |
|-----|-------------------|
| `login()` | Validator::make(), Auth::attempt(), Session regenerate |
| `register()` | Hash::make(), User::create(), Auth::login() |
| `logout()` | Auth::logout(), Session invalidate |
| `forgotPassword()` | Email validation, Password reset token |

### DonHangController - Đơn hàng
| Hàm | Công nghệ sử dụng |
|-----|-------------------|
| `userOrders()` | Auth::user(), Eager Loading, Pagination |
| `processCheckout()` | Request Validation, DB::transaction(), Mail::queue() |
| `updateStatus()` | Business Logic, Stock management, Email notification |
| `store()` | Transaction (DB::beginTransaction), Multiple creates |

---

## 3. ELOQUENT ORM - CHI TIẾT

### Query Scopes (Model Sach)
```php
// Scope lọc sách đang hoạt động
public function scopeActive($query) {
    return $query->where('trang_thai', 'active');
}

// Scope lọc sách còn hàng
public function scopeInStock($query) {
    return $query->where('so_luong_ton', '>', 0);
}

// Scope tìm kiếm
public function scopeSearch($query, $keyword) {
    return $query->where('ten_sach', 'like', "%{$keyword}%")
                 ->orWhere('mo_ta', 'like', "%{$keyword}%");
}
```

### Eager Loading
```php
// Tránh N+1 query problem
$sach = Sach::with(['tacGia', 'theLoai', 'nhaXuatBan'])->get();

// Nested eager loading với điều kiện
$book = Sach::with([
    'danhGias' => function ($query) {
        $query->where('trang_thai', 'approved')->with('nguoiDung');
    }
])->find($id);
```

### Relationships
```php
// One-to-Many: Sách thuộc về Tác giả
public function tacGia() {
    return $this->belongsTo(TacGia::class, 'ma_tac_gia', 'ma_tac_gia');
}

// One-to-Many: Tác giả có nhiều Sách
public function sach() {
    return $this->hasMany(Sach::class, 'ma_tac_gia', 'ma_tac_gia');
}

// Many-to-Many thông qua pivot table
public function donHangs() {
    return $this->belongsToMany(DonHang::class, 'chi_tiet_don_hang');
}
```

---

## 4. REQUEST VALIDATION

### Validation Rules
```php
$request->validate([
    'ten_sach' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'gia_ban' => 'required|numeric|min:0',
    'gia_khuyen_mai' => 'nullable|numeric|lt:gia_ban', // less than
    'anh_bia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'tac_gia_id' => 'required|exists:tac_gia,ma_tac_gia'
]);
```

### Custom Messages
```php
$messages = [
    'ten_sach.required' => 'Vui lòng nhập tên sách',
    'email.unique' => 'Email đã được sử dụng',
    'gia_khuyen_mai.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán'
];
```

---

## 5. FILE STORAGE

### Upload File
```php
if ($request->hasFile('anh_bia')) {
    $image = $request->file('anh_bia');
    $imageName = time() . '_' . Str::slug($request->ten_sach) . '.' . $image->getClientOriginalExtension();
    $imagePath = $image->storeAs('books', $imageName, 'public');
}
```

### Xóa File
```php
if (Storage::disk('public')->exists($sach->hinh_anh)) {
    Storage::disk('public')->delete($sach->hinh_anh);
}
```

---

## 6. AUTHENTICATION

### Login
```php
$credentials = $request->only('email', 'password');
if (Auth::attempt($credentials, $remember)) {
    $request->session()->regenerate();
    return redirect()->intended(route('home'));
}
```

### Kiểm tra đăng nhập
```php
if (Auth::check()) {
    $user = Auth::user();
    $userId = Auth::id();
}
```

### Hash Password
```php
$user->mat_khau = Hash::make($request->password);
```

---

## 7. SESSION MANAGEMENT

### Lưu vào Session
```php
session()->put('cart', $cart);
session(['key' => 'value']);
```

### Lấy từ Session
```php
$cart = session()->get('cart', []); // Mặc định là mảng rỗng
$value = session('key');
```

### Flash Message
```php
return redirect()->back()->with('tb_success', 'Thành công!');
return redirect()->route('home')->with('tb_danger', 'Lỗi!');
```

---

## 8. JSON RESPONSE (API)

```php
return response()->json([
    'success' => true,
    'message' => 'Đã thêm vào giỏ hàng',
    'data' => $items,
    'cart_count' => $count
]);

return response()->json(['error' => 'Not found'], 404);
```

---

## 9. DATABASE TRANSACTIONS

```php
DB::beginTransaction();
try {
    // Tạo đơn hàng
    $donHang = DonHang::create([...]);
    
    // Tạo chi tiết đơn hàng
    foreach ($items as $item) {
        ChiTietDonHang::create([...]);
        $sach->decrement('so_luong_ton', $item['so_luong']);
    }
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollback();
    throw $e;
}
```

---

## 10. BLADE TEMPLATE

### Layouts & Sections
```blade
{{-- Layout --}}
@yield('content')
@yield('scripts')

{{-- View --}}
@extends('layouts.app')
@section('content')
    ...
@endsection
```

### Directives
```blade
@if($condition)
@elseif($other)
@else
@endif

@foreach($items as $item)
    {{ $item->name }}
@endforeach

@auth
    Đã đăng nhập
@endauth

@guest
    Chưa đăng nhập
@endguest
```

### Components
```blade
@include('partials.book-card', ['book' => $sach])
@component('components.alert', ['type' => 'success'])
    Nội dung
@endcomponent
```

---

## 11. ROUTING

### Web Routes
```php
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::post('/cart/add', [GioHangController::class, 'add'])->name('cart.add');
Route::resource('sach', SachController::class);
```

### Route Groups
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('sach', SachController::class);
});
```

### API Routes
```php
Route::prefix('api')->group(function () {
    Route::get('/cart/count', [GioHangController::class, 'getCount']);
    Route::get('/cart/items', [GioHangController::class, 'getItems']);
});
```

---

## 12. MIDDLEWARE

### Rate Limiting
```php
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
```

### Authentication
```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [NguoiDungController::class, 'profile']);
});
```

---

## 13. MAIL

### Gửi Email
```php
Mail::to($user->email)->queue(new OrderConfirmation($order));
Mail::to($user->email)->send(new OrderStatusChanged($order, $oldStatus, $newStatus));
```

---

## 14. HELPER FUNCTIONS

### String Helpers
```php
Str::slug('Tên Sách Tiếng Việt'); // ten-sach-tieng-viet
Str::random(32); // Random string
```

### Number Format
```php
number_format($price, 0, ',', '.'); // 150.000
```

### Date/Time
```php
Carbon::now();
$order->created_at->format('d/m/Y H:i');
```

---

## 15. COLLECTION METHODS

```php
// Map - Transform dữ liệu
$items->map(function($item) {
    return ['id' => $item->id, 'name' => $item->name];
});

// Sum với callback
$total = $items->sum(function($item) {
    return $item->price * $item->quantity;
});

// Filter
$activeItems = $items->filter(function($item) {
    return $item->status === 'active';
});

// First/Last
$first = $items->first();
$last = $items->last();
```

---

## TÓM TẮT

Dự án sử dụng đầy đủ các tính năng của Laravel Framework:
- **MVC Pattern**: Controllers, Models, Views tách biệt rõ ràng
- **Eloquent ORM**: Relationships, Scopes, Eager Loading
- **Blade Templates**: Layouts, Components, Directives
- **Authentication**: Login, Register, Password Reset
- **Authorization**: Middleware, Gates, Policies
- **File Storage**: Upload, Delete, Public disk
- **Session & Cache**: Cart management, Flash messages
- **API Development**: JSON responses, Rate limiting
- **Database**: Migrations, Seeders, Transactions
