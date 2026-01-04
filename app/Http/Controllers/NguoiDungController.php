<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class NguoiDungController extends Controller
{
    /**
     * Display user profile (for frontend)
     */
    public function profile()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('tb_info', 'Vui lòng đăng nhập để xem hồ sơ');
        }

        // Get user orders
        $orders = \App\Models\DonHang::where('nguoi_dung_id', $user->id)
            ->with(['chiTiet.sach'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get wishlist
        $wishlist = \App\Models\YeuThich::where('nguoi_dung_id', $user->id)
            ->with(['sach.tacGia'])
            ->get();

        // Get reviews
        $reviews = \App\Models\DanhGia::where('nguoi_dung_id', $user->id)
            ->with(['sach'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get addresses (if table exists)
        $addresses = collect();
        try {
            if (\Schema::hasTable('dia_chi')) {
                $addresses = \App\Models\DiaChi::where('nguoi_dung_id', $user->id)->get();
            }
        } catch (\Exception $e) {
            // Table doesn't exist, use empty collection
        }

        $title = 'Tài khoản của tôi - BookStore';

        return view('account.profile', compact(
            'user', 
            'orders', 
            'wishlist', 
            'reviews', 
            'addresses',
            'title'
        ))->with([
            'orderCount' => $orders->count(),
            'wishlistCount' => $wishlist->count()
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:500',
            'ngay_sinh' => 'nullable|date'
        ]);

        $user->update($request->only(['ho_ten', 'so_dien_thoai', 'dia_chi', 'ngay_sinh']));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật thông tin thành công']);
        }

        return redirect()->back()->with('tb_success', 'Cập nhật thông tin thành công');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        if (!Hash::check($request->current_password, $user->mat_khau)) {
            return redirect()->back()->with('tb_danger', 'Mật khẩu hiện tại không đúng');
        }

        $user->update(['mat_khau' => $request->new_password]);

        return redirect()->back()->with('tb_success', 'Đổi mật khẩu thành công');
    }

    /**
     * Display user orders (for frontend)
     */
    public function orders()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('tb_info', 'Vui lòng đăng nhập để xem đơn hàng');
        }

        $orders = \App\Models\DonHang::where('nguoi_dung_id', $user->id)
            ->with(['chiTiet.sach'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $title = 'Đơn hàng của tôi - BookStore';

        return view('account.orders', compact('orders', 'title'));
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $title = 'Quản lý người dùng';

        $query = NguoiDung::query();

        // Filter by role
        if ($request->filled('vai_tro')) {
            $query->where('vai_tro', $request->vai_tro);
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            if ($request->verified === '1') {
                $query->whereNotNull('xac_minh_email_luc');
            } else {
                $query->whereNull('xac_minh_email_luc');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['ho_ten', 'email', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $nguoiDung = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => NguoiDung::count(),
            'admins' => NguoiDung::where('vai_tro', 'quan_tri')->count(),
            'verified' => NguoiDung::whereNotNull('xac_minh_email_luc')->count(),
            'unverified' => NguoiDung::whereNull('xac_minh_email_luc')->count(),
        ];

        return view('nguoi_dung.index', compact('nguoiDung', 'title', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $title = 'Thêm người dùng mới';
        
        return view('nguoi_dung.create', compact('title'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $rules = [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email',
            'mat_khau' => 'required|string|min:6|confirmed',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:500',
            'vai_tro' => 'required|in:admin,customer'
        ];

        $messages = [
            'ho_ten.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp',
            'vai_tro.required' => 'Vui lòng chọn vai trò'
        ];

        $request->validate($rules, $messages);

        $data = $request->only(['ho_ten', 'email', 'so_dien_thoai', 'dia_chi', 'vai_tro']);
        $data['mat_khau'] = $request->mat_khau; // Will be hashed by model mutator

        NguoiDung::create($data);

        return redirect()->route('admin.nguoidung.index')
            ->with('tb_success', 'Thêm người dùng thành công');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $nguoiDung = NguoiDung::with(['donHangs.chiTiet.sach', 'danhGias.sach'])
                              ->findOrFail($id);
        
        $title = 'Thông tin người dùng: ' . $nguoiDung->ho_ten;

        // Statistics for this user
        $stats = [
            'total_orders' => $nguoiDung->donHangs->count(),
            'completed_orders' => $nguoiDung->donHangs->where('trang_thai', 'da_giao')->count(),
            'total_spent' => $nguoiDung->donHangs->where('trang_thai', 'da_giao')->sum('tong_tien'),
            'total_reviews' => $nguoiDung->danhGias->count(),
        ];

        return view('nguoi_dung.show', compact('nguoiDung', 'title', 'stats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $title = 'Chỉnh sửa người dùng: ' . $nguoiDung->ho_ten;
        
        return view('nguoi_dung.edit', compact('nguoiDung', 'title'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        $rules = [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email,' . $id,
            'mat_khau' => 'nullable|string|min:6|confirmed',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:500',
            'vai_tro' => 'required|in:admin,customer'
        ];

        $messages = [
            'ho_ten.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp',
            'vai_tro.required' => 'Vui lòng chọn vai trò'
        ];

        $request->validate($rules, $messages);

        $data = $request->only(['ho_ten', 'email', 'so_dien_thoai', 'dia_chi', 'vai_tro']);
        
        // Only update password if provided
        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = $request->mat_khau;
        }

        $nguoiDung->update($data);

        return redirect()->route('admin.nguoidung.index')
            ->with('tb_success', 'Cập nhật người dùng thành công');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        // Check if user has orders
        if ($nguoiDung->donHangs()->count() > 0) {
            return redirect()->back()
                ->with('tb_danger', 'Không thể xóa người dùng đã có đơn hàng');
        }

        $nguoiDung->delete();

        return redirect()->route('admin.nguoidung.index')
            ->with('tb_success', 'Xóa người dùng thành công');
    }

    /**
     * Verify user email
     */
    public function verifyEmail($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        
        if ($nguoiDung->xac_minh_email_luc) {
            return redirect()->back()
                ->with('tb_info', 'Email đã được xác minh');
        }

        $nguoiDung->update(['xac_minh_email_luc' => now()]);

        return redirect()->back()
            ->with('tb_success', 'Đã xác minh email cho người dùng');
    }

    /**
     * Toggle user role
     */
    public function toggleRole($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        
        $newRole = $nguoiDung->vai_tro === 'admin' ? 'customer' : 'admin';
        $nguoiDung->update(['vai_tro' => $newRole]);

        $roleText = $newRole === 'admin' ? 'quản trị viên' : 'khách hàng';

        return redirect()->back()
            ->with('tb_success', "Đã chuyển vai trò thành {$roleText}");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()
                ->with('tb_danger', 'Vui lòng chọn ít nhất một người dùng');
        }

        switch ($action) {
            case 'verify':
                NguoiDung::whereIn('id', $ids)
                         ->whereNull('xac_minh_email_luc')
                         ->update(['xac_minh_email_luc' => now()]);
                return redirect()->back()
                    ->with('tb_success', 'Đã xác minh email cho các người dùng đã chọn');

            case 'make_admin':
                NguoiDung::whereIn('id', $ids)->update(['vai_tro' => 'admin']);
                return redirect()->back()
                    ->with('tb_success', 'Đã chuyển thành quản trị viên');

            case 'make_customer':
                NguoiDung::whereIn('id', $ids)->update(['vai_tro' => 'customer']);
                return redirect()->back()
                    ->with('tb_success', 'Đã chuyển thành khách hàng');

            case 'delete':
                $count = NguoiDung::whereIn('id', $ids)
                                 ->whereDoesntHave('donHangs')
                                 ->delete();
                return redirect()->back()
                    ->with('tb_success', "Đã xóa {$count} người dùng");

            default:
                return redirect()->back()
                    ->with('tb_danger', 'Hành động không hợp lệ');
        }
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = NguoiDung::query();

        // Apply same filters as index
        if ($request->has('vai_tro') && !empty($request->vai_tro)) {
            if ($request->vai_tro === 'admin') {
                $query->admin();
            } elseif ($request->vai_tro === 'customer') {
                $query->customer();
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        $filename = 'nguoi_dung_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['ID', 'Họ tên', 'Email', 'Số điện thoại', 'Vai trò', 'Ngày tạo']);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->ho_ten,
                    $user->email,
                    $user->so_dien_thoai,
                    $user->vai_tro === 'admin' ? 'Quản trị viên' : 'Khách hàng',
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
