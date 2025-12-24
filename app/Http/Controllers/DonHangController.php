<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\NguoiDung;
use App\Models\MaGiamGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonHangController extends Controller
{
    /**
     * Display user orders (for frontend)
     */
    public function userOrders()
    {
        // For now, redirect to home with message since auth is not implemented
        return redirect()->route('home')
            ->with('tb_info', 'Chức năng đơn hàng đang được phát triển');
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        $title = 'Thanh toán - BookStore';
        
        // For now, show checkout page without authentication requirement
        // In production, this would require authentication
        return view('checkout.index', compact('title'));
    }

    /**
     * Process checkout
     */
    public function processCheckout(Request $request)
    {
        $rules = [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'so_dien_thoai' => 'required|string|max:15',
            'dia_chi' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'ghi_chu' => 'nullable|string|max:1000'
        ];

        $messages = [
            'ho_ten.required' => 'Vui lòng nhập họ và tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'dia_chi.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán'
        ];

        $request->validate($rules, $messages);

        // For demo purposes, just return success message
        // In production, this would create actual order
        return redirect()->route('checkout.success')
            ->with('success', 'Đơn hàng đã được đặt thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    }

    /**
     * Display checkout success page
     */
    public function checkoutSuccess()
    {
        $title = 'Đặt hàng thành công - BookStore';
        
        return view('checkout.success', compact('title'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Quản lý đơn hàng';
        
        $query = DonHang::with(['nguoiDung', 'chiTiet.sach', 'maGiamGia']);
        
        // Filter by status
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            $query->byStatus($request->trang_thai);
        }
        
        // Filter by date range
        if ($request->has('tu_ngay') && $request->has('den_ngay')) {
            $query->inDateRange($request->tu_ngay, $request->den_ngay);
        }
        
        // Search by order code or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_don', 'like', "%{$search}%")
                  ->orWhereHas('nguoiDung', function ($user) use ($search) {
                      $user->where('ho_ten', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $donHang = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => DonHang::count(),
            'pending' => DonHang::byStatus(DonHang::TRANG_THAI_CHO_XAC_NHAN)->count(),
            'confirmed' => DonHang::byStatus(DonHang::TRANG_THAI_DA_XAC_NHAN)->count(),
            'shipping' => DonHang::byStatus(DonHang::TRANG_THAI_DANG_GIAO)->count(),
            'completed' => DonHang::byStatus(DonHang::TRANG_THAI_DA_GIAO)->count(),
            'cancelled' => DonHang::byStatus(DonHang::TRANG_THAI_DA_HUY)->count(),
        ];
        
        return view('don_hang.index', compact('donHang', 'title', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tạo đơn hàng mới';
        $nguoiDung = NguoiDung::customer()->orderBy('ho_ten', 'asc')->get();
        
        return view('don_hang.create', compact('title', 'nguoiDung'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
            'dia_chi_giao' => 'required|string|max:500',
            'so_dien_thoai_giao' => 'required|string|max:15',
            'phuong_thuc_thanh_toan' => 'required|in:cod,chuyen_khoan,the_tin_dung',
            'ghi_chu' => 'nullable|string|max:1000',
            'ma_giam_gia_code' => 'nullable|string|exists:ma_giam_gia,ma_code',
            'items' => 'required|array|min:1',
            'items.*.sach_id' => 'required|exists:sach,id',
            'items.*.so_luong' => 'required|integer|min:1'
        ];

        $messages = [
            'nguoi_dung_id.required' => 'Vui lòng chọn khách hàng',
            'dia_chi_giao.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'so_dien_thoai_giao.required' => 'Vui lòng nhập số điện thoại',
            'phuong_thuc_thanh_toan.required' => 'Vui lòng chọn phương thức thanh toán',
            'items.required' => 'Vui lòng thêm ít nhất một sản phẩm'
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Calculate total
            $tongTienGoc = 0;
            foreach ($request->items as $item) {
                $sach = \App\Models\Sach::find($item['sach_id']);
                if (!$sach->canOrder($item['so_luong'])) {
                    throw new \Exception("Sách '{$sach->ten_sach}' không đủ số lượng");
                }
                $tongTienGoc += $sach->gia_hien_tai * $item['so_luong'];
            }

            // Apply discount
            $soTienGiamGia = 0;
            $maGiamGiaId = null;
            if ($request->ma_giam_gia_code) {
                $maGiamGia = MaGiamGia::byCode($request->ma_giam_gia_code)->first();
                if ($maGiamGia && $maGiamGia->canApplyToOrder($tongTienGoc)) {
                    $soTienGiamGia = $maGiamGia->calculateDiscount($tongTienGoc);
                    $maGiamGiaId = $maGiamGia->id;
                }
            }

            $tongTien = $tongTienGoc - $soTienGiamGia;

            // Create order
            $donHang = DonHang::create([
                'nguoi_dung_id' => $request->nguoi_dung_id,
                'tong_tien' => $tongTien,
                'tong_tien_goc' => $tongTienGoc,
                'so_tien_giam_gia' => $soTienGiamGia,
                'ma_giam_gia_id' => $maGiamGiaId,
                'trang_thai' => DonHang::TRANG_THAI_CHO_XAC_NHAN,
                'dia_chi_giao' => $request->dia_chi_giao,
                'so_dien_thoai_giao' => $request->so_dien_thoai_giao,
                'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
                'ghi_chu' => $request->ghi_chu
            ]);

            // Create order details
            foreach ($request->items as $item) {
                $sach = \App\Models\Sach::find($item['sach_id']);
                ChiTietDonHang::create([
                    'don_hang_id' => $donHang->id,
                    'sach_id' => $item['sach_id'],
                    'so_luong' => $item['so_luong'],
                    'gia_ban' => $sach->gia_hien_tai
                ]);

                // Update stock
                $sach->decrement('so_luong_ton', $item['so_luong']);
            }

            // Use discount code
            if ($maGiamGiaId) {
                $maGiamGia->use();
            }

            DB::commit();

            return redirect()->route('donhang.show', $donHang->id)
                ->with('tb_success', 'Tạo đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('tb_danger', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $donHang = DonHang::with(['nguoiDung', 'chiTiet.sach', 'maGiamGia'])
                          ->findOrFail($id);
        
        $title = 'Chi tiết đơn hàng: ' . $donHang->ma_don;
        
        return view('don_hang.show', compact('donHang', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $donHang = DonHang::with(['chiTiet.sach'])->findOrFail($id);
        
        if (!$donHang->canCancel()) {
            return redirect()->route('donhang.show', $id)
                ->with('tb_danger', 'Không thể chỉnh sửa đơn hàng này');
        }
        
        $title = 'Chỉnh sửa đơn hàng: ' . $donHang->ma_don;
        
        return view('don_hang.edit', compact('donHang', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);
        
        if (!$donHang->canCancel()) {
            return redirect()->route('donhang.show', $id)
                ->with('tb_danger', 'Không thể cập nhật đơn hàng này');
        }

        $rules = [
            'dia_chi_giao' => 'required|string|max:500',
            'so_dien_thoai_giao' => 'required|string|max:15',
            'ghi_chu' => 'nullable|string|max:1000'
        ];

        $request->validate($rules);

        $donHang->update($request->only([
            'dia_chi_giao', 'so_dien_thoai_giao', 'ghi_chu'
        ]));

        return redirect()->route('donhang.show', $id)
            ->with('tb_success', 'Cập nhật đơn hàng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $donHang = DonHang::findOrFail($id);
        
        if (!$donHang->canCancel()) {
            return redirect()->route('admin.donhang.index')
                ->with('tb_danger', 'Không thể xóa đơn hàng này');
        }

        // Restore stock
        foreach ($donHang->chiTiet as $chiTiet) {
            $chiTiet->sach->increment('so_luong_ton', $chiTiet->so_luong);
        }

        $donHang->delete();
        
        return redirect()->route('admin.donhang.index')
            ->with('tb_success', 'Xóa đơn hàng thành công');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);
        
        $request->validate([
            'trang_thai' => 'required|in:cho_xac_nhan,da_xac_nhan,dang_giao,da_giao,da_huy'
        ]);

        $oldStatus = $donHang->trang_thai;
        $newStatus = $request->trang_thai;

        // Business logic for status transitions
        if ($oldStatus === DonHang::TRANG_THAI_DA_GIAO && $newStatus !== DonHang::TRANG_THAI_DA_GIAO) {
            return redirect()->back()
                ->with('tb_danger', 'Không thể thay đổi trạng thái đơn hàng đã giao');
        }

        if ($oldStatus === DonHang::TRANG_THAI_DA_HUY && $newStatus !== DonHang::TRANG_THAI_DA_HUY) {
            return redirect()->back()
                ->with('tb_danger', 'Không thể thay đổi trạng thái đơn hàng đã hủy');
        }

        // If cancelling order, restore stock
        if ($newStatus === DonHang::TRANG_THAI_DA_HUY && $oldStatus !== DonHang::TRANG_THAI_DA_HUY) {
            foreach ($donHang->chiTiet as $chiTiet) {
                $chiTiet->sach->increment('so_luong_ton', $chiTiet->so_luong);
            }
        }

        $donHang->update(['trang_thai' => $newStatus]);

        return redirect()->back()
            ->with('tb_success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    /**
     * Print order
     */
    public function print($id)
    {
        $donHang = DonHang::with(['nguoiDung', 'chiTiet.sach', 'maGiamGia'])
                          ->findOrFail($id);
        
        return view('don_hang.print', compact('donHang'));
    }
}
