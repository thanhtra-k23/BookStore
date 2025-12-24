<?php

namespace App\Http\Controllers;

use App\Models\MaGiamGia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaGiamGiaController extends Controller
{
    /**
     * Display a listing of discount codes
     */
    public function index(Request $request)
    {
        $title = 'Quản lý mã giảm giá';
        
        $query = MaGiamGia::query();
        
        // Filter by status
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        // Filter by type
        if ($request->has('loai_giam_gia') && !empty($request->loai_giam_gia)) {
            $query->where('loai_giam_gia', $request->loai_giam_gia);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_code', 'like', "%{$search}%")
                  ->orWhere('ten_ma_giam_gia', 'like', "%{$search}%");
            });
        }
        
        $maGiamGia = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => MaGiamGia::count(),
            'active' => MaGiamGia::active()->count(),
            'available' => MaGiamGia::available()->count(),
            'expired' => MaGiamGia::where('ngay_ket_thuc', '<', now())->count(),
        ];
        
        return view('ma_giam_gia.index', compact('maGiamGia', 'title', 'stats'));
    }

    /**
     * Show the form for creating a new discount code
     */
    public function create()
    {
        $title = 'Tạo mã giảm giá mới';
        
        return view('ma_giam_gia.create', compact('title'));
    }

    /**
     * Store a newly created discount code
     */
    public function store(Request $request)
    {
        $rules = [
            'ten_ma_giam_gia' => 'required|string|max:255',
            'ma_code' => 'required|string|max:50|unique:ma_giam_gia,ma_code',
            'mo_ta' => 'nullable|string|max:1000',
            'loai_giam_gia' => 'required|in:phan_tram,so_tien',
            'gia_tri_giam' => 'required|numeric|min:0',
            'gia_tri_don_hang_toi_thieu' => 'required|numeric|min:0',
            'gia_tri_giam_toi_da' => 'nullable|numeric|min:0',
            'so_luong' => 'nullable|integer|min:1',
            'ngay_bat_dau' => 'required|date|after_or_equal:today',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'nullable|boolean'
        ];

        // Additional validation for percentage discount
        if ($request->loai_giam_gia === MaGiamGia::LOAI_PHAN_TRAM) {
            $rules['gia_tri_giam'] .= '|max:100';
            $rules['gia_tri_giam_toi_da'] = 'required|numeric|min:0';
        }

        $messages = [
            'ten_ma_giam_gia.required' => 'Vui lòng nhập tên mã giảm giá',
            'ma_code.required' => 'Vui lòng nhập mã code',
            'ma_code.unique' => 'Mã code đã tồn tại',
            'loai_giam_gia.required' => 'Vui lòng chọn loại giảm giá',
            'gia_tri_giam.required' => 'Vui lòng nhập giá trị giảm',
            'gia_tri_giam.max' => 'Giá trị giảm theo phần trăm không được vượt quá 100%',
            'gia_tri_don_hang_toi_thieu.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu',
            'ngay_bat_dau.after_or_equal' => 'Ngày bắt đầu không được nhỏ hơn hôm nay',
            'ngay_ket_thuc.required' => 'Vui lòng chọn ngày kết thúc',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu'
        ];

        $request->validate($rules, $messages);

        MaGiamGia::create($request->all());

        return redirect()->route('admin.magiamgia.index')
            ->with('tb_success', 'Tạo mã giảm giá thành công');
    }

    /**
     * Display the specified discount code
     */
    public function show($id)
    {
        $maGiamGia = MaGiamGia::with('donHangs')->findOrFail($id);
        $title = 'Chi tiết mã giảm giá: ' . $maGiamGia->ten_ma_giam_gia;
        
        return view('ma_giam_gia.show', compact('maGiamGia', 'title'));
    }

    /**
     * Show the form for editing the specified discount code
     */
    public function edit($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        $title = 'Chỉnh sửa mã giảm giá: ' . $maGiamGia->ten_ma_giam_gia;
        
        return view('ma_giam_gia.edit', compact('maGiamGia', 'title'));
    }

    /**
     * Update the specified discount code
     */
    public function update(Request $request, $id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        
        $rules = [
            'ten_ma_giam_gia' => 'required|string|max:255',
            'ma_code' => 'required|string|max:50|unique:ma_giam_gia,ma_code,' . $id,
            'mo_ta' => 'nullable|string|max:1000',
            'loai_giam_gia' => 'required|in:phan_tram,so_tien',
            'gia_tri_giam' => 'required|numeric|min:0',
            'gia_tri_don_hang_toi_thieu' => 'required|numeric|min:0',
            'gia_tri_giam_toi_da' => 'nullable|numeric|min:0',
            'so_luong' => 'nullable|integer|min:1',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'nullable|boolean'
        ];

        // Additional validation for percentage discount
        if ($request->loai_giam_gia === MaGiamGia::LOAI_PHAN_TRAM) {
            $rules['gia_tri_giam'] .= '|max:100';
            $rules['gia_tri_giam_toi_da'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $maGiamGia->update($request->all());

        return redirect()->route('admin.magiamgia.index')
            ->with('tb_success', 'Cập nhật mã giảm giá thành công');
    }

    /**
     * Remove the specified discount code
     */
    public function destroy($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        
        // Check if discount code has been used
        if ($maGiamGia->donHangs()->count() > 0) {
            return redirect()->back()
                ->with('tb_danger', 'Không thể xóa mã giảm giá đã được sử dụng');
        }

        $maGiamGia->delete();

        return redirect()->route('admin.magiamgia.index')
            ->with('tb_success', 'Xóa mã giảm giá thành công');
    }

    /**
     * Toggle discount code status
     */
    public function toggleStatus($id)
    {
        $maGiamGia = MaGiamGia::findOrFail($id);
        $maGiamGia->trang_thai = !$maGiamGia->trang_thai;
        $maGiamGia->save();

        $status = $maGiamGia->trang_thai ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()->back()
            ->with('tb_success', "Đã {$status} mã giảm giá");
    }

    /**
     * Generate random discount code
     */
    public function generateCode()
    {
        do {
            $code = 'SALE' . strtoupper(Str::random(6));
        } while (MaGiamGia::where('ma_code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    /**
     * Validate discount code (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'ma_code' => 'required|string',
            'tong_tien' => 'required|numeric|min:0'
        ]);

        $maGiamGia = MaGiamGia::byCode($request->ma_code)->first();

        if (!$maGiamGia) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại'
            ]);
        }

        if (!$maGiamGia->canApplyToOrder($request->tong_tien)) {
            $reasons = [];
            
            if (!$maGiamGia->isActive()) {
                if ($maGiamGia->isNotStarted()) {
                    $reasons[] = 'Mã giảm giá chưa có hiệu lực';
                } elseif ($maGiamGia->isExpired()) {
                    $reasons[] = 'Mã giảm giá đã hết hạn';
                } else {
                    $reasons[] = 'Mã giảm giá không hoạt động';
                }
            }
            
            if (!$maGiamGia->isAvailable()) {
                $reasons[] = 'Mã giảm giá đã hết lượt sử dụng';
            }
            
            if ($request->tong_tien < $maGiamGia->gia_tri_don_hang_toi_thieu) {
                $reasons[] = 'Đơn hàng chưa đạt giá trị tối thiểu ' . 
                           number_format($maGiamGia->gia_tri_don_hang_toi_thieu, 0, ',', '.') . 'đ';
            }

            return response()->json([
                'success' => false,
                'message' => implode('. ', $reasons)
            ]);
        }

        $discount = $maGiamGia->calculateDiscount($request->tong_tien);
        $finalTotal = $request->tong_tien - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'discount' => $discount,
            'final_total' => $finalTotal,
            'discount_info' => [
                'ten_ma_giam_gia' => $maGiamGia->ten_ma_giam_gia,
                'gia_tri_giam_text' => $maGiamGia->gia_tri_giam_text,
                'so_luong_con_lai' => $maGiamGia->so_luong_con_lai
            ]
        ]);
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
                ->with('tb_danger', 'Vui lòng chọn ít nhất một mã giảm giá');
        }

        switch ($action) {
            case 'activate':
                MaGiamGia::whereIn('ma_giam_gia', $ids)->update(['trang_thai' => true]);
                return redirect()->back()
                    ->with('tb_success', 'Đã kích hoạt các mã giảm giá đã chọn');

            case 'deactivate':
                MaGiamGia::whereIn('ma_giam_gia', $ids)->update(['trang_thai' => false]);
                return redirect()->back()
                    ->with('tb_success', 'Đã vô hiệu hóa các mã giảm giá đã chọn');

            case 'delete':
                $count = MaGiamGia::whereIn('ma_giam_gia', $ids)
                                 ->whereDoesntHave('donHangs')
                                 ->delete();
                return redirect()->back()
                    ->with('tb_success', "Đã xóa {$count} mã giảm giá");

            default:
                return redirect()->back()
                    ->with('tb_danger', 'Hành động không hợp lệ');
        }
    }
}