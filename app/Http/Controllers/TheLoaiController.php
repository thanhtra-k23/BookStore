<?php

namespace App\Http\Controllers;

use App\Models\TheLoai;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TheLoaiController extends Controller
{
    /**
     * Display a listing of categories with advanced filtering and search
     */
    public function index(Request $request)
    {
        $query = TheLoai::with(['theLoaiCha', 'theLoaiCon', 'sach']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_the_loai', 'LIKE', "%{$search}%")
                  ->orWhere('mo_ta', 'LIKE', "%{$search}%")
                  ->orWhere('duong_dan', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by parent category
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'null') {
                $query->whereNull('ma_the_loai_cha');
            } else {
                $query->where('ma_the_loai_cha', $request->parent_id);
            }
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'ten_the_loai');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['ten_the_loai', 'created_at', 'updated_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $theLoai = $query->paginate(15)->withQueryString();
        
        // Get parent categories for filter dropdown
        $parentCategories = TheLoai::whereNull('ma_the_loai_cha')
            ->orderBy('ten_the_loai')
            ->get();
        
        // Statistics
        $stats = [
            'total' => TheLoai::count(),
            'active' => TheLoai::where('trang_thai', 1)->count(),
            'inactive' => TheLoai::where('trang_thai', 0)->count(),
            'parent_categories' => TheLoai::whereNull('ma_the_loai_cha')->count(),
            'child_categories' => TheLoai::whereNotNull('ma_the_loai_cha')->count(),
        ];
        
        return view('the_loai.index', compact('theLoai', 'parentCategories', 'stats'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $parentCategories = TheLoai::whereNull('ma_the_loai_cha')
            ->where('trang_thai', 1)
            ->orderBy('ten_the_loai')
            ->get();
            
        return view('the_loai.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $rules = [
            'ten_the_loai' => [
                'required',
                'string',
                'max:255',
                Rule::unique('the_loai', 'ten_the_loai')
            ],
            'mo_ta' => 'nullable|string|max:1000',
            'ma_the_loai_cha' => 'nullable|exists:the_loai,ma_the_loai',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean',
            'thu_tu_hien_thi' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ];

        $messages = [
            'ten_the_loai.required' => 'Vui lòng nhập tên thể loại',
            'ten_the_loai.unique' => 'Tên thể loại đã tồn tại',
            'ten_the_loai.max' => 'Tên thể loại không được vượt quá 255 ký tự',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'ma_the_loai_cha.exists' => 'Thể loại cha không tồn tại',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'thu_tu_hien_thi.integer' => 'Thứ tự hiển thị phải là số nguyên',
            'thu_tu_hien_thi.min' => 'Thứ tự hiển thị phải lớn hơn hoặc bằng 0'
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $theLoai = new TheLoai();
            $theLoai->ten_the_loai = $request->ten_the_loai;
            $theLoai->duong_dan = Str::slug($request->ten_the_loai);
            $theLoai->mo_ta = $request->mo_ta;
            $theLoai->ma_the_loai_cha = $request->ma_the_loai_cha;
            $theLoai->trang_thai = $request->has('trang_thai') ? 1 : 0;
            $theLoai->thu_tu_hien_thi = $request->thu_tu_hien_thi ?? 0;
            $theLoai->meta_title = $request->meta_title;
            $theLoai->meta_description = $request->meta_description;
            $theLoai->meta_keywords = $request->meta_keywords;

            // Handle image upload
            if ($request->hasFile('hinh_anh')) {
                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . Str::slug($request->ten_the_loai) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('categories', $imageName, 'public');
                $theLoai->hinh_anh = $imagePath;
            }

            $theLoai->save();

            DB::commit();

            return redirect()->route('admin.theloai.index')
                ->with('success', 'Thêm thể loại thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded image if exists
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified category
     */
    public function show(TheLoai $theLoai)
    {
        $theLoai->load(['theLoaiCha', 'theLoaiCon', 'sach' => function($query) {
            $query->where('trang_thai', 'active')->take(10);
        }]);
        
        // Get statistics
        $stats = [
            'total_books' => $theLoai->sach()->count(),
            'active_books' => $theLoai->sach()->where('trang_thai', 'active')->count(),
            'total_children' => $theLoai->theLoaiCon()->count(),
            'created_date' => $theLoai->created_at->format('d/m/Y H:i'),
            'updated_date' => $theLoai->updated_at->format('d/m/Y H:i')
        ];
        
        return view('the_loai.show', compact('theLoai', 'stats'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(TheLoai $theLoai)
    {
        $parentCategories = TheLoai::whereNull('ma_the_loai_cha')
            ->where('ma_the_loai', '!=', $theLoai->ma_the_loai)
            ->where('trang_thai', 1)
            ->orderBy('ten_the_loai')
            ->get();
            
        return view('the_loai.edit', compact('theLoai', 'parentCategories'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, TheLoai $theLoai)
    {
        $rules = [
            'ten_the_loai' => [
                'required',
                'string',
                'max:255',
                Rule::unique('the_loai', 'ten_the_loai')->ignore($theLoai->ma_the_loai, 'ma_the_loai')
            ],
            'mo_ta' => 'nullable|string|max:1000',
            'ma_the_loai_cha' => [
                'nullable',
                'exists:the_loai,ma_the_loai',
                function ($attribute, $value, $fail) use ($theLoai) {
                    if ($value == $theLoai->ma_the_loai) {
                        $fail('Thể loại không thể là cha của chính nó.');
                    }
                }
            ],
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean',
            'thu_tu_hien_thi' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ];

        $messages = [
            'ten_the_loai.required' => 'Vui lòng nhập tên thể loại',
            'ten_the_loai.unique' => 'Tên thể loại đã tồn tại',
            'ten_the_loai.max' => 'Tên thể loại không được vượt quá 255 ký tự',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'ma_the_loai_cha.exists' => 'Thể loại cha không tồn tại',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $oldImage = $theLoai->hinh_anh;

            $theLoai->ten_the_loai = $request->ten_the_loai;
            $theLoai->duong_dan = Str::slug($request->ten_the_loai);
            $theLoai->mo_ta = $request->mo_ta;
            $theLoai->ma_the_loai_cha = $request->ma_the_loai_cha;
            $theLoai->trang_thai = $request->has('trang_thai') ? 1 : 0;
            $theLoai->thu_tu_hien_thi = $request->thu_tu_hien_thi ?? 0;
            $theLoai->meta_title = $request->meta_title;
            $theLoai->meta_description = $request->meta_description;
            $theLoai->meta_keywords = $request->meta_keywords;

            // Handle image upload
            if ($request->hasFile('hinh_anh')) {
                // Delete old image
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . Str::slug($request->ten_the_loai) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('categories', $imageName, 'public');
                $theLoai->hinh_anh = $imagePath;
            }

            $theLoai->save();

            DB::commit();

            return redirect()->route('admin.theloai.index')
                ->with('success', 'Cập nhật thể loại thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(TheLoai $theLoai)
    {
        try {
            // Check if category has books
            if ($theLoai->sach()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa thể loại này vì đang có sách thuộc thể loại này!');
            }

            // Check if category has children
            if ($theLoai->theLoaiCon()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa thể loại này vì đang có thể loại con!');
            }

            DB::beginTransaction();

            // Delete image if exists
            if ($theLoai->hinh_anh && Storage::disk('public')->exists($theLoai->hinh_anh)) {
                Storage::disk('public')->delete($theLoai->hinh_anh);
            }

            $theLoai->delete();

            DB::commit();

            return redirect()->route('admin.theloai.index')
                ->with('success', 'Xóa thể loại thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'exists:the_loai,ma_the_loai'
        ]);

        try {
            DB::beginTransaction();

            $selectedIds = $request->selected_ids;
            $action = $request->action;
            $count = 0;

            switch ($action) {
                case 'delete':
                    // Check for categories with books or children
                    $categoriesWithBooks = TheLoai::whereIn('ma_the_loai', $selectedIds)
                        ->whereHas('sach')
                        ->count();
                    
                    $categoriesWithChildren = TheLoai::whereIn('ma_the_loai', $selectedIds)
                        ->whereHas('theLoaiCon')
                        ->count();

                    if ($categoriesWithBooks > 0 || $categoriesWithChildren > 0) {
                        return redirect()->back()
                            ->with('error', 'Không thể xóa một số thể loại vì đang có sách hoặc thể loại con!');
                    }

                    $categories = TheLoai::whereIn('ma_the_loai', $selectedIds)->get();
                    foreach ($categories as $category) {
                        if ($category->hinh_anh && Storage::disk('public')->exists($category->hinh_anh)) {
                            Storage::disk('public')->delete($category->hinh_anh);
                        }
                        $category->delete();
                        $count++;
                    }
                    break;

                case 'activate':
                    $count = TheLoai::whereIn('ma_the_loai', $selectedIds)
                        ->update(['trang_thai' => 1]);
                    break;

                case 'deactivate':
                    $count = TheLoai::whereIn('ma_the_loai', $selectedIds)
                        ->update(['trang_thai' => 0]);
                    break;
            }

            DB::commit();

            $messages = [
                'delete' => "Đã xóa {$count} thể loại thành công!",
                'activate' => "Đã kích hoạt {$count} thể loại thành công!",
                'deactivate' => "Đã vô hiệu hóa {$count} thể loại thành công!"
            ];

            return redirect()->back()
                ->with('success', $messages[$action]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(TheLoai $theLoai)
    {
        try {
            $theLoai->trang_thai = !$theLoai->trang_thai;
            $theLoai->save();

            $status = $theLoai->trang_thai ? 'kích hoạt' : 'vô hiệu hóa';
            
            return response()->json([
                'success' => true,
                'message' => "Đã {$status} thể loại thành công!",
                'status' => $theLoai->trang_thai
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export categories to CSV
     */
    public function export(Request $request)
    {
        $query = TheLoai::with(['theLoaiCha', 'sach']);
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_the_loai', 'LIKE', "%{$search}%")
                  ->orWhere('mo_ta', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'null') {
                $query->whereNull('ma_the_loai_cha');
            } else {
                $query->where('ma_the_loai_cha', $request->parent_id);
            }
        }
        
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }
        
        $categories = $query->orderBy('ten_the_loai')->get();
        
        $filename = 'the_loai_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Mã thể loại',
                'Tên thể loại',
                'Đường dẫn',
                'Mô tả',
                'Thể loại cha',
                'Trạng thái',
                'Thứ tự hiển thị',
                'Số lượng sách',
                'Ngày tạo',
                'Ngày cập nhật'
            ]);
            
            // Data
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->ma_the_loai,
                    $category->ten_the_loai,
                    $category->duong_dan,
                    $category->mo_ta,
                    $category->theLoaiCha ? $category->theLoaiCha->ten_the_loai : 'Không có',
                    $category->trang_thai ? 'Hoạt động' : 'Không hoạt động',
                    $category->thu_tu_hien_thi,
                    $category->sach->count(),
                    $category->created_at->format('d/m/Y H:i:s'),
                    $category->updated_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
