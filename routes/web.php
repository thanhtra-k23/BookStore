<?php

use App\Http\Controllers\{
    HomeController,
    SachController,
    TacGiaController,
    TheLoaiController,
    NhaXuatBanController,
    DonHangController,
    ChiTietDonHangController,
    NguoiDungController,
    GioHangController,
    YeuThichController,
    DanhGiaController,
    MaGiamGiaController,
    AdminController,
    AuthController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes - với Rate Limiting chống brute force
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:password-reset');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-reset');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/verify-email', [AuthController::class, 'showVerifyEmailNotice'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
});

// Public Routes - với Rate Limiting cho search
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pure', [HomeController::class, 'index'])->name('home.pure'); // Pure Blade version
Route::get('/original', [HomeController::class, 'indexOriginal'])->name('home.original'); // Original version
Route::get('/search', [HomeController::class, 'search'])->middleware('throttle:search')->name('search');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/authors', [HomeController::class, 'authors'])->name('authors');
Route::get('/author/{slug}', [HomeController::class, 'author'])->name('author');
Route::get('/book/{id}/{slug?}', [HomeController::class, 'bookDetail'])->name('book.detail');
Route::get('/books/{id}', [HomeController::class, 'bookDetail'])->name('books.detail');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/return-policy', [HomeController::class, 'returnPolicy'])->name('return-policy');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/new-books', [HomeController::class, 'newBooks'])->name('new-books');
Route::get('/bestsellers', [HomeController::class, 'bestsellers'])->name('bestsellers');
Route::get('/sale', [HomeController::class, 'sale'])->name('sale');

// User Routes (can be accessed by guests and authenticated users)
Route::get('/cart', [GioHangController::class, 'index'])->name('cart.index');
Route::get('/wishlist', [YeuThichController::class, 'index'])->name('wishlist.index');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [NguoiDungController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [NguoiDungController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [NguoiDungController::class, 'changePassword'])->name('profile.password');
    Route::get('/orders', [DonHangController::class, 'userOrders'])->name('orders');
    Route::get('/orders/{id}', [DonHangController::class, 'showUserOrder'])->name('orders.show');
    Route::get('/orders/{id}/track', [DonHangController::class, 'trackOrder'])->name('orders.track');
    Route::post('/orders/{id}/cancel', [DonHangController::class, 'cancelUserOrder'])->name('orders.cancel');
    Route::get('/orders/{id}/review', [DonHangController::class, 'reviewOrder'])->name('orders.review');
    
    // Account management
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/addresses', [NguoiDungController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [NguoiDungController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{id}', [NguoiDungController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{id}', [NguoiDungController::class, 'deleteAddress'])->name('addresses.delete');
        Route::post('/addresses/{id}/default', [NguoiDungController::class, 'setDefaultAddress'])->name('addresses.default');
        Route::get('/settings', [NguoiDungController::class, 'settings'])->name('settings');
    });
});

// Checkout routes - với Rate Limiting chống spam
Route::get('/checkout', [DonHangController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [DonHangController::class, 'processCheckout'])->middleware('throttle:checkout')->name('checkout.process');
Route::get('/checkout/success', [DonHangController::class, 'checkoutSuccess'])->name('checkout.success');

// Test route
Route::get('/test', function() { return 'Laravel is working!'; });

// Simple homepage test
Route::get('/simple', function() { 
    return view('layouts.app', ['title' => 'Test']); 
});

// Shopping Cart & Wishlist Routes - với Rate Limiting
Route::middleware(['throttle:cart'])->group(function () {
    // Shopping Cart
    Route::post('/cart/add', [GioHangController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [GioHangController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [GioHangController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [GioHangController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/quick-add', [GioHangController::class, 'quickAdd'])->name('cart.quick-add');
    
    // Wishlist
    Route::post('/wishlist/toggle', [YeuThichController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{id}', [YeuThichController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/clear', [YeuThichController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/add-to-cart', [YeuThichController::class, 'addToCart'])->name('wishlist.add-to-cart');
});

// User Routes (không cần rate limit riêng)
Route::group([], function () {
    // User Profile & Orders
    Route::get('/profile', [NguoiDungController::class, 'profile'])->name('profile');
    Route::get('/orders', [DonHangController::class, 'userOrders'])->name('orders');
    
    // Reviews
    Route::post('/reviews', [DanhGiaController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [DanhGiaController::class, 'destroy'])->name('reviews.destroy');
});

// Admin Routes - với Rate Limiting cho admin actions
Route::prefix('admin')->name('admin.')->middleware(['throttle:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // API Testing Dashboard
    Route::get('/api-test', [\App\Http\Controllers\ApiTestController::class, 'index'])->name('api-test');
    Route::post('/api-test/run', [\App\Http\Controllers\ApiTestController::class, 'testEndpoint'])->name('api-test.run');
    Route::get('/stats', [AdminController::class, 'getStats'])->name('stats');
    Route::get('/revenue-chart', [AdminController::class, 'getRevenueChart'])->name('revenue-chart');
    Route::get('/top-selling', [AdminController::class, 'getTopSellingBooks'])->name('top-selling');
    Route::get('/activities', [AdminController::class, 'getRecentActivities'])->name('activities');
    
    // Books
    Route::resource('sach', SachController::class);
    Route::post('sach/bulk-action', [SachController::class, 'bulkAction'])->name('sach.bulk-action');
    Route::get('sach/{id}/toggle-status', [SachController::class, 'toggleStatus'])->name('sach.toggle-status');
    
    // Authors
    Route::resource('tacgia', TacGiaController::class);
    Route::post('tacgia/bulk-action', [TacGiaController::class, 'bulkAction'])->name('tacgia.bulk-action');
    Route::get('tacgia/{tacGia}/toggle-status', [TacGiaController::class, 'toggleStatus'])->name('tacgia.toggle-status');
    Route::get('tacgia/export', [TacGiaController::class, 'export'])->name('tacgia.export');
    
    // Categories
    Route::resource('theloai', TheLoaiController::class);
    Route::post('theloai/bulk-action', [TheLoaiController::class, 'bulkAction'])->name('theloai.bulk-action');
    Route::get('theloai/{theLoai}/toggle-status', [TheLoaiController::class, 'toggleStatus'])->name('theloai.toggle-status');
    Route::get('theloai/export', [TheLoaiController::class, 'export'])->name('theloai.export');
    
    // Publishers
    Route::resource('nhaxuatban', NhaXuatBanController::class);
    Route::post('nhaxuatban/bulk-action', [NhaXuatBanController::class, 'bulkAction'])->name('nhaxuatban.bulk-action');
    Route::get('nhaxuatban/{nhaXuatBan}/toggle-status', [NhaXuatBanController::class, 'toggleStatus'])->name('nhaxuatban.toggle-status');
    Route::get('nhaxuatban/export', [NhaXuatBanController::class, 'export'])->name('nhaxuatban.export');
    
    // Orders
    Route::resource('donhang', DonHangController::class);
    Route::post('donhang/{id}/update-status', [DonHangController::class, 'updateStatus'])->name('donhang.update-status');
    Route::get('donhang/{id}/print', [DonHangController::class, 'print'])->name('donhang.print');
    
    // Order Details
    Route::resource('chitietdonhang', ChiTietDonHangController::class);
    Route::post('chitietdonhang/bulk-delete', [ChiTietDonHangController::class, 'bulkDelete'])->name('chitietdonhang.bulk-delete');
    Route::get('chitietdonhang/export', [ChiTietDonHangController::class, 'export'])->name('chitietdonhang.export');
    Route::get('chitietdonhang/by-order/{id}', [ChiTietDonHangController::class, 'getByOrder'])->name('chitietdonhang.by-order');
    
    // Users
    Route::resource('nguoidung', NguoiDungController::class);
    Route::post('nguoidung/bulk-action', [NguoiDungController::class, 'bulkAction'])->name('nguoidung.bulk-action');
    Route::get('nguoidung/{id}/verify-email', [NguoiDungController::class, 'verifyEmail'])->name('nguoidung.verify-email');
    Route::get('nguoidung/{id}/toggle-role', [NguoiDungController::class, 'toggleRole'])->name('nguoidung.toggle-role');
    Route::get('nguoidung/export', [NguoiDungController::class, 'export'])->name('nguoidung.export');
    
    // Discount Codes
    Route::get('magiamgia/generate-code', [MaGiamGiaController::class, 'generateCode'])->name('magiamgia.generate-code');
    Route::resource('magiamgia', MaGiamGiaController::class);
    Route::post('magiamgia/bulk-action', [MaGiamGiaController::class, 'bulkAction'])->name('magiamgia.bulk-action');
    Route::get('magiamgia/{id}/toggle-status', [MaGiamGiaController::class, 'toggleStatus'])->name('magiamgia.toggle-status');
});

// API Routes for AJAX calls - với Rate Limiting
Route::prefix('api')->middleware(['throttle:api'])->group(function () {
    Route::get('/cart/count', [GioHangController::class, 'getCount']);
    Route::get('/cart/items', [GioHangController::class, 'getItems']);
    Route::get('/wishlist/count', [YeuThichController::class, 'getCount']);
    Route::post('/discount/validate', [MaGiamGiaController::class, 'validate']);
    Route::get('/books/{id}/reviews', [DanhGiaController::class, 'getBookReviews']);
    Route::get('/search/autocomplete', [HomeController::class, 'searchAutocomplete']);
});