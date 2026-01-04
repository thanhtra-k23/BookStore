<?php

namespace App\Http\Controllers;

use App\Models\YeuThich;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuThichController extends Controller
{
    /**
     * Display the wishlist
     */
    public function index()
    {
        $title = 'Danh sách yêu thích';
        
        // Yêu cầu đăng nhập để xem danh sách yêu thích
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('tb_warning', 'Vui lòng đăng nhập để xem danh sách yêu thích');
        }
        
        $yeuThich = YeuThich::byUser(Auth::id())
                           ->with('sach.tacGia', 'sach.theLoai', 'sach.nhaXuatBan')
                           ->latest()
                           ->paginate(12);

        return view('yeu_thich.index', compact('yeuThich', 'title'));
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        // Support both sach_id and ma_sach
        $sachId = $request->sach_id ?? $request->ma_sach;
        
        if (!$sachId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu mã sách'
            ], 400);
        }

        // Find book by ma_sach or id
        $sach = Sach::where('ma_sach', $sachId)->orWhere('id', $sachId)->first();
        
        if (!$sach) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sách'
            ], 404);
        }

        $isAdded = YeuThich::toggle(Auth::id(), $sach->id);
        $favoriteCount = YeuThich::getFavoriteCount(Auth::id());

        return response()->json([
            'success' => true,
            'is_favorite' => $isAdded,
            'message' => $isAdded ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích',
            'favorite_count' => $favoriteCount
        ]);
    }

    /**
     * Remove from favorites
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $yeuThich = YeuThich::where('id', $id)
                           ->where('nguoi_dung_id', Auth::id())
                           ->first();

        if (!$yeuThich) {
            return redirect()->back()
                ->with('tb_danger', 'Không tìm thấy sản phẩm trong danh sách yêu thích');
        }

        $yeuThich->delete();

        return redirect()->back()
            ->with('tb_success', 'Đã xóa khỏi danh sách yêu thích');
    }

    /**
     * Clear all favorites
     */
    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        YeuThich::byUser(Auth::id())->delete();

        return redirect()->back()
            ->with('tb_success', 'Đã xóa tất cả khỏi danh sách yêu thích');
    }

    /**
     * Check if book is favorite (for AJAX)
     */
    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $request->validate([
            'sach_id' => 'required|exists:sach,id'
        ]);

        $isFavorite = YeuThich::isFavorite(Auth::id(), $request->sach_id);

        return response()->json(['is_favorite' => $isFavorite]);
    }

    /**
     * Get favorite count for user
     */
    public function getCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = YeuThich::getFavoriteCount(Auth::id());
        
        return response()->json(['count' => $count]);
    }

    /**
     * Add multiple books to cart from favorites
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'sach_ids' => 'required|array',
            'sach_ids.*' => 'exists:sach,id'
        ]);

        $addedCount = 0;
        $errors = [];

        foreach ($request->sach_ids as $sachId) {
            $sach = Sach::find($sachId);
            
            if ($sach && $sach->canOrder(1)) {
                \App\Models\GioHang::addToCart(Auth::id(), $sachId, 1);
                $addedCount++;
            } else {
                $errors[] = $sach ? $sach->ten_sach : "Sách ID: {$sachId}";
            }
        }

        $message = "Đã thêm {$addedCount} sách vào giỏ hàng";
        
        if (!empty($errors)) {
            $message .= ". Một số sách không thể thêm: " . implode(', ', $errors);
        }

        return redirect()->back()
            ->with('tb_success', $message);
    }
}