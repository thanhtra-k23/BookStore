<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    /**
     * Display a listing of reviews (Admin)
     */
    public function index(Request $request)
    {
        $title = 'Quản lý đánh giá';
        
        $query = DanhGia::with(['sach', 'nguoiDung']);
        
        // Filter by status
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            switch ($request->trang_thai) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'rejected':
                    $query->rejected();
                    break;
            }
        }
        
        // Filter by rating
        if ($request->has('diem_so') && !empty($request->diem_so)) {
            $query->byRating($request->diem_so);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('noi_dung', 'like', "%{$search}%")
                  ->orWhereHas('sach', function ($book) use ($search) {
                      $book->where('ten_sach', 'like', "%{$search}%");
                  })
                  ->orWhereHas('nguoiDung', function ($user) use ($search) {
                      $user->where('ho_ten', 'like', "%{$search}%");
                  });
            });
        }
        
        $danhGia = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => DanhGia::count(),
            'pending' => DanhGia::pending()->count(),
            'approved' => DanhGia::approved()->count(),
            'rejected' => DanhGia::rejected()->count(),
        ];
        
        return view('danh_gia.index', compact('danhGia', 'title', 'stats'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đánh giá'
            ], 401);
        }

        $request->validate([
            'sach_id' => 'required|exists:sach,id',
            'diem_so' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string|min:10|max:1000'
        ], [
            'sach_id.required' => 'Sách không hợp lệ',
            'diem_so.required' => 'Vui lòng chọn điểm đánh giá',
            'diem_so.min' => 'Điểm đánh giá tối thiểu là 1',
            'diem_so.max' => 'Điểm đánh giá tối đa là 5',
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá',
            'noi_dung.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự',
            'noi_dung.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự'
        ]);

        // Check if user already reviewed this book
        $existingReview = DanhGia::where('sach_id', $request->sach_id)
                                ->where('nguoi_dung_id', Auth::id())
                                ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá sách này rồi'
            ], 400);
        }

        // Check if user has purchased this book
        $hasPurchased = \App\Models\ChiTietDonHang::whereHas('donHang', function ($query) {
            $query->where('nguoi_dung_id', Auth::id())
                  ->where('trang_thai', \App\Models\DonHang::TRANG_THAI_DA_GIAO);
        })->where('sach_id', $request->sach_id)->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ có thể đánh giá sách đã mua'
            ], 400);
        }

        $danhGia = DanhGia::create([
            'sach_id' => $request->sach_id,
            'nguoi_dung_id' => Auth::id(),
            'diem_so' => $request->diem_so,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => DanhGia::TRANG_THAI_CHO_DUYET
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá của bạn đã được gửi và đang chờ duyệt',
            'review' => $danhGia->load('nguoiDung')
        ]);
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $danhGia = DanhGia::with(['sach', 'nguoiDung'])->findOrFail($id);
        $title = 'Chi tiết đánh giá';
        
        return view('danh_gia.show', compact('danhGia', 'title'));
    }

    /**
     * Update the specified review (Admin only)
     */
    public function update(Request $request, $id)
    {
        $danhGia = DanhGia::findOrFail($id);
        
        $request->validate([
            'trang_thai' => 'required|in:cho_duyet,da_duyet,bi_tu_choi',
            'ghi_chu_admin' => 'nullable|string|max:500'
        ]);

        $oldStatus = $danhGia->trang_thai;
        $newStatus = $request->trang_thai;

        $danhGia->update([
            'trang_thai' => $newStatus,
            'ghi_chu_admin' => $request->ghi_chu_admin
        ]);

        // If approved, update book rating
        if ($newStatus === DanhGia::TRANG_THAI_DA_DUYET && $oldStatus !== DanhGia::TRANG_THAI_DA_DUYET) {
            $danhGia->sach->updateRating($danhGia->diem_so);
        }

        $statusText = [
            'cho_duyet' => 'chờ duyệt',
            'da_duyet' => 'đã duyệt',
            'bi_tu_choi' => 'bị từ chối'
        ];

        return redirect()->back()
            ->with('tb_success', "Đã cập nhật trạng thái đánh giá thành '{$statusText[$newStatus]}'");
    }

    /**
     * Remove the specified review
     */
    public function destroy($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        
        // Only allow deletion by admin or review owner
        if (!Auth::user()->isAdmin() && $danhGia->nguoi_dung_id !== Auth::id()) {
            return redirect()->back()
                ->with('tb_danger', 'Bạn không có quyền xóa đánh giá này');
        }

        $danhGia->delete();

        return redirect()->back()
            ->with('tb_success', 'Đã xóa đánh giá');
    }

    /**
     * Approve review
     */
    public function approve($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->approve();

        return redirect()->back()
            ->with('tb_success', 'Đã duyệt đánh giá');
    }

    /**
     * Reject review
     */
    public function reject($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->reject();

        return redirect()->back()
            ->with('tb_success', 'Đã từ chối đánh giá');
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
                ->with('tb_danger', 'Vui lòng chọn ít nhất một đánh giá');
        }

        switch ($action) {
            case 'approve':
                $reviews = DanhGia::whereIn('id', $ids)->get();
                foreach ($reviews as $review) {
                    $review->approve();
                }
                return redirect()->back()
                    ->with('tb_success', 'Đã duyệt các đánh giá đã chọn');

            case 'reject':
                DanhGia::whereIn('id', $ids)->update([
                    'trang_thai' => DanhGia::TRANG_THAI_BI_TU_CHOI
                ]);
                return redirect()->back()
                    ->with('tb_success', 'Đã từ chối các đánh giá đã chọn');

            case 'delete':
                DanhGia::whereIn('id', $ids)->delete();
                return redirect()->back()
                    ->with('tb_success', 'Đã xóa các đánh giá đã chọn');

            default:
                return redirect()->back()
                    ->with('tb_danger', 'Hành động không hợp lệ');
        }
    }

    /**
     * Get reviews for a book (AJAX)
     */
    public function getBookReviews($sachId, Request $request)
    {
        $query = DanhGia::where('sach_id', $sachId)
                        ->approved()
                        ->with('nguoiDung')
                        ->orderBy('created_at', 'desc');

        if ($request->has('rating') && $request->rating > 0) {
            $query->where('diem_so', $request->rating);
        }

        $reviews = $query->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }
}