<?php

namespace App\Http\Controllers;

use App\Models\NhaXuatBan;
use App\Models\Sach;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NhaXuatBanController extends Controller
{
    /**
     * Display a listing of publishers with advanced filtering and search
     */
    public function index(Request $request)
    {
        $query = NhaXuatBan::with(['sach']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_nxb', 'LIKE', "%{$search}%")
                  ->orWhere('dia_chi', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%")
                  ->orWhere('duong_dan', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }
        
        // Filter by country
        if ($request->filled('quoc_gia')) {
            $query->where('quoc_gia', 'LIKE', "%{$request->quoc_gia}%");
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'ten_nxb');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['ten_nxb', 'created_at', 'updated_at', 'nam_thanh_lap'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $nhaXuatBan = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => NhaXuatBan::count(),
            'active' => NhaXuatBan::where('trang_thai', 1)->count(),
            'inactive' => NhaXuatBan::where('trang_thai', 0)->count(),
            'with_books' => NhaXuatBan::whereHas('sach')->count(),
            'without_books' => NhaXuatBan::whereDoesntHave('sach')->count(),
        ];
        
        return view('nha_xuat_ban.index', compact('nhaXuatBan', 'stats'));
    }

    /**
     * Show the form for creating a new publisher
     */
    public function create()
    {
        return view('nha_xuat_ban.create');
    }

    /**
     * Store a newly created publisher
     */
    public function store(Request $request)
    {
        $rules = [
            'ten_nxb' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nha_xuat_ban', 'ten_nxb')
            ],
            'dia_chi' => 'required|string|max:500',
            'so_dien_thoai' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'nam_thanh_lap' => 'nullable|integer|min:1800|max:' . date('Y'),
            'quoc_gia' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ];

        $messages = [
            'ten_nxb.required' => 'Vui lòng nhập tên nhà xuất bản',
            'ten_nxb.unique' => 'Tên nhà xuất bản đã tồn tại',
            'ten_nxb.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự',
            'dia_chi.required' => 'Vui lòng nhập địa chỉ',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'website.url' => 'Website không đúng định dạng',
            'website.max' => 'Website không được vượt quá 255 ký tự',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'nam_thanh_lap.integer' => 'Năm thành lập phải là số nguyên',
            'nam_thanh_lap.min' => 'Năm thành lập phải từ 1800 trở lên',
            'nam_thanh_lap.max' => 'Năm thành lập không được vượt quá năm hiện tại',
            'quoc_gia.max' => 'Quốc gia không được vượt quá 100 ký tự',
            'logo.image' => 'File phải là hình ảnh',
            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif',
            'logo.max' => 'Kích thước logo không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $nhaXuatBan = new NhaXuatBan();
            $nhaXuatBan->ten_nxb = $request->ten_nxb;
            $nhaXuatBan->duong_dan = Str::slug($request->ten_nxb);
            $nhaXuatBan->dia_chi = $request->dia_chi;
            $nhaXuatBan->so_dien_thoai = $request->so_dien_thoai;
            $nhaXuatBan->email = $request->email;
            $nhaXuatBan->website = $request->website;
            $nhaXuatBan->mo_ta = $request->mo_ta;
            $nhaXuatBan->nam_thanh_lap = $request->nam_thanh_lap;
            $nhaXuatBan->quoc_gia = $request->quoc_gia;
            $nhaXuatBan->trang_thai = $request->has('trang_thai') ? 1 : 0;
            $nhaXuatBan->meta_title = $request->meta_title;
            $nhaXuatBan->meta_description = $request->meta_description;
            $nhaXuatBan->meta_keywords = $request->meta_keywords;

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::slug($request->ten_nxb) . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('publishers', $logoName, 'public');
                $nhaXuatBan->logo = $logoPath;
            }

            $nhaXuatBan->save();

            DB::commit();

            return redirect()->route('admin.nhaxuatban.index')
                ->with('success', 'Thêm nhà xuất bản thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded logo if exists
            if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified publisher
     */
    public function show($nhaxuatban)
    {
        $nhaXuatBan = NhaXuatBan::findOrFail($nhaxuatban);
        $nhaXuatBan->load(['sach' => function($query) {
            $query->where('trang_thai', 1)->take(10);
        }]);
        
        // Get statistics
        $stats = [
            'total_books' => $nhaXuatBan->sach()->count(),
            'active_books' => $nhaXuatBan->sach()->where('trang_thai', 1)->count(),
            'total_revenue' => $nhaXuatBan->sach()->sum('gia_ban') ?: 0,
            'avg_price' => $nhaXuatBan->sach()->avg('gia_ban') ?: 0,
            'created_date' => $nhaXuatBan->created_at ? $nhaXuatBan->created_at->format('d/m/Y H:i') : 'Không có',
            'updated_date' => $nhaXuatBan->updated_at ? $nhaXuatBan->updated_at->format('d/m/Y H:i') : 'Không có'
        ];
        
        return view('nha_xuat_ban.show', compact('nhaXuatBan', 'stats'));
    }

    /**
     * Show the form for editing the specified publisher
     */
    public function edit($nhaxuatban)
    {
        $nhaXuatBan = NhaXuatBan::findOrFail($nhaxuatban);
        return view('nha_xuat_ban.edit', compact('nhaXuatBan'));
    }

    /**
     * Update the specified publisher
     */
    public function update(Request $request, $nhaxuatban)
    {
        $nhaXuatBan = NhaXuatBan::findOrFail($nhaxuatban);
        $rules = [
            'ten_nxb' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nha_xuat_ban', 'ten_nxb')->ignore($nhaXuatBan->ma_nxb, 'ma_nxb')
            ],
            'dia_chi' => 'required|string|max:500',
            'so_dien_thoai' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'nam_thanh_lap' => 'nullable|integer|min:1800|max:' . date('Y'),
            'quoc_gia' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ];

        $messages = [
            'ten_nxb.required' => 'Vui lòng nhập tên nhà xuất bản',
            'ten_nxb.unique' => 'Tên nhà xuất bản đã tồn tại',
            'ten_nxb.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự',
            'dia_chi.required' => 'Vui lòng nhập địa chỉ',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng',
            'email.email' => 'Email không đúng định dạng',
            'website.url' => 'Website không đúng định dạng',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'nam_thanh_lap.integer' => 'Năm thành lập phải là số nguyên',
            'nam_thanh_lap.min' => 'Năm thành lập phải từ 1800 trở lên',
            'nam_thanh_lap.max' => 'Năm thành lập không được vượt quá năm hiện tại',
            'logo.image' => 'File phải là hình ảnh',
            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif',
            'logo.max' => 'Kích thước logo không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $oldLogo = $nhaXuatBan->logo;

            $nhaXuatBan->ten_nxb = $request->ten_nxb;
            $nhaXuatBan->duong_dan = Str::slug($request->ten_nxb);
            $nhaXuatBan->dia_chi = $request->dia_chi;
            $nhaXuatBan->so_dien_thoai = $request->so_dien_thoai;
            $nhaXuatBan->email = $request->email;
            $nhaXuatBan->website = $request->website;
            $nhaXuatBan->mo_ta = $request->mo_ta;
            $nhaXuatBan->nam_thanh_lap = $request->nam_thanh_lap;
            $nhaXuatBan->quoc_gia = $request->quoc_gia;
            $nhaXuatBan->trang_thai = $request->has('trang_thai') ? 1 : 0;
            $nhaXuatBan->meta_title = $request->meta_title;
            $nhaXuatBan->meta_description = $request->meta_description;
            $nhaXuatBan->meta_keywords = $request->meta_keywords;

            // Handle logo upload and removal
            if ($request->has('remove_logo') && $request->remove_logo) {
                // Remove current logo
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $nhaXuatBan->logo = null;
            } elseif ($request->hasFile('logo')) {
                // Delete old logo
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::slug($request->ten_nxb) . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('publishers', $logoName, 'public');
                $nhaXuatBan->logo = $logoPath;
            }

            $nhaXuatBan->save();

            DB::commit();

            return redirect()->route('admin.nhaxuatban.index')
                ->with('success', 'Cập nhật nhà xuất bản thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified publisher
     */
    public function destroy($nhaxuatban)
    {
        \Log::info("Delete request for NXB ID: " . $nhaxuatban);
        
        try {
            // Find the publisher
            $nhaXuatBan = NhaXuatBan::findOrFail($nhaxuatban);
            \Log::info("Found NXB: " . $nhaXuatBan->ten_nxb);
            
            // Check if publisher has books
            $bookCount = $nhaXuatBan->sach()->count();
            \Log::info("Book count: " . $bookCount);
            
            if ($bookCount > 0) {
                \Log::info("Cannot delete - has books");
                return redirect()->back()
                    ->with('error', 'Không thể xóa nhà xuất bản này vì đang có sách thuộc nhà xuất bản này!');
            }

            DB::beginTransaction();

            // Delete logo if exists
            if ($nhaXuatBan->logo && Storage::disk('public')->exists($nhaXuatBan->logo)) {
                Storage::disk('public')->delete($nhaXuatBan->logo);
                \Log::info("Deleted logo: " . $nhaXuatBan->logo);
            }

            $nhaXuatBan->delete();
            \Log::info("Deleted NXB successfully");

            DB::commit();

            return redirect()->route('admin.nhaxuatban.index')
                ->with('success', 'Xóa nhà xuất bản thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Delete error: " . $e->getMessage());
            
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
            'selected_ids.*' => 'exists:nha_xuat_ban,ma_nxb'
        ]);

        try {
            DB::beginTransaction();

            $selectedIds = $request->selected_ids;
            $action = $request->action;
            $count = 0;

            switch ($action) {
                case 'delete':
                    // Check for publishers with books
                    $publishersWithBooks = NhaXuatBan::whereIn('ma_nxb', $selectedIds)
                        ->whereHas('sach')
                        ->count();

                    if ($publishersWithBooks > 0) {
                        return redirect()->back()
                            ->with('error', 'Không thể xóa một số nhà xuất bản vì đang có sách!');
                    }

                    $publishers = NhaXuatBan::whereIn('ma_nxb', $selectedIds)->get();
                    foreach ($publishers as $publisher) {
                        if ($publisher->logo && Storage::disk('public')->exists($publisher->logo)) {
                            Storage::disk('public')->delete($publisher->logo);
                        }
                        $publisher->delete();
                        $count++;
                    }
                    break;

                case 'activate':
                    $count = NhaXuatBan::whereIn('ma_nxb', $selectedIds)
                        ->update(['trang_thai' => 1]);
                    break;

                case 'deactivate':
                    $count = NhaXuatBan::whereIn('ma_nxb', $selectedIds)
                        ->update(['trang_thai' => 0]);
                    break;
            }

            DB::commit();

            $messages = [
                'delete' => "Đã xóa {$count} nhà xuất bản thành công!",
                'activate' => "Đã kích hoạt {$count} nhà xuất bản thành công!",
                'deactivate' => "Đã vô hiệu hóa {$count} nhà xuất bản thành công!"
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
     * Toggle publisher status
     */
    public function toggleStatus($nhaxuatban)
    {
        try {
            $nhaXuatBan = NhaXuatBan::findOrFail($nhaxuatban);
            $nhaXuatBan->trang_thai = !$nhaXuatBan->trang_thai;
            $nhaXuatBan->save();

            $status = $nhaXuatBan->trang_thai ? 'kích hoạt' : 'vô hiệu hóa';
            
            return response()->json([
                'success' => true,
                'message' => "Đã {$status} nhà xuất bản thành công!",
                'status' => $nhaXuatBan->trang_thai
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export publishers to CSV
     */
    public function export(Request $request)
    {
        $query = NhaXuatBan::with(['sach']);
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_nxb', 'LIKE', "%{$search}%")
                  ->orWhere('dia_chi', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }
        
        if ($request->filled('quoc_gia')) {
            $query->where('quoc_gia', 'LIKE', "%{$request->quoc_gia}%");
        }
        
        $publishers = $query->orderBy('ten_nxb')->get();
        
        $filename = 'nha_xuat_ban_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($publishers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Mã NXB',
                'Tên nhà xuất bản',
                'Đường dẫn',
                'Địa chỉ',
                'Số điện thoại',
                'Email',
                'Website',
                'Mô tả',
                'Năm thành lập',
                'Quốc gia',
                'Trạng thái',
                'Số lượng sách',
                'Ngày tạo',
                'Ngày cập nhật'
            ]);
            
            // Data
            foreach ($publishers as $publisher) {
                fputcsv($file, [
                    $publisher->ma_nxb,
                    $publisher->ten_nxb,
                    $publisher->duong_dan,
                    $publisher->dia_chi,
                    $publisher->so_dien_thoai,
                    $publisher->email,
                    $publisher->website,
                    $publisher->mo_ta,
                    $publisher->nam_thanh_lap,
                    $publisher->quoc_gia,
                    $publisher->trang_thai ? 'Hoạt động' : 'Không hoạt động',
                    $publisher->sach->count(),
                    $publisher->created_at->format('d/m/Y H:i:s'),
                    $publisher->updated_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
