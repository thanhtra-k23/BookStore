<?php

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
     * Display the homepage using pure Blade and Eloquent ORM
     */
    public function index()
    {
        // Sử dụng Eloquent ORM để lấy dữ liệu từ MySQL
        
        // Sách nổi bật (sách có lượt xem cao)
        $sachNoiBat = Sach::active()
                         ->inStock()
                         ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                         ->orderBy('luot_xem', 'desc')
                         ->limit(8)
                         ->get();

        // Sách mới nhất
        $sachMoi = Sach::active()
                      ->inStock()
                      ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                      ->orderBy('created_at', 'desc')
                      ->limit(8)
                      ->get();

        // Danh mục sách với số lượng
        $theLoais = TheLoai::withCount(['sach' => function($query) {
                               $query->active()->inStock();
                           }])
                           ->orderBy('sach_count', 'desc')
                           ->limit(6)
                           ->get();

        // Thống kê tổng quan sử dụng Eloquent
        $tongSach = Sach::active()->count();
        $tongTacGia = TacGia::count();
        $tongTheLoai = TheLoai::count();
        $tongNguoiDung = NguoiDung::count();

        return view('home-pure', compact(
            'sachNoiBat',
            'sachMoi', 
            'theLoais',
            'tongSach',
            'tongTacGia',
            'tongTheLoai',
            'tongNguoiDung'
        ));
    }

    /**
     * Display original homepage (for comparison)
     */
    public function indexOriginal()
    {
        $title = 'Trang chủ - Nhà sách online';
        
        // Featured books
        $featuredBooks = Sach::active()
                            ->inStock()
                            ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                            ->orderBy('luot_xem', 'desc')
                            ->limit(8)
                            ->get();

        // New arrivals
        $newBooks = Sach::active()
                       ->inStock()
                       ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                       ->orderBy('created_at', 'desc')
                       ->limit(8)
                       ->get();

        // On sale books
        $saleBooks = Sach::active()
                        ->inStock()
                        ->onSale()
                        ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                        ->orderByRaw('(gia_ban - gia_khuyen_mai) DESC')
                        ->limit(8)
                        ->get();

        // Popular categories
        $popularCategories = TheLoai::withCount('sach')
                                   ->orderBy('sach_count', 'desc')
                                   ->limit(6)
                                   ->get();

        // Popular authors
        $popularAuthors = TacGia::withCount('sach')
                               ->orderBy('sach_count', 'desc')
                               ->limit(6)
                               ->get();

        // Statistics
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
     * Search books
     */
    public function search(Request $request)
    {
        $title = 'Kết quả tìm kiếm';
        $keyword = $request->input('q', '');
        
        // Initialize empty results
        $sach = collect();
        $totalResults = 0;
        
        if (!empty($keyword)) {
            $query = Sach::active()
                        ->with(['tacGia', 'theLoai', 'nhaXuatBan'])
                        ->search($keyword);

            // Filters
            if ($request->has('the_loai_id') && !empty($request->the_loai_id)) {
                $query->byCategory($request->the_loai_id);
            }

            if ($request->has('tac_gia_id') && !empty($request->tac_gia_id)) {
                $query->byAuthor($request->tac_gia_id);
            }

            if ($request->has('gia_min') && !empty($request->gia_min)) {
                $query->where('gia_ban', '>=', $request->gia_min);
            }

            if ($request->has('gia_max') && !empty($request->gia_max)) {
                $query->where('gia_ban', '<=', $request->gia_max);
            }

            // Sorting
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

            $sach = $query->paginate(12)->appends($request->query());
            $totalResults = $sach->total();
        } else {
            // Empty search - show empty paginated collection
            $sach = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 12, 1, ['path' => request()->url()]
            );
        }

        // Filter options
        $categories = TheLoai::orderBy('ten_the_loai', 'asc')->get();
        $authors = TacGia::orderBy('ten_tac_gia', 'asc')->get();

        return view('home.search', compact(
            'title', 'sach', 'keyword', 'categories', 'authors', 'totalResults'
        ));
    }

    /**
     * Display books by category
     */
    public function category($slug, Request $request)
    {
        $category = TheLoai::where('duong_dan', $slug)->firstOrFail();
        $title = 'Thể loại: ' . $category->ten_the_loai;

        $query = Sach::active()
                    ->inStock()
                    ->where('ma_the_loai', $category->ma_the_loai)
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

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
     * Display books by author
     */
    public function author($slug, Request $request)
    {
        $author = TacGia::where('duong_dan', $slug)->firstOrFail();
        $title = 'Tác giả: ' . $author->ten_tac_gia;

        $query = Sach::active()
                    ->inStock()
                    ->byAuthor($author->ma_tac_gia)
                    ->with(['tacGia', 'theLoai', 'nhaXuatBan']);

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
     * Display book details
     */
    public function bookDetail($id, $slug = null)
    {
        $book = Sach::with([
            'tacGia', 'theLoai', 'nhaXuatBan',
            'danhGias' => function ($query) {
                $query->where('trang_thai', 'approved')->with('nguoiDung')->latest();
            }
        ])->findOrFail($id);

        if ($slug && $slug !== $book->duong_dan) {
            return redirect()->route('book.detail', [$book->ma_sach, $book->duong_dan]);
        }

        // Increment view count
        $book->incrementView();

        // Related books
        $relatedBooks = Sach::active()
                           ->inStock()
                           ->where('ma_the_loai', $book->ma_the_loai)
                           ->where('ma_sach', '!=', $book->ma_sach)
                           ->with(['tacGia', 'theLoai'])
                           ->limit(6)
                           ->get();

        // Check if user can review
        $canReview = false;
        if (Auth::check()) {
            $hasPurchased = \App\Models\ChiTietDonHang::whereHas('donHang', function ($query) {
                $query->where('ma_nguoi_dung', Auth::id())
                      ->where('trang_thai', 'delivered');
            })->where('ma_sach', $book->ma_sach)->exists();

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
     * Display all categories
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
     * Display all authors
     */
    public function authors(Request $request)
    {
        $title = 'Tất cả tác giả';
        
        $query = TacGia::withCount('sach');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('ten_tac_gia', 'like', '%' . $request->search . '%');
        }

        $authors = $query->orderBy('ten_tac_gia', 'asc')->paginate(20);

        return view('home.authors', compact('title', 'authors'));
    }

    /**
     * Contact page
     */
    public function contact()
    {
        $title = 'Liên hệ';
        
        return view('home.contact', compact('title'));
    }

    /**
     * About page
     */
    public function about()
    {
        $title = 'Giới thiệu';
        
        return view('home.about', compact('title'));
    }
}