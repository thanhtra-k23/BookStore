<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChiTietDonHangController extends Controller
{
    /**
     * Display a listing of order details
     */
    public function index(Request $request)
    {
        $title = 'Quản lý chi tiết đơn hàng';
        
        $query = ChiTietDonHang::with(['donHang.nguoiDung', 'sach.tacGia', 'sach.theLoai']);
        
        // Filter by order
        if ($request->has('don_hang_id') && !empty($request->don_hang_id)) {
            $query->where('don_hang_id', $request->don_hang_id);
        }
        
        // Filter by book
        if ($request->has('sach_id') && !empty($request->sach_id)) {
            $query->where('sach_id', $request->sach_id);
        }
        
        // Search by order code or book name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('donHang', function ($order) use ($search) {
                    $order->where('ma_don', 'like', "%{$search}%");
                })->orWhereHas('sach', function ($book) use ($search) {
                    $book->where('ten_sach', 'like', "%{$search}%");
                });
            });
        }
        
        // Date range filter
        if ($request->has('tu_ngay') && $request->has('den_ngay')) {
            $query->whereHas('donHang', function ($order) use ($request) {
                $order->whereBetween('created_at', [$request->tu_ngay, $request->den_ngay]);
            });
        }
        
        $chiTietDonHang = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get data for filters
        $donHangs = DonHang::with('nguoiDung')->orderBy('created_at', 'desc')->limit(50)->get();
        $sachs = Sach::orderBy('ten_sach', 'asc')->limit(100)->get();
        
        // Statistics
        $stats = [
            'total_items' => ChiTietDonHang::count(),
            'total_quantity' => ChiTietDonHang::sum('so_luong'),
            'total_revenue' => ChiTietDonHang::selectRaw('SUM(so_luong * gia_ban) as total')->value('total'),
            'unique_books' => ChiTietDonHang::distinct('sach_id')->count(),
        ];
        
        return view('chi_tiet_don_hang.index', compact(
            'chiTietDonHang', 'title', 'donHangs', 'sachs', 'stats'
        ));
    }

    /**
     * Show the form for creating a new order detail
     */
    public function create($donHangId = null)
    {
        $title = 'Thêm sản phẩm vào đơn hàng';
        
        $donHangs = DonHang::with('nguoiDung')
                          ->whereIn('trang_thai', [DonHang::TRANG_THAI_CHO_XAC_NHAN, DonHang::TRANG_THAI_DA_XAC_NHAN])
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        $sachs = Sach::active()->inStock()->with(['tacGia', 'theLoai'])->orderBy('ten_sach', 'asc')->get();
        
        $selectedOrder = null;
        if ($donHangId) {
            $selectedOrder = DonHang::findOrFail($donHangId);
        }
        
        return view('chi_tiet_don_hang.create', compact('title', 'donHangs', 'sachs', 'selectedOrder'));
    }

    /**
     * Store a newly created order detail
     */
    public function store(Request $request)
    {
        $rules = [
            'don_hang_id' => 'required|exists:don_hang,id',
            'sach_id' => 'required|exists:sach,id',
            'so_luong' => 'required|integer|min:1',
            'gia_ban' => 'nullable|numeric|min:0'
        ];

        $messages = [
            'don_hang_id.required' => 'Vui lòng chọn đơn hàng',
            'sach_id.required' => 'Vui lòng chọn sách',
            'so_luong.required' => 'Vui lòng nhập số lượng',
            'so_luong.min' => 'Số lượng phải lớn hơn 0'
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $donHang = DonHang::findOrFail($request->don_hang_id);
            $sach = Sach::findOrFail($request->sach_id);

            // Check if order can be modified
            if (!$donHang->canCancel()) {
                return redirect()->back()
                    ->withInput()
                    ->with('tb_danger', 'Không thể thêm sản phẩm vào đơn hàng này');
            }

            // Check stock availability
            if (!$sach->canOrder($request->so_luong)) {
                return redirect()->back()
                    ->withInput()
                    ->with('tb_danger', 'Sách không đủ số lượng hoặc không còn bán');
            }

            // Check if item already exists in order
            $existingItem = ChiTietDonHang::where('don_hang_id', $request->don_hang_id)
                                        ->where('sach_id', $request->sach_id)
                                        ->first();

            if ($existingItem) {
                // Update existing item
                $newQuantity = $existingItem->so_luong + $request->so_luong;
                if (!$sach->canOrder($newQuantity)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('tb_danger', 'Tổng số lượng vượt quá số lượng tồn kho');
                }
                
                $existingItem->update(['so_luong' => $newQuantity]);
                $message = 'Đã cập nhật số lượng sản phẩm trong đơn hàng';
            } else {
                // Create new item
                $data = $request->only(['don_hang_id', 'sach_id', 'so_luong']);
                $data['gia_ban'] = $request->gia_ban ?: $sach->gia_hien_tai;
                
                ChiTietDonHang::create($data);
                $message = 'Đã thêm sản phẩm vào đơn hàng thành công';
            }

            // Update order total
            $this->updateOrderTotal($donHang);

            // Update stock
            $sach->decrement('so_luong_ton', $request->so_luong);

            DB::commit();

            return redirect()->route('chitietdonhang.index')
                ->with('tb_success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('tb_danger', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order detail
     */
    public function show($id)
    {
        $chiTiet = ChiTietDonHang::with(['donHang.nguoiDung', 'sach.tacGia', 'sach.theLoai', 'sach.nhaXuatBan'])
                                ->findOrFail($id);
        
        $title = 'Chi tiết sản phẩm trong đơn hàng';
        
        return view('chi_tiet_don_hang.show', compact('chiTiet', 'title'));
    }

    /**
     * Show the form for editing the specified order detail
     */
    public function edit($id)
    {
        $chiTiet = ChiTietDonHang::with(['donHang', 'sach'])->findOrFail($id);
        
        if (!$chiTiet->donHang->canCancel()) {
            return redirect()->route('chitietdonhang.index')
                ->with('tb_danger', 'Không thể chỉnh sửa chi tiết đơn hàng này');
        }
        
        $title = 'Chỉnh sửa chi tiết đơn hàng';
        
        return view('chi_tiet_don_hang.edit', compact('chiTiet', 'title'));
    }

    /**
     * Update the specified order detail
     */
    public function update(Request $request, $id)
    {
        $chiTiet = ChiTietDonHang::with(['donHang', 'sach'])->findOrFail($id);
        
        if (!$chiTiet->donHang->canCancel()) {
            return redirect()->route('chitietdonhang.index')
                ->with('tb_danger', 'Không thể cập nhật chi tiết đơn hàng này');
        }

        $rules = [
            'so_luong' => 'required|integer|min:1',
            'gia_ban' => 'required|numeric|min:0'
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $oldQuantity = $chiTiet->so_luong;
            $newQuantity = $request->so_luong;
            $quantityDiff = $newQuantity - $oldQuantity;

            // Check stock if increasing quantity
            if ($quantityDiff > 0 && !$chiTiet->sach->canOrder($quantityDiff)) {
                return redirect()->back()
                    ->withInput()
                    ->with('tb_danger', 'Không đủ số lượng tồn kho');
            }

            // Update order detail
            $chiTiet->update([
                'so_luong' => $newQuantity,
                'gia_ban' => $request->gia_ban
            ]);

            // Update stock
            if ($quantityDiff != 0) {
                if ($quantityDiff > 0) {
                    $chiTiet->sach->decrement('so_luong_ton', $quantityDiff);
                } else {
                    $chiTiet->sach->increment('so_luong_ton', abs($quantityDiff));
                }
            }

            // Update order total
            $this->updateOrderTotal($chiTiet->donHang);

            DB::commit();

            return redirect()->route('chitietdonhang.index')
                ->with('tb_success', 'Cập nhật chi tiết đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('tb_danger', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order detail
     */
    public function destroy($id)
    {
        $chiTiet = ChiTietDonHang::with(['donHang', 'sach'])->findOrFail($id);
        
        if (!$chiTiet->donHang->canCancel()) {
            return redirect()->route('chitietdonhang.index')
                ->with('tb_danger', 'Không thể xóa chi tiết đơn hàng này');
        }

        DB::beginTransaction();
        try {
            // Restore stock
            $chiTiet->sach->increment('so_luong_ton', $chiTiet->so_luong);
            
            // Delete order detail
            $chiTiet->delete();
            
            // Update order total
            $this->updateOrderTotal($chiTiet->donHang);

            DB::commit();

            return redirect()->route('chitietdonhang.index')
                ->with('tb_success', 'Xóa chi tiết đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('tb_danger', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Get order details by order ID (AJAX)
     */
    public function getByOrder($donHangId)
    {
        $chiTietDonHang = ChiTietDonHang::with(['sach.tacGia', 'sach.theLoai'])
                                      ->where('don_hang_id', $donHangId)
                                      ->get();

        return response()->json([
            'success' => true,
            'data' => $chiTietDonHang
        ]);
    }

    /**
     * Bulk delete order details
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->back()
                ->with('tb_danger', 'Vui lòng chọn ít nhất một chi tiết đơn hàng');
        }

        DB::beginTransaction();
        try {
            $chiTietDonHangs = ChiTietDonHang::with(['donHang', 'sach'])
                                           ->whereIn('id', $ids)
                                           ->get();

            $affectedOrders = [];
            
            foreach ($chiTietDonHangs as $chiTiet) {
                if ($chiTiet->donHang->canCancel()) {
                    // Restore stock
                    $chiTiet->sach->increment('so_luong_ton', $chiTiet->so_luong);
                    
                    // Track affected orders
                    $affectedOrders[$chiTiet->don_hang_id] = $chiTiet->donHang;
                    
                    // Delete
                    $chiTiet->delete();
                }
            }

            // Update totals for affected orders
            foreach ($affectedOrders as $order) {
                $this->updateOrderTotal($order);
            }

            DB::commit();

            return redirect()->back()
                ->with('tb_success', 'Đã xóa các chi tiết đơn hàng đã chọn');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('tb_danger', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Update order total amount
     */
    private function updateOrderTotal(DonHang $donHang)
    {
        $tongTienGoc = $donHang->chiTiet->sum('thanh_tien');
        $soTienGiamGia = $donHang->so_tien_giam_gia ?: 0;
        
        // Recalculate discount if there's a discount code
        if ($donHang->maGiamGia && $donHang->maGiamGia->canApplyToOrder($tongTienGoc)) {
            $soTienGiamGia = $donHang->maGiamGia->calculateDiscount($tongTienGoc);
        } else {
            $soTienGiamGia = 0;
        }
        
        $tongTien = $tongTienGoc - $soTienGiamGia;
        
        $donHang->update([
            'tong_tien_goc' => $tongTienGoc,
            'so_tien_giam_gia' => $soTienGiamGia,
            'tong_tien' => $tongTien
        ]);
    }

    /**
     * Export order details to CSV
     */
    public function export(Request $request)
    {
        $query = ChiTietDonHang::with(['donHang.nguoiDung', 'sach.tacGia']);
        
        // Apply same filters as index
        if ($request->has('don_hang_id') && !empty($request->don_hang_id)) {
            $query->where('don_hang_id', $request->don_hang_id);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('donHang', function ($order) use ($search) {
                    $order->where('ma_don', 'like', "%{$search}%");
                })->orWhereHas('sach', function ($book) use ($search) {
                    $book->where('ten_sach', 'like', "%{$search}%");
                });
            });
        }
        
        $chiTietDonHangs = $query->get();
        
        $filename = 'chi_tiet_don_hang_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($chiTietDonHangs) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'ID', 'Mã đơn hàng', 'Khách hàng', 'Tên sách', 'Tác giả', 
                'Số lượng', 'Giá bán', 'Thành tiền', 'Ngày tạo'
            ]);
            
            // Data
            foreach ($chiTietDonHangs as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->donHang->ma_don,
                    $item->donHang->nguoiDung->ho_ten,
                    $item->sach->ten_sach,
                    $item->sach->tacGia->ten_tac_gia,
                    $item->so_luong,
                    number_format($item->gia_ban, 0, ',', '.'),
                    number_format($item->thanh_tien, 0, ',', '.'),
                    $item->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
