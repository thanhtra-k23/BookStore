<?php

namespace App\Http\Controllers;

use App\Models\GioHang;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $title = 'Giỏ hàng của bạn';
        
        if (!Auth::check()) {
            // Show empty cart for guests
            $gioHang = collect();
            $tongTien = 0;
            $tongSoLuong = 0;
        } else {
            $gioHang = GioHang::byUser(Auth::id())
                              ->with('sach.tacGia', 'sach.theLoai')
                              ->get();

            $tongTien = $gioHang->sum('thanh_tien');
            $tongSoLuong = $gioHang->sum('so_luong');
        }

        return view('gio_hang.index', compact('gioHang', 'tongTien', 'tongSoLuong', 'title'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
            ], 401);
        }

        $request->validate([
            'sach_id' => 'required|exists:sach,id',
            'so_luong' => 'required|integer|min:1'
        ]);

        $sach = Sach::findOrFail($request->sach_id);

        if (!$sach->canOrder($request->so_luong)) {
            return response()->json([
                'success' => false,
                'message' => 'Sách không đủ số lượng hoặc không còn bán'
            ], 400);
        }

        $existingItem = GioHang::where('nguoi_dung_id', Auth::id())
                              ->where('sach_id', $request->sach_id)
                              ->first();

        if ($existingItem) {
            if (!$existingItem->canAddQuantity($request->so_luong)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thêm số lượng này vào giỏ hàng'
                ], 400);
            }
            
            $existingItem->increaseQuantity($request->so_luong);
            $message = 'Đã cập nhật số lượng trong giỏ hàng';
        } else {
            GioHang::create([
                'nguoi_dung_id' => Auth::id(),
                'sach_id' => $request->sach_id,
                'so_luong' => $request->so_luong
            ]);
            $message = 'Đã thêm vào giỏ hàng';
        }

        $cartCount = GioHang::getCartItemCount(Auth::id());

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'so_luong' => 'required|integer|min:1'
        ]);

        $gioHang = GioHang::where('id', $id)
                          ->where('nguoi_dung_id', Auth::id())
                          ->firstOrFail();

        if (!$gioHang->sach->canOrder($request->so_luong)) {
            return redirect()->back()
                ->with('tb_danger', 'Số lượng không hợp lệ');
        }

        $gioHang->update(['so_luong' => $request->so_luong]);

        return redirect()->back()
            ->with('tb_success', 'Đã cập nhật số lượng');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $gioHang = GioHang::where('id', $id)
                          ->where('nguoi_dung_id', Auth::id())
                          ->first();

        if (!$gioHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
            ], 404);
        }

        $gioHang->delete();

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
     * Clear entire cart
     */
    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        GioHang::clearCart(Auth::id());

        return redirect()->back()
            ->with('tb_success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng');
    }

    /**
     * Get cart count for AJAX
     */
    public function getCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = GioHang::getCartItemCount(Auth::id());
        
        return response()->json(['count' => $count]);
    }

    /**
     * Quick add to cart (for AJAX)
     */
    public function quickAdd(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $request->validate([
            'sach_id' => 'required|exists:sach,id'
        ]);

        $sach = Sach::findOrFail($request->sach_id);

        if (!$sach->canOrder(1)) {
            return response()->json([
                'success' => false,
                'message' => 'Sách không còn hàng'
            ], 400);
        }

        GioHang::addToCart(Auth::id(), $request->sach_id, 1);
        $cartCount = GioHang::getCartItemCount(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_count' => $cartCount
        ]);
    }
}