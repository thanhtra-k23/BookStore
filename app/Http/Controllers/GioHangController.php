<?php
/**
 * =============================================================================
 * GioHangController - Controller quản lý giỏ hàng
 * =============================================================================
 * 
 * @package     App\Http\Controllers
 * @author      BookStore Team
 * @version     1.0.0
 * 
 * CÔNG NGHỆ SỬ DỤNG:
 * - Laravel Framework 10.x (PHP Framework)
 * - Eloquent ORM (Tương tác database)
 * - Session Management (Lưu giỏ hàng cho khách)
 * - Authentication (Xác thực người dùng)
 * - JSON Response (API endpoints)
 * - Request Validation (Kiểm tra dữ liệu đầu vào)
 * 
 * CÁC TÍNH NĂNG CHÍNH:
 * - Hiển thị giỏ hàng
 * - Thêm sản phẩm vào giỏ (hỗ trợ cả guest và user)
 * - Cập nhật số lượng
 * - Xóa sản phẩm
 * - API endpoints cho AJAX
 * =============================================================================
 */

namespace App\Http\Controllers;

use App\Models\GioHang;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     * 
     * CÔNG NGHỆ:
     * - Auth::check(): Kiểm tra trạng thái đăng nhập
     * - Eloquent with(): Eager loading quan hệ sach.tacGia, sach.theLoai
     * - Collection sum(): Tính tổng với callback function
     * - Null coalescing: ?? để xử lý giá khuyến mãi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Giỏ hàng của bạn';
        
        // Yêu cầu đăng nhập để xem giỏ hàng
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('tb_warning', 'Vui lòng đăng nhập để xem giỏ hàng');
        }
        
        // Lấy giỏ hàng từ database cho user đã đăng nhập
        // Eager loading để tránh N+1 query
        $gioHang = GioHang::where('ma_nguoi_dung', Auth::id())
                          ->with('sach.tacGia', 'sach.theLoai')
                          ->get();

        // Tính tổng tiền với Collection sum() và callback
        $tongTien = $gioHang->sum(function($item) {
            // Ưu tiên giá khuyến mãi nếu có
            $price = $item->sach->gia_khuyen_mai ?? $item->sach->gia_ban;
            return $price * $item->so_luong;
        });
        $tongSoLuong = $gioHang->sum('so_luong');

        return view('cart.index', compact('gioHang', 'tongTien', 'tongSoLuong', 'title'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * 
     * CÔNG NGHỆ:
     * - Request input: Lấy dữ liệu từ request (hỗ trợ nhiều tên field)
     * - JSON Response: Trả về JSON cho AJAX request
     * - Eloquent find(): Tìm sách theo ID
     * - Model method: canOrder() kiểm tra có thể đặt hàng không
     * - Authentication: Yêu cầu đăng nhập để thêm giỏ hàng
     * - Eloquent create/update: Tạo mới hoặc cập nhật giỏ hàng
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        // KIỂM TRA ĐĂNG NHẬP - Yêu cầu đăng nhập để thêm vào giỏ hàng
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng',
                'login_url' => route('login')
            ], 401);
        }

        // Hỗ trợ cả ma_sach và sach_id để linh hoạt
        $sachId = $request->ma_sach ?? $request->sach_id;
        $soLuong = $request->so_luong ?? 1;

        // Validate input
        if (!$sachId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin sách'
            ], 400);
        }

        // Tìm sách trong database
        $sach = Sach::find($sachId);

        if (!$sach) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sách'
            ], 404);
        }

        // Kiểm tra có thể đặt hàng không (còn hàng, đang bán)
        if (!$sach->canOrder($soLuong)) {
            return response()->json([
                'success' => false,
                'message' => 'Sách không đủ số lượng hoặc không còn bán'
            ], 400);
        }

        // USER ĐÃ ĐĂNG NHẬP: Lưu vào database
        
        // Kiểm tra sách đã có trong giỏ chưa
        $existingItem = GioHang::where('ma_nguoi_dung', Auth::id())
                              ->where('ma_sach', $sachId)
                              ->first();

        if ($existingItem) {
            // Cập nhật số lượng nếu đã có
            $newQty = $existingItem->so_luong + $soLuong;
            if ($newQty > $sach->so_luong_ton) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thêm số lượng này vào giỏ hàng'
                ], 400);
            }
            $existingItem->update(['so_luong' => $newQty]);
            $message = 'Đã cập nhật số lượng trong giỏ hàng';
        } else {
            // Tạo mới item trong giỏ hàng
            GioHang::create([
                'ma_nguoi_dung' => Auth::id(),
                'ma_sach' => $sachId,
                'so_luong' => $soLuong,
                'gia_tai_thoi_diem' => $sach->gia_khuyen_mai ?? $sach->gia_ban
            ]);
            $message = 'Đã thêm vào giỏ hàng';
        }
        
        // Đếm tổng số lượng trong giỏ
        $cartCount = GioHang::where('ma_nguoi_dung', Auth::id())->sum('so_luong');

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     * 
     * CÔNG NGHỆ:
     * - Request Validation: validate() với rules
     * - Eloquent where() chain: Lọc theo nhiều điều kiện
     * - firstOrFail(): Trả về 404 nếu không tìm thấy
     * - Redirect with flash message
     * 
     * @param Request $request
     * @param int $id - ID của item trong giỏ hàng
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Yêu cầu đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate số lượng
        $request->validate([
            'so_luong' => 'required|integer|min:1'
        ]);

        // Tìm item trong giỏ hàng của user hiện tại
        $gioHang = GioHang::where('id', $id)
                          ->where('nguoi_dung_id', Auth::id())
                          ->firstOrFail();

        // Kiểm tra số lượng tồn kho
        if (!$gioHang->sach->canOrder($request->so_luong)) {
            return redirect()->back()
                ->with('tb_danger', 'Số lượng không hợp lệ');
        }

        // Cập nhật số lượng
        $gioHang->update(['so_luong' => $request->so_luong]);

        return redirect()->back()
            ->with('tb_success', 'Đã cập nhật số lượng');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * 
     * CÔNG NGHỆ:
     * - JSON Response: Trả về JSON cho AJAX
     * - Eloquent delete(): Xóa record
     * - Static method: GioHang::getCartItemCount() để đếm
     * - number_format(): Format số tiền
     * 
     * @param int $id - ID của item trong giỏ hàng
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Tìm item trong giỏ hàng của user
        $gioHang = GioHang::where('id', $id)
                          ->where('nguoi_dung_id', Auth::id())
                          ->first();

        if (!$gioHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
            ], 404);
        }

        // Xóa item
        $gioHang->delete();

        // Lấy thông tin giỏ hàng mới
        $cartCount = GioHang::getCartItemCount(Auth::id());
        $cartTotal = GioHang::getCartTotal(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng',
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 0, ',', '.')
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * 
     * CÔNG NGHỆ:
     * - Static method: GioHang::clearCart() để xóa tất cả
     * - Redirect with flash message
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Xóa tất cả items trong giỏ hàng của user
        GioHang::clearCart(Auth::id());

        return redirect()->back()
            ->with('tb_success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng');
    }

    /**
     * API: Lấy số lượng sản phẩm trong giỏ
     * 
     * CÔNG NGHỆ:
     * - JSON Response: Trả về JSON cho AJAX
     * - Eloquent sum(): Tính tổng số lượng
     * - Session: Lấy giỏ hàng từ session cho khách
     * - array_sum() + array_column(): Tính tổng từ mảng
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        if (Auth::check()) {
            // User đăng nhập: Lấy từ database
            $count = GioHang::where('ma_nguoi_dung', Auth::id())->sum('so_luong');
        } else {
            // Khách: Lấy từ session
            $cart = session()->get('cart', []);
            $count = array_sum(array_column($cart, 'so_luong'));
        }
        
        return response()->json(['count' => $count]);
    }

    /**
     * API: Lấy danh sách sản phẩm trong giỏ
     * 
     * CÔNG NGHỆ:
     * - Collection map(): Transform dữ liệu
     * - Eager loading: with('sach.tacGia')
     * - Null coalescing: ?? để xử lý giá trị null
     * - collect()->values(): Chuyển mảng thành collection
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems()
    {
        if (Auth::check()) {
            // User đăng nhập: Lấy từ database và transform
            $items = GioHang::where('ma_nguoi_dung', Auth::id())
                           ->with('sach.tacGia')
                           ->get()
                           ->map(function($item) {
                               return [
                                   'ma_sach' => $item->ma_sach,
                                   'ten_sach' => $item->sach->ten_sach ?? 'N/A',
                                   'tac_gia' => $item->sach->tacGia->ten_tac_gia ?? 'Chưa cập nhật',
                                   'gia_ban' => $item->sach->gia_ban ?? 0,
                                   'gia_khuyen_mai' => $item->sach->gia_khuyen_mai,
                                   'hinh_anh' => $item->sach->anh_bia_url ?? '',
                                   'so_luong' => $item->so_luong
                               ];
                           });
        } else {
            // Khách: Lấy từ session
            $cart = session()->get('cart', []);
            $items = collect($cart)->values();
        }
        
        return response()->json(['items' => $items]);
    }

    /**
     * API: Thêm nhanh vào giỏ hàng (AJAX)
     * 
     * CÔNG NGHỆ:
     * - Request Validation: validate() với exists rule
     * - findOrFail(): Tìm hoặc trả về 404
     * - Static method: GioHang::addToCart()
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickAdd(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        // Validate sách tồn tại
        $request->validate([
            'sach_id' => 'required|exists:sach,id'
        ]);

        $sach = Sach::findOrFail($request->sach_id);

        // Kiểm tra còn hàng
        if (!$sach->canOrder(1)) {
            return response()->json([
                'success' => false,
                'message' => 'Sách không còn hàng'
            ], 400);
        }

        // Thêm vào giỏ với số lượng 1
        GioHang::addToCart(Auth::id(), $request->sach_id, 1);
        $cartCount = GioHang::getCartItemCount(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_count' => $cartCount
        ]);
    }
}
