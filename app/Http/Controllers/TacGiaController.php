<?php

namespace App\Http\Controllers;

use App\Models\TacGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TacGiaController extends Controller
{
    /**
     * Display a listing of authors
     */
    public function index(Request $request)
    {
        $title = 'Quản lý tác giả';
        
        $query = TacGia::withCount('sach');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        // Filter by country
        if ($request->has('quoc_tich') && !empty($request->quoc_tich)) {
            $query->byCountry($request->quoc_tich);
        }
        
        // Filter by status (alive/deceased)
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            if ($request->trang_thai === 'alive') {
                $query->alive();
            } elseif ($request->trang_thai === 'deceased') {
                $query->deceased();
            }
        }
        
        $tacGia = $query->orderBy('ten_tac_gia', 'asc')->paginate(15);
        
        // Get unique countries for filter
        $countries = TacGia::whereNotNull('quoc_tich')
                          ->distinct()
                          ->pluck('quoc_tich')
                          ->sort();
        
        // Statistics
        $stats = [
            'total' => TacGia::count(),
            'active' => TacGia::where('trang_thai', 1)->count(),
            'inactive' => TacGia::where('trang_thai', 0)->count(),
            'with_books' => TacGia::whereHas('sach')->count(),
            'without_books' => TacGia::whereDoesntHave('sach')->count(),
        ];
        
        return view('tac_gia.index', compact('tacGia', 'title', 'countries', 'stats'));
    }

    /**
     * Show the form for creating a new author
     */
    public function create()
    {
        $title = 'Thêm tác giả mới';
        
        return view('tac_gia.create', compact('title'));
    }

    /**
     * Store a newly created author
     */
    public function store(Request $request)
    {
        $rules = [
            'ten_tac_gia' => 'required|string|max:255',
            'tieu_su' => 'required|string',
            'ngay_sinh' => 'nullable|date|before:today',
            'ngay_mat' => 'nullable|date|after:ngay_sinh',
            'quoc_tich' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $messages = [
            'ten_tac_gia.required' => 'Vui lòng nhập tên tác giả',
            'tieu_su.required' => 'Vui lòng nhập tiểu sử tác giả',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hiện tại',
            'ngay_mat.after' => 'Ngày mất phải sau ngày sinh',
            'website.url' => 'Website phải là URL hợp lệ',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.max' => 'Kích thước file không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        // Generate unique slug (including soft deleted records)
        $baseSlug = Str::slug($request->ten_tac_gia);
        $slug = $baseSlug;
        $counter = 1;
        
        while (TacGia::withTrashed()->where('duong_dan', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = $request->only([
            'ten_tac_gia', 'tieu_su', 'ngay_sinh', 'ngay_mat', 'quoc_tich', 'website'
        ]);
        
        // Add the unique slug
        $data['duong_dan'] = $slug;

        // Handle image upload
        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::slug($request->ten_tac_gia) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('authors', $imageName, 'public');
            $data['hinh_anh'] = $imagePath;
        }

        TacGia::create($data);

        return redirect()->route('admin.tacgia.index')
            ->with('tb_success', 'Thêm tác giả thành công.');
    }

    /**
     * Display the specified author
     */
    public function show($id, $slug = null)
    {
        $tacGia = TacGia::with(['sach.theLoai', 'sach.nhaXuatBan'])
                        ->withCount('sach')
                        ->findOrFail($id);

        if ($slug && $slug !== $tacGia->duong_dan) {
            return redirect()->route('tacgia.show', [$tacGia->id, $tacGia->duong_dan]);
        }

        $title = 'Tác giả: ' . $tacGia->ten_tac_gia;

        // Get author's books with pagination
        $books = $tacGia->sach()
                       ->active()
                       ->with(['theLoai', 'nhaXuatBan'])
                       ->orderBy('nam_xuat_ban', 'desc')
                       ->paginate(12);

        // Statistics
        $stats = [
            'total_books' => $tacGia->getBookCount(),
            'active_books' => $tacGia->getActiveBookCount(),
            'average_rating' => $tacGia->getAverageBookRating(),
            'total_views' => $tacGia->getTotalBookViews()
        ];

        return view('tac_gia.show', compact('tacGia', 'books', 'title', 'stats'));
    }

    /**
     * Show the form for editing the specified author
     */
    public function edit($id)
    {
        $tacGia = TacGia::findOrFail($id);
        $title = 'Chỉnh sửa tác giả: ' . $tacGia->ten_tac_gia;

        return view('tac_gia.edit', compact('tacGia', 'title'));
    }

    /**
     * Update the specified author
     */
    public function update(Request $request, $id)
    {
        $tacGia = TacGia::findOrFail($id);

        $rules = [
            'ten_tac_gia' => 'required|string|max:255',
            'tieu_su' => 'required|string',
            'ngay_sinh' => 'nullable|date|before:today',
            'ngay_mat' => 'nullable|date|after:ngay_sinh',
            'quoc_tich' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $messages = [
            'ten_tac_gia.required' => 'Vui lòng nhập tên tác giả',
            'tieu_su.required' => 'Vui lòng nhập tiểu sử tác giả',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hiện tại',
            'ngay_mat.after' => 'Ngày mất phải sau ngày sinh',
            'website.url' => 'Website phải là URL hợp lệ',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.max' => 'Kích thước file không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        // Generate unique slug if name changed (including soft deleted records)
        $baseSlug = Str::slug($request->ten_tac_gia);
        $slug = $baseSlug;
        $counter = 1;
        
        while (TacGia::withTrashed()->where('duong_dan', $slug)->where('ma_tac_gia', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = $request->only([
            'ten_tac_gia', 'tieu_su', 'ngay_sinh', 'ngay_mat', 'quoc_tich', 'website'
        ]);
        
        // Add the unique slug
        $data['duong_dan'] = $slug;

        // Handle image upload or removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Remove current image
            if ($tacGia->hinh_anh && Storage::disk('public')->exists($tacGia->hinh_anh)) {
                Storage::disk('public')->delete($tacGia->hinh_anh);
            }
            $data['hinh_anh'] = null;
        } elseif ($request->hasFile('hinh_anh')) {
            // Delete old image
            if ($tacGia->hinh_anh && Storage::disk('public')->exists($tacGia->hinh_anh)) {
                Storage::disk('public')->delete($tacGia->hinh_anh);
            }

            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::slug($request->ten_tac_gia) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('authors', $imageName, 'public');
            $data['hinh_anh'] = $imagePath;
        }

        $tacGia->update($data);

        return redirect()->route('admin.tacgia.index')
            ->with('tb_success', 'Cập nhật tác giả thành công.');
    }

    /**
     * Remove the specified author
     */
    public function destroy($id, $slug = null)
    {
        $tacGia = TacGia::findOrFail($id);
        
        if ($slug && $slug !== $tacGia->duong_dan) {
            return redirect()->route('admin.tacgia.index')
                ->with('tb_danger', 'Đường dẫn không hợp lệ');
        }

        // Check if author has books
        if ($tacGia->sach()->count() > 0) {
            return redirect()->route('admin.tacgia.index')
                ->with('tb_danger', 'Không thể xóa tác giả đã có sách');
        }

        // Delete image if exists
        if ($tacGia->hinh_anh && Storage::disk('public')->exists($tacGia->hinh_anh)) {
            Storage::disk('public')->delete($tacGia->hinh_anh);
        }

        $tacGia->delete();
        
        return redirect()->route('admin.tacgia.index')
            ->with('tb_success', 'Xóa tác giả thành công');
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
                ->with('tb_danger', 'Vui lòng chọn ít nhất một tác giả');
        }

        switch ($action) {
            case 'delete':
                $count = TacGia::whereIn('id', $ids)
                              ->whereDoesntHave('sach')
                              ->delete();
                return redirect()->back()
                    ->with('tb_success', "Đã xóa {$count} tác giả");

            default:
                return redirect()->back()
                    ->with('tb_danger', 'Hành động không hợp lệ');
        }
    }

    /**
     * Export authors to CSV
     */
    public function export(Request $request)
    {
        $query = TacGia::withCount('sach');
        
        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        if ($request->has('quoc_tich') && !empty($request->quoc_tich)) {
            $query->byCountry($request->quoc_tich);
        }
        
        $authors = $query->get();
        
        $filename = 'tac_gia_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($authors) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'ID', 'Tên tác giả', 'Quốc tịch', 'Ngày sinh', 'Ngày mất', 
                'Số lượng sách', 'Website', 'Ngày tạo'
            ]);
            
            // Data
            foreach ($authors as $author) {
                fputcsv($file, [
                    $author->id,
                    $author->ten_tac_gia,
                    $author->quoc_tich,
                    $author->ngay_sinh ? $author->ngay_sinh->format('d/m/Y') : '',
                    $author->ngay_mat ? $author->ngay_mat->format('d/m/Y') : '',
                    $author->sach_count,
                    $author->website,
                    $author->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
