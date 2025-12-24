<?php

namespace App\Http\Controllers;

use App\Models\Sach;
use App\Models\DonHang;
use App\Models\NguoiDung;
use App\Models\TheLoai;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Basic statistics
        $stats = [
            'total_books' => Sach::count(),
            'orders_today' => DonHang::whereDate('created_at', today())->count(),
            'revenue_month' => DonHang::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('trang_thai', '!=', 'da_huy')
                ->sum('tong_tien'),
            'total_customers' => NguoiDung::count(),
        ];

        // Recent orders (last 10) - simplified for now
        $recent_orders = collect(); // Empty collection for now
        
        // Low stock books (less than 10 items)
        $low_stock_books = Sach::with(['tacGia'])
            ->where('so_luong_ton', '<=', 10)
            ->where('trang_thai', 'active')
            ->orderBy('so_luong_ton', 'asc')
            ->limit(10)
            ->get();

        // Chart data for revenue (last 12 months)
        $revenue_data = $this->getRevenueChartData();
        
        // Chart data for categories
        $category_data = $this->getCategoryChartData();

        $chart_data = [
            'revenue' => $revenue_data,
            'categories' => $category_data
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recent_orders',
            'low_stock_books',
            'chart_data'
        ));
    }

    private function getRevenueChartData()
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('m/Y');
            
            $revenue = DonHang::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('trang_thai', '!=', 'da_huy')
                ->sum('tong_tien');
                
            $revenues[] = (float) $revenue;
        }

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getCategoryChartData()
    {
        $categories = TheLoai::withCount('sach')
            ->orderBy('sach_count', 'desc')
            ->limit(8)
            ->get()
            ->filter(function($category) {
                return $category->sach_count > 0;
            });

        return [
            'labels' => $categories->pluck('ten_the_loai')->toArray(),
            'data' => $categories->pluck('sach_count')->toArray()
        ];
    }

    public function getStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $stats = [
            // Books
            'books' => [
                'total' => Sach::count(),
                'active' => Sach::where('trang_thai', 'active')->count(),
                'low_stock' => Sach::where('so_luong_ton', '<=', 10)->count(),
                'out_of_stock' => Sach::where('so_luong_ton', 0)->count(),
            ],
            
            // Orders
            'orders' => [
                'today' => DonHang::whereDate('created_at', $today)->count(),
                'this_month' => DonHang::whereDate('created_at', '>=', $thisMonth)->count(),
                'this_year' => DonHang::whereDate('created_at', '>=', $thisYear)->count(),
                'pending' => DonHang::where('trang_thai', 'cho_xac_nhan')->count(),
                'confirmed' => DonHang::where('trang_thai', 'da_xac_nhan')->count(),
                'shipping' => DonHang::where('trang_thai', 'dang_giao')->count(),
                'completed' => DonHang::where('trang_thai', 'da_giao')->count(),
                'cancelled' => DonHang::where('trang_thai', 'da_huy')->count(),
            ],
            
            // Revenue
            'revenue' => [
                'today' => DonHang::whereDate('created_at', $today)
                    ->where('trang_thai', '!=', 'da_huy')
                    ->sum('tong_tien'),
                'this_month' => DonHang::whereDate('created_at', '>=', $thisMonth)
                    ->where('trang_thai', '!=', 'da_huy')
                    ->sum('tong_tien'),
                'this_year' => DonHang::whereDate('created_at', '>=', $thisYear)
                    ->where('trang_thai', '!=', 'da_huy')
                    ->sum('tong_tien'),
            ],
            
            // Customers
            'customers' => [
                'total' => NguoiDung::where('vai_tro', 'khach_hang')->count(),
                'new_today' => NguoiDung::where('vai_tro', 'khach_hang')
                    ->whereDate('created_at', $today)->count(),
                'new_this_month' => NguoiDung::where('vai_tro', 'khach_hang')
                    ->whereDate('created_at', '>=', $thisMonth)->count(),
            ],
            
            // Categories & Authors
            'categories' => TheLoai::count(),
            'authors' => TacGia::count(),
            'publishers' => NhaXuatBan::count(),
        ];

        return response()->json($stats);
    }

    public function getRevenueChart(Request $request)
    {
        $period = $request->get('period', '12months'); // 7days, 30days, 12months
        
        switch ($period) {
            case '7days':
                return $this->getRevenueLast7Days();
            case '30days':
                return $this->getRevenueLast30Days();
            case '12months':
            default:
                return $this->getRevenueChartData();
        }
    }

    private function getRevenueLast7Days()
    {
        $days = [];
        $revenues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');
            
            $revenue = DonHang::whereDate('created_at', $date)
                ->where('trang_thai', '!=', 'da_huy')
                ->sum('tong_tien');
                
            $revenues[] = (float) $revenue;
        }

        return [
            'labels' => $days,
            'data' => $revenues
        ];
    }

    private function getRevenueLast30Days()
    {
        $days = [];
        $revenues = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');
            
            $revenue = DonHang::whereDate('created_at', $date)
                ->where('trang_thai', '!=', 'da_huy')
                ->sum('tong_tien');
                
            $revenues[] = (float) $revenue;
        }

        return [
            'labels' => $days,
            'data' => $revenues
        ];
    }

    public function getTopSellingBooks(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $books = DB::table('chi_tiet_don_hang')
            ->join('sach', 'chi_tiet_don_hang.ma_sach', '=', 'sach.ma_sach')
            ->join('don_hang', 'chi_tiet_don_hang.ma_don_hang', '=', 'don_hang.ma_don_hang')
            ->join('tac_gia', 'sach.ma_tac_gia', '=', 'tac_gia.ma_tac_gia')
            ->where('don_hang.trang_thai', '!=', 'da_huy')
            ->select(
                'sach.ten_sach',
                'tac_gia.ten_tac_gia',
                'sach.gia_ban',
                DB::raw('SUM(chi_tiet_don_hang.so_luong) as total_sold'),
                DB::raw('SUM(chi_tiet_don_hang.thanh_tien) as total_revenue')
            )
            ->groupBy('sach.ma_sach', 'sach.ten_sach', 'tac_gia.ten_tac_gia', 'sach.gia_ban')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($books);
    }

    public function getRecentActivities()
    {
        $activities = collect();

        // Recent orders
        $recent_orders = DonHang::with('nguoiDung')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'icon' => 'fas fa-shopping-cart',
                    'color' => 'primary',
                    'title' => "Đơn hàng mới #{$order->ma_don}",
                    'description' => "Khách hàng: {$order->nguoiDung->ho_ten}",
                    'time' => $order->created_at,
                    'url' => route('donhang.show', $order->id)
                ];
            });

        // Recent books
        $recent_books = Sach::with('tacGia')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($book) {
                return [
                    'type' => 'book',
                    'icon' => 'fas fa-book',
                    'color' => 'success',
                    'title' => "Sách mới: {$book->ten_sach}",
                    'description' => "Tác giả: {$book->tacGia->ten_tac_gia}",
                    'time' => $book->created_at,
                    'url' => route('sach.show', $book->id)
                ];
            });

        // Recent customers
        $recent_customers = NguoiDung::where('vai_tro', 'khach_hang')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($customer) {
                return [
                    'type' => 'customer',
                    'icon' => 'fas fa-user-plus',
                    'color' => 'info',
                    'title' => "Khách hàng mới: {$customer->ho_ten}",
                    'description' => "Email: {$customer->email}",
                    'time' => $customer->created_at,
                    'url' => route('nguoidung.show', $customer->id)
                ];
            });

        $activities = $activities
            ->merge($recent_orders)
            ->merge($recent_books)
            ->merge($recent_customers)
            ->sortByDesc('time')
            ->take(10)
            ->values();

        return response()->json($activities);
    }
}