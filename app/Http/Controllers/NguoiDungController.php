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
        // For now, redirect to home with message since auth is not implemented
        return redirect()->route('home')
            ->with('tb_info', 'Chức năng hồ sơ người dùng đang được phát triển');
    }

    /**
     * Display user orders (for frontend)
     */
    public function orders()
    {
        // For now, redirect to home with message since auth is not implemented
        return redirect()->route('home')
            ->with('tb_info', 'Chức năng đơn hàng đang được phát triển');
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $title = 'Quản lý người dùng';

        $query = NguoiDung::query();

        // Filter by role
        if ($request->has('vai_tro') && !empty($request->vai_tro)) {
            if ($request->vai_tro === 'admin') {
                $query->admin();
            } elseif ($request->vai_tro === 'customer') {
                $query->customer();
            }
        }

        // Filter by verification status
        if ($request->has('xac_minh') && !empty($request->xac_minh)) {
            if ($request->xac_minh === 'verified') {
                $query->verified();
            } elseif ($request->xac_minh === 'unverified') {
                $query->whereNull('xac_minh_email_luc');
            }
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        $nguoiDung = $query->withCount('donHangs')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        // Statistics
        $stats = [
            'total' => NguoiDung::count(),
            'admin' => NguoiDung::admin()->count(),
            'customer' => NguoiDung::customer()->count(),
            'verified' => NguoiDung::verified()->count(),
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
