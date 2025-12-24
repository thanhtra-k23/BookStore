<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sach;
use App\Models\TheLoai;
use App\Models\TacGia;
use App\Models\DonHang;
use App\Models\GioHang;
use App\Models\YeuThich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Get books with pagination and filters
     */
    public function getBooks(Request $request)
    {
        $query = Sach::active()
                    ->inStock()
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->byCategory($request->category_id);
        }

        // Filter by author
        if ($request->has('author_id') && !empty($request->author_id)) {
            $query->byAuthor($request->author_id);
        }

        // Price range
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('gia_ban', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('gia_ban', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->input('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'popular':
                $query->popular();
                break;
            case 'rating':
                $query->highRated();
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $books = $query->paginate($request->input('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $books->items(),
            'pagination' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total()
            ]
        ]);
    }

    /**
     * Get book details
     */
    public function getBook($id)
    {
        $book = Sach::with([
            'tacGia', 'theLoai', 'nhaXuatBan',
            'danhGias' => function ($query) {
                $query->approved()->with('nguoiDung')->latest()->limit(5);
            }
        ])->findOrFail($id);

        // Increment view count
        $book->incrementView();

        // Related books
        $relatedBooks = Sach::active()
                           ->inStock()
                           ->where('the_loai_id', $book->the_loai_id)
                           ->where('id', '!=', $book->id)
                           ->with(['tacGia', 'theLoai'])
                           ->limit(6)
                           ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'book' => $book,
                'related_books' => $relatedBooks
            ]
        ]);
    }

    /**
     * Get categories
     */
    public function getCategories()
    {
        $categories = TheLoai::with('theLoaiCon')
                            ->parent()
                            ->withCount('sach')
                            ->orderBy('ten_the_loai', 'asc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get authors
     */
    public function getAuthors(Request $request)
    {
        $query = TacGia::withCount('sach');

        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        $authors = $query->orderBy('ten_tac_gia', 'asc')
                        ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $authors->items(),
            'pagination' => [
                'current_page' => $authors->currentPage(),
                'last_page' => $authors->lastPage(),
                'per_page' => $authors->perPage(),
                'total' => $authors->total()
            ]
        ]);
    }

    /**
     * Get user's cart
     */
    public function getCart()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $cart = GioHang::byUser(Auth::id())
                      ->with('sach.tacGia', 'sach.theLoai')
                      ->get();

        $total = $cart->sum('thanh_tien');
        $itemCount = $cart->sum('so_luong');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cart,
                'total' => $total,
                'item_count' => $itemCount
            ]
        ]);
    }

    /**
     * Get user's wishlist
     */
    public function getWishlist()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $wishlist = YeuThich::byUser(Auth::id())
                           ->with('sach.tacGia', 'sach.theLoai')
                           ->latest()
                           ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlist->pluck('sach')
        ]);
    }

    /**
     * Get user's orders
     */
    public function getOrders(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $query = DonHang::byUser(Auth::id())
                        ->with(['chiTiet.sach', 'maGiamGia']);

        if ($request->has('status') && !empty($request->status)) {
            $query->byStatus($request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')
                       ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ]);
    }

    /**
     * Get order details
     */
    public function getOrder($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $order = DonHang::where('id', $id)
                        ->where('nguoi_dung_id', Auth::id())
                        ->with(['chiTiet.sach.tacGia', 'maGiamGia'])
                        ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Get featured books for homepage
     */
    public function getFeaturedBooks()
    {
        $featuredBooks = Sach::active()
                            ->inStock()
                            ->with(['tacGia', 'theLoai'])
                            ->popular()
                            ->limit(8)
                            ->get();

        $newBooks = Sach::active()
                       ->inStock()
                       ->with(['tacGia', 'theLoai'])
                       ->orderBy('created_at', 'desc')
                       ->limit(8)
                       ->get();

        $saleBooks = Sach::active()
                        ->inStock()
                        ->onSale()
                        ->with(['tacGia', 'theLoai'])
                        ->limit(8)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'featured' => $featuredBooks,
                'new_arrivals' => $newBooks,
                'on_sale' => $saleBooks
            ]
        ]);
    }

    /**
     * Search suggestions
     */
    public function searchSuggestions(Request $request)
    {
        $keyword = $request->input('q', '');
        
        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $books = Sach::active()
                    ->where('ten_sach', 'like', "%{$keyword}%")
                    ->with('tacGia')
                    ->limit(5)
                    ->get(['id', 'ten_sach', 'duong_dan', 'tac_gia_id']);

        $authors = TacGia::where('ten_tac_gia', 'like', "%{$keyword}%")
                         ->limit(3)
                         ->get(['id', 'ten_tac_gia', 'duong_dan']);

        return response()->json([
            'success' => true,
            'data' => [
                'books' => $books,
                'authors' => $authors
            ]
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_books' => Sach::active()->count(),
            'total_categories' => TheLoai::count(),
            'total_authors' => TacGia::count(),
            'total_orders' => DonHang::count(),
            'pending_orders' => DonHang::byStatus(DonHang::TRANG_THAI_CHO_XAC_NHAN)->count(),
            'completed_orders' => DonHang::byStatus(DonHang::TRANG_THAI_DA_GIAO)->count(),
        ];

        if (Auth::check()) {
            $stats['user_orders'] = DonHang::byUser(Auth::id())->count();
            $stats['user_cart_items'] = GioHang::getCartItemCount(Auth::id());
            $stats['user_wishlist_items'] = YeuThich::getFavoriteCount(Auth::id());
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}