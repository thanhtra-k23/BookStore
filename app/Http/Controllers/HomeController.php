<?php
/**
 * =============================================================================
 * HomeController - Controller xử lý các trang công khai của website
 * =============================================================================
 * 
 * @package     App\Http\Controllers
 * @author      BookStore Team
 * @version     1.0.0
 * 
 * CÔNG NGHỆ SỬ DỤNG:
 * - Laravel Framework 10.x (PHP Framework)
 * - Eloquent ORM (Object-Relational Mapping để tương tác database)
 * - Blade Template Engine (Template engine của Laravel)
 * - Query Builder (Xây dựng truy vấn SQL)
 * - Pagination (Phân trang tự động)
 * - Route Model Binding (Tự động bind model từ route)
 * 
 * CÁC TÍNH NĂNG CHÍNH:
 * - Hiển thị trang chủ với sách nổi bật, sách mới
 * - Tìm kiếm sách với nhiều bộ lọc
 * - Hiển thị sách theo thể loại, tác giả
 * - Chi tiết sách với sách liên quan
 * - Autocomplete tìm kiếm (AJAX)
 * =============================================================================
 */

namespace App\Http\Controllers;

use App\Models\Sach;
use App\Models\TheLoai;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use App\Models\NguoiDung;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ website
     * 
     * CÔNG NGHỆ:
     * - Eloquent ORM: Sử dụng các scope (active(), inStock()) để lọc dữ liệu
     * - Eager Loading: with() để tải trước quan hệ, tránh N+1 query
     * - Query Builder: orderBy(), limit() để sắp xếp và giới hạn kết quả
     * - Blade View: Trả về view với compact() để truyền dữ liệu
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Trang chủ - Nhà sách online';
        
        // Sách nổi bật - sắp xếp theo lượt xem
        $featuredBooks = Sach::active()
                            ->inStock()
                            ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                            ->orderBy('luot_xem', 'desc')
                            ->limit(8)
                            ->get();

        // Sách mới nhất
        $newBooks = Sach::active()
                       ->inStock()
                       ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                       ->orderBy('created_at', 'desc')
                       ->limit(8)
                       ->get();

        // Sách đang giảm giá
        $saleBooks = Sach::active()
                        ->inStock()
                        ->onSale()
                        ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                        ->orderByRaw('(gia_ban - gia_khuyen_mai) DESC')
                        ->limit(8)
                        ->get();

        // Thể loại phổ biến - sắp xếp theo số lượng sách
        $popularCategories = TheLoai::withCount('sach')
                                   ->orderBy('sach_count', 'desc')
                                   ->limit(6)
                                   ->get();

        // Tác giả phổ biến
        $popularAuthors = TacGia::withCount('sach')
                               ->orderBy('sach_count', 'desc')
                               ->limit(6)
                               ->get();

        // Thống kê tổng quan
        $stats = [
            'total_books' => Sach::active()->count(),
            'total_categories' => TheLoai::count(),
            'total_authors' => TacGia::count(),
            'total_publishers' => NhaXuatBan::count(),
            'total_orders' => DonHang::count(),
        ];

        return view('home.index', compact(
            'title',
            'featuredBooks',
            'newBooks', 
            'saleBooks',
            'popularCategories',
            'popularAuthors',
            'stats'
        ));
    }

    /**
     * Hiển thị trang chủ phiên bản gốc (để so sánh)
     * 
     * CÔNG NGHỆ:
     * - Eloquent Scopes: onSale() để lọc sách đang giảm giá
     * - orderByRaw(): Sử dụng SQL thuần để sắp xếp phức tạp
     * - withCount(): Đếm số lượng quan hệ
     * 
     * @return \Illuminate\View\View
     */
    public function indexOriginal()
    {
        $title = 'Trang chủ - Nhà sách online';
        
        // Sách nổi bật - sắp xếp theo lượt xem
        $featuredBooks = Sach::active()
                            ->inStock()
                            ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                            ->orderBy('luot_xem', 'desc')
                            ->limit(8)
                            ->get();

        // Sách mới nhất
        $newBooks = Sach::active()
                       ->inStock()
                       ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                       ->orderBy('created_at', 'desc')
                       ->limit(8)
                       ->get();

        // Sách đang giảm giá
        // - onSale(): Scope lọc sách có giá khuyến mãi
        // - orderByRaw(): SQL thuần để tính và sắp xếp theo mức giảm giá
        $saleBooks = Sach::active()
                        ->inStock()
                        ->onSale()
                        ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                        ->orderByRaw('(gia_ban - gia_khuyen_mai) DESC')
                        ->limit(8)
                        ->get();

        // Thể loại phổ biến - sắp xếp theo số lượng sách
        $popularCategories = TheLoai::withCount('sach')
                                   ->orderBy('sach_count', 'desc')
                                   ->limit(6)
                                   ->get();

        // Tác giả phổ biến
        $popularAuthors = TacGia::withCount('sach')
                               ->orderBy('sach_count', 'desc')
                               ->limit(6)
                               ->get();

        // Thống kê tổng quan
        $stats = [
            'total_books' => Sach::active()->count(),
            'total_categories' => TheLoai::count(),
            'total_authors' => TacGia::count(),
            'total_publishers' => NhaXuatBan::count(),
        ];

        return view('home.index', compact(
            'title',
            'featuredBooks',
            'newBooks', 
            'saleBooks',
            'popularCategories',
            'popularAuthors',
            'stats'
        ));
    }

    /**
     * Tìm kiếm sách với nhiều bộ lọc
     * 
     * CÔNG NGHỆ:
     * - Request Input: $request->input() để lấy tham số từ URL
     * - Query Builder: Xây dựng query động dựa trên điều kiện
     * - Eloquent Scopes: search(), byCategory(), byAuthor()
     * - Pagination: paginate() với appends() để giữ query string
     * - LengthAwarePaginator: Tạo paginator rỗng khi không có kết quả
     * 
     * @param Request $request - HTTP Request chứa các tham số tìm kiếm
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $title = 'Kết quả tìm kiếm';
        $keyword = $request->input('q', ''); // Lấy từ khóa, mặc định rỗng
        
        // Khởi tạo kết quả rỗng
        $sach = collect();
        $totalResults = 0;
        
        if (!empty($keyword)) {
            // Xây dựng query với Eloquent
            $query = Sach::active()
                        ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                        ->search($keyword); // Scope tìm kiếm theo tên, mô tả

            // Áp dụng các bộ lọc nếu có
            // Filter theo thể loại
            if ($request->has('the_loai_id') && !empty($request->the_loai_id)) {
                $query->byCategory($request->the_loai_id);
            }

            // Filter theo tác giả
            if ($request->has('tac_gia_id') && !empty($request->tac_gia_id)) {
                $query->byAuthor($request->tac_gia_id);
            }

            // Filter theo khoảng giá
            if ($request->has('gia_min') && !empty($request->gia_min)) {
                $query->where('gia_ban', '>=', $request->gia_min);
            }

            if ($request->has('gia_max') && !empty($request->gia_max)) {
                $query->where('gia_ban', '<=', $request->gia_max);
            }

            // Sắp xếp kết quả theo tiêu chí
            $sortBy = $request->input('sort', 'relevance');
            switch ($sortBy) {
                case 'price_asc':
                    $query->orderBy('gia_ban', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('gia_ban', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('luot_xem', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('diem_trung_binh', 'desc');
                    break;
                default:
                    $query->orderBy('ten_sach', 'asc');
            }

            // Phân trang với appends() để giữ các query parameters
            $sach = $query->paginate(12)->appends($request->query());
            $totalResults = $sach->total();
        } else {
            // Tạo paginator rỗng khi không có từ khóa
            $sach = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 12, 1, ['path' => request()->url()]
            );
        }

        // Lấy danh sách thể loại và tác giả cho bộ lọc
        $categories = TheLoai::orderBy('ten_the_loai', 'asc')->get();
        $authors = TacGia::orderBy('ten_tac_gia', 'asc')->get();

        return view('home.search', compact(
            'title', 'sach', 'keyword', 'categories', 'authors', 'totalResults'
        ));
    }

    /**
     * Hiển thị sách theo thể loại
     * 
     * CÔNG NGHỆ:
     * - Route Model Binding: Tự động tìm TheLoai theo slug
     * - firstOrFail(): Trả về 404 nếu không tìm thấy
     * - Pagination với appends()
     * 
     * @param string $slug - Đường dẫn thân thiện của thể loại
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function category($slug, Request $request)
    {
        // Tìm thể loại theo slug, trả về 404 nếu không tìm thấy
        $category = TheLoai::where('duong_dan', $slug)->firstOrFail();
        $title = 'Thể loại: ' . $category->ten_the_loai;

        $query = Sach::active()
                    ->inStock()
                    ->where('ma_the_loai', $category->ma_the_loai)
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Sắp xếp theo tiêu chí
        $sortBy = $request->input('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'popular':
                $query->orderBy('luot_xem', 'desc');
                break;
            case 'rating':
                $query->orderBy('diem_trung_binh', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $books = $query->paginate(12)->appends($request->query());

        return view('home.category', compact('title', 'category', 'books'));
    }

    /**
     * Hiển thị sách theo tác giả
     * 
     * CÔNG NGHỆ:
     * - Eloquent Scope: byAuthor() để lọc theo tác giả
     * - firstOrFail(): Trả về 404 nếu không tìm thấy
     * 
     * @param string $slug - Đường dẫn thân thiện của tác giả
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function author($slug, Request $request)
    {
        $author = TacGia::where('duong_dan', $slug)->firstOrFail();
        $title = 'Tác giả: ' . $author->ten_tac_gia;

        $query = Sach::active()
                    ->inStock()
                    ->byAuthor($author->ma_tac_gia)
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Sắp xếp
        $sortBy = $request->input('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'popular':
                $query->orderBy('luot_xem', 'desc');
                break;
            case 'rating':
                $query->orderBy('diem_trung_binh', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $books = $query->paginate(12)->appends($request->query());

        return view('home.author', compact('title', 'author', 'books'));
    }

    /**
     * Hiển thị chi tiết sách
     * 
     * CÔNG NGHỆ:
     * - findOrFail(): Tìm theo ID, trả về 404 nếu không có
     * - Nested Eager Loading: with(['danhGias.nguoiDung'])
     * - Closure trong with() để lọc và sắp xếp quan hệ
     * - incrementView(): Tăng lượt xem (cập nhật database)
     * - Auth::check(): Kiểm tra đăng nhập
     * - whereHas(): Lọc theo điều kiện của quan hệ
     * 
     * @param int $id - ID của sách
     * @param string|null $slug - Đường dẫn thân thiện (tùy chọn)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function bookDetail($id, $slug = null)
    {
        // Eager loading với điều kiện lọc đánh giá
        $book = Sach::with([
            'tacGia', 'theLoai', 'nhaXuatBan',
            'danhGias' => function ($query) {
                // Chỉ lấy đánh giá đã duyệt, kèm thông tin người dùng
                $query->where('trang_thai', 'approved')->with('nguoiDung')->latest();
            }
        ])->findOrFail($id);

        // Redirect nếu slug không khớp (SEO friendly)
        if ($slug && $slug !== $book->duong_dan) {
            return redirect()->route('book.detail', [$book->ma_sach, $book->duong_dan]);
        }

        // Tăng lượt xem sách
        $book->incrementView();

        // Lấy sách liên quan (cùng thể loại)
        $relatedBooks = Sach::active()
                           ->inStock()
                           ->where('ma_the_loai', $book->ma_the_loai)
                           ->where('ma_sach', '!=', $book->ma_sach)
                           ->with(['tacGia', 'theLoai'])
                           ->limit(6)
                           ->get();

        // Kiểm tra quyền đánh giá
        // - Phải đăng nhập
        // - Đã mua sách và đơn hàng đã giao
        // - Chưa đánh giá sách này
        $canReview = false;
        if (Auth::check()) {
            // Kiểm tra đã mua sách chưa (whereHas để lọc theo quan hệ)
            $hasPurchased = \App\Models\ChiTietDonHang::whereHas('donHang', function ($query) {
                $query->where('ma_nguoi_dung', Auth::id())
                      ->where('trang_thai', 'delivered');
            })->where('ma_sach', $book->ma_sach)->exists();

            // Kiểm tra đã đánh giá chưa
            $hasReviewed = \App\Models\DanhGia::where('ma_sach', $book->ma_sach)
                                             ->where('ma_nguoi_dung', Auth::id())
                                             ->exists();

            $canReview = $hasPurchased && !$hasReviewed;
        }

        $title = $book->ten_sach;

        return view('books.detail', compact(
            'title', 'book', 'relatedBooks', 'canReview'
        ));
    }

    /**
     * Hiển thị tất cả thể loại
     * 
     * CÔNG NGHỆ:
     * - withCount(): Đếm số sách trong mỗi thể loại
     * - orderBy(): Sắp xếp theo tên
     * 
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        $title = 'Tất cả thể loại';
        
        $categories = TheLoai::withCount('sach')
                            ->orderBy('ten_the_loai', 'asc')
                            ->get();

        return view('home.categories', compact('title', 'categories'));
    }

    /**
     * Hiển thị tất cả tác giả với phân trang
     * 
     * CÔNG NGHỆ:
     * - Conditional Query: Thêm điều kiện tìm kiếm nếu có
     * - LIKE query: Tìm kiếm gần đúng
     * - Pagination: paginate(20)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function authors(Request $request)
    {
        $title = 'Tất cả tác giả';
        
        $query = TacGia::withCount('sach');

        // Tìm kiếm theo tên tác giả
        if ($request->has('search') && !empty($request->search)) {
            $query->where('ten_tac_gia', 'like', '%' . $request->search . '%');
        }

        $authors = $query->orderBy('ten_tac_gia', 'asc')->paginate(20);

        return view('home.authors', compact('title', 'authors'));
    }

    /**
     * Trang liên hệ
     * 
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        $title = 'Liên hệ';
        
        return view('home.contact', compact('title'));
    }

    /**
     * Trang giới thiệu
     * 
     * @return \Illuminate\View\View
     */
    public function about()
    {
        $title = 'Giới thiệu';
        
        return view('home.about', compact('title'));
    }

    /**
     * Trang câu hỏi thường gặp
     * 
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        $title = 'Câu hỏi thường gặp';
        
        return view('home.faq', compact('title'));
    }

    /**
     * Trang chính sách vận chuyển
     * 
     * @return \Illuminate\View\View
     */
    public function shippingPolicy()
    {
        $title = 'Chính sách vận chuyển';
        
        return view('home.shipping-policy', compact('title'));
    }

    /**
     * Trang chính sách đổi trả
     * 
     * @return \Illuminate\View\View
     */
    public function returnPolicy()
    {
        $title = 'Chính sách đổi trả';
        
        return view('home.return-policy', compact('title'));
    }

    /**
     * Trang chính sách bảo mật
     * 
     * @return \Illuminate\View\View
     */
    public function privacyPolicy()
    {
        $title = 'Chính sách bảo mật';
        
        return view('home.privacy-policy', compact('title'));
    }

    /**
     * Trang sách mới nhất
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function newBooks(Request $request)
    {
        $title = 'Sách mới nhất';
        
        $query = Sach::active()
                    ->inStock()
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Sắp xếp
        $sortBy = $request->input('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'popular':
                $query->orderBy('luot_xem', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $books = $query->paginate(12)->appends($request->query());

        return view('home.new-books', compact('title', 'books'));
    }

    /**
     * Trang sách bán chạy
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function bestsellers(Request $request)
    {
        $title = 'Sách bán chạy';
        
        $query = Sach::active()
                    ->inStock()
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Sắp xếp
        $sortBy = $request->input('sort', 'popular');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('luot_xem', 'desc');
        }

        $books = $query->paginate(12)->appends($request->query());

        return view('home.bestsellers', compact('title', 'books'));
    }

    /**
     * Trang sách khuyến mãi
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function sale(Request $request)
    {
        $title = 'Khuyến mãi';
        
        $query = Sach::active()
                    ->inStock()
                    ->onSale() // Chỉ lấy sách có giá khuyến mãi
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

        // Sắp xếp
        $sortBy = $request->input('sort', 'discount');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('gia_khuyen_mai', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_khuyen_mai', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('luot_xem', 'desc');
                break;
            default:
                // Sắp xếp theo mức giảm giá (giảm nhiều nhất trước)
                $query->orderByRaw('(gia_ban - gia_khuyen_mai) DESC');
        }

        $books = $query->paginate(12)->appends($request->query());

        return view('home.sale', compact('title', 'books'));
    }

    /**
     * Autocomplete tìm kiếm (AJAX endpoint)
     * 
     * CÔNG NGHỆ:
     * - JSON Response: response()->json() trả về dữ liệu JSON
     * - Collection map(): Transform dữ liệu trước khi trả về
     * - Multiple queries: Tìm kiếm song song sách, tác giả, thể loại
     * - select(): Chỉ lấy các cột cần thiết để tối ưu
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAutocomplete(Request $request)
    {
        $keyword = $request->input('q', '');
        
        // Yêu cầu ít nhất 2 ký tự
        if (strlen($keyword) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Tìm kiếm sách
        $books = Sach::active()
            ->where(function($query) use ($keyword) {
                $query->where('ten_sach', 'like', "%{$keyword}%")
                      ->orWhere('mo_ta', 'like', "%{$keyword}%");
            })
            ->with(['tacGia'])
            ->select('ma_sach', 'ten_sach', 'gia_ban', 'gia_khuyen_mai', 'anh_bia', 'slug')
            ->limit(5)
            ->get()
            ->map(function($book) {
                // Transform dữ liệu cho frontend
                return [
                    'type' => 'book',
                    'id' => $book->ma_sach,
                    'title' => $book->ten_sach,
                    'subtitle' => $book->tacGia->ten_tac_gia ?? '',
                    'price' => $book->gia_khuyen_mai ?? $book->gia_ban,
                    'image' => $book->anh_bia_url ?? '/images/no-image.png',
                    'url' => route('book.detail', ['id' => $book->ma_sach, 'slug' => $book->slug])
                ];
            });

        // Tìm kiếm tác giả
        $authors = TacGia::where('ten_tac_gia', 'like', "%{$keyword}%")
            ->withCount('sach')
            ->limit(3)
            ->get()
            ->map(function($author) {
                return [
                    'type' => 'author',
                    'id' => $author->ma_tac_gia,
                    'title' => $author->ten_tac_gia,
                    'subtitle' => $author->sach_count . ' sách',
                    'url' => route('author', $author->duong_dan)
                ];
            });

        // Tìm kiếm thể loại
        $categories = TheLoai::where('ten_the_loai', 'like', "%{$keyword}%")
            ->withCount('sach')
            ->limit(3)
            ->get()
            ->map(function($category) {
                return [
                    'type' => 'category',
                    'id' => $category->ma_the_loai,
                    'title' => $category->ten_the_loai,
                    'subtitle' => $category->sach_count . ' sách',
                    'url' => route('category', $category->duong_dan)
                ];
            });

        return response()->json([
            'suggestions' => [
                'books' => $books,
                'authors' => $authors,
                'categories' => $categories
            ]
        ]);
    }
}
