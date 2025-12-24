<?php

namespace App\Http\Controllers;

use App\Models\Sach;
use App\Models\TacGia;
use App\Models\TheLoai;
use App\Models\NhaXuatBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Quản lý sách';
        
        $query = Sach::with(['tacGia', 'theLoai', 'nhaXuatBan']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        // Filter by category
        if ($request->has('the_loai_id') && !empty($request->the_loai_id)) {
            $query->byCategory($request->the_loai_id);
        }
        
        // Filter by author
        if ($request->has('tac_gia_id') && !empty($request->tac_gia_id)) {
            $query->byAuthor($request->tac_gia_id);
        }
        
        // Filter by publisher
        if ($request->has('nha_xuat_ban_id') && !empty($request->nha_xuat_ban_id)) {
            $query->byPublisher($request->nha_xuat_ban_id);
        }
        
        // Filter by status
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            if ($request->trang_thai === 'active') {
                $query->active();
            } elseif ($request->trang_thai === 'in_stock') {
                $query->inStock();
            } elseif ($request->trang_thai === 'on_sale') {
                $query->onSale();
            }
        }
        
        $sach = $query->orderBy('created_at', 'desc')->paginate(15);
        $tacGia = TacGia::orderBy('ten_tac_gia', 'asc')->get();
        $theLoai = TheLoai::orderBy('ten_the_loai', 'asc')->get();
        $nhaXuatBan = NhaXuatBan::orderBy('ten_nxb', 'asc')->get();
        
        return view('sach.index', compact('sach', 'tacGia', 'theLoai', 'nhaXuatBan', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm sách mới';
        $tacGia = TacGia::orderBy('ten_tac_gia', 'asc')->get();
        $theLoai = TheLoai::orderBy('ten_the_loai', 'asc')->get();
        $nhaXuatBan = NhaXuatBan::orderBy('ten_nxb', 'asc')->get();
        
        return view('sach.create', compact('tacGia', 'theLoai', 'nhaXuatBan', 'title'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'ten_sach' => 'required|string|max:255',
            'tac_gia_id' => 'required|exists:tac_gia,ma_tac_gia',
            'the_loai_id' => 'required|exists:the_loai,ma_the_loai',
            'nha_xuat_ban_id' => 'required|exists:nha_xuat_ban,ma_nxb',
            'mo_ta' => 'required|string',
            'gia_ban' => 'required|numeric|min:0',
            'gia_khuyen_mai' => 'nullable|numeric|min:0|lt:gia_ban',
            'so_luong_ton' => 'required|integer|min:0',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'anh_bia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'required|in:active,inactive'
        ];

        $messages = [
            'ten_sach.required' => 'Vui lòng nhập tên sách',
            'tac_gia_id.required' => 'Vui lòng chọn tác giả',
            'tac_gia_id.exists' => 'Tác giả không tồn tại',
            'the_loai_id.required' => 'Vui lòng chọn thể loại',
            'the_loai_id.exists' => 'Thể loại không tồn tại',
            'nha_xuat_ban_id.required' => 'Vui lòng chọn nhà xuất bản',
            'nha_xuat_ban_id.exists' => 'Nhà xuất bản không tồn tại',
            'mo_ta.required' => 'Vui lòng nhập mô tả',
            'gia_ban.required' => 'Vui lòng nhập giá bán',
            'gia_ban.numeric' => 'Giá bán phải là số',
            'gia_khuyen_mai.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán',
            'so_luong_ton.required' => 'Vui lòng nhập số lượng tồn',
            'nam_xuat_ban.required' => 'Vui lòng nhập năm xuất bản',
            'anh_bia.image' => 'File phải là hình ảnh',
            'anh_bia.max' => 'Kích thước file không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        // Generate unique slug (including soft deleted records)
        $baseSlug = Str::slug($request->ten_sach);
        $slug = $baseSlug;
        $counter = 1;
        
        while (Sach::withTrashed()->where('duong_dan', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = $request->only([
            'ten_sach', 'mo_ta', 'gia_ban', 'gia_khuyen_mai', 'so_luong_ton',
            'nam_xuat_ban', 'trang_thai'
        ]);
        
        // Add the unique slug
        $data['duong_dan'] = $slug;
        
        // Map form fields to database columns
        $data['ma_the_loai'] = $request->the_loai_id;
        $data['ma_tac_gia'] = $request->tac_gia_id;
        $data['ma_nxb'] = $request->nha_xuat_ban_id;

        // Handle image upload
        if ($request->hasFile('anh_bia')) {
            $image = $request->file('anh_bia');
            $imageName = time() . '_' . Str::slug($request->ten_sach) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('books', $imageName, 'public');
            $data['hinh_anh'] = $imagePath;
        }

        Sach::create($data);

        return redirect()->route('admin.sach.index')
            ->with('tb_success', 'Thêm sách thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $slug = null)
    {
        $sach = Sach::with(['tacGia', 'theLoai', 'nhaXuatBan'])
                    ->findOrFail($id);

        if ($slug && $slug !== $sach->duong_dan) {
            return redirect()->route('sach.show', [$sach->id, $sach->duong_dan]);
        }

        // Increment view count
        $sach->incrementView();

        // Get related books
        $relatedBooks = Sach::where('the_loai_id', $sach->the_loai_id)
                           ->where('id', '!=', $sach->id)
                           ->active()
                           ->limit(6)
                           ->get();

        $title = $sach->ten_sach;

        return view('sach.show', compact('sach', 'relatedBooks', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sach = Sach::findOrFail($id);
        $title = 'Chỉnh sửa sách: ' . $sach->ten_sach;
        $tacGia = TacGia::orderBy('ten_tac_gia', 'asc')->get();
        $theLoai = TheLoai::orderBy('ten_the_loai', 'asc')->get();
        $nhaXuatBan = NhaXuatBan::orderBy('ten_nxb', 'asc')->get();

        return view('sach.edit', compact('sach', 'tacGia', 'theLoai', 'nhaXuatBan', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sach = Sach::findOrFail($id);

        $rules = [
            'ten_sach' => 'required|string|max:255',
            'tac_gia_id' => 'required|exists:tac_gia,ma_tac_gia',
            'the_loai_id' => 'required|exists:the_loai,ma_the_loai',
            'nha_xuat_ban_id' => 'required|exists:nha_xuat_ban,ma_nxb',
            'mo_ta' => 'required|string',
            'gia_ban' => 'required|numeric|min:0',
            'gia_khuyen_mai' => 'nullable|numeric|min:0|lt:gia_ban',
            'so_luong_ton' => 'required|integer|min:0',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'anh_bia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'required|in:active,inactive'
        ];

        $messages = [
            'ten_sach.required' => 'Vui lòng nhập tên sách',
            'gia_khuyen_mai.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán',
            'anh_bia.image' => 'File phải là hình ảnh',
            'anh_bia.max' => 'Kích thước file không được vượt quá 2MB'
        ];

        $request->validate($rules, $messages);

        // Generate unique slug if name changed (including soft deleted records)
        $baseSlug = Str::slug($request->ten_sach);
        $slug = $baseSlug;
        $counter = 1;
        
        while (Sach::withTrashed()->where('duong_dan', $slug)->where('ma_sach', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = $request->only([
            'ten_sach', 'mo_ta', 'gia_ban', 'gia_khuyen_mai', 'so_luong_ton',
            'nam_xuat_ban', 'trang_thai'
        ]);
        
        // Add the unique slug
        $data['duong_dan'] = $slug;
        
        // Map form fields to database columns
        $data['ma_the_loai'] = $request->the_loai_id;
        $data['ma_tac_gia'] = $request->tac_gia_id;
        $data['ma_nxb'] = $request->nha_xuat_ban_id;

        // Handle image upload
        if ($request->hasFile('anh_bia')) {
            // Delete old image
            if ($sach->hinh_anh && Storage::disk('public')->exists($sach->hinh_anh)) {
                Storage::disk('public')->delete($sach->hinh_anh);
            }

            $image = $request->file('anh_bia');
            $imageName = time() . '_' . Str::slug($request->ten_sach) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('books', $imageName, 'public');
            $data['hinh_anh'] = $imagePath;
        }

        $sach->update($data);

        return redirect()->route('admin.sach.index')
            ->with('tb_success', 'Cập nhật sách thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $slug = null)
    {
        $sach = Sach::find($id);
        
        if (!$sach) {
            return redirect()->route('admin.sach.index')
                ->with('tb_danger', 'Không tìm thấy sách');
        }
        
        if ($slug && $slug !== $sach->duong_dan) {
            return redirect()->route('admin.sach.index')
                ->with('tb_danger', 'Đường dẫn không hợp lệ');
        }

        // Check if book has orders
        if ($sach->chiTietDonHangs()->count() > 0) {
            return redirect()->route('admin.sach.index')
                ->with('tb_danger', 'Không thể xóa sách đã có đơn hàng');
        }

        // Delete image if exists
        if ($sach->hinh_anh && Storage::disk('public')->exists($sach->hinh_anh)) {
            Storage::disk('public')->delete($sach->hinh_anh);
        }

        $sach->delete();
        
        return redirect()->route('admin.sach.index')
            ->with('tb_success', 'Xóa sách thành công');
    }

    /**
     * Toggle book status
     */
    public function toggleStatus($id)
    {
        $sach = Sach::findOrFail($id);
        $sach->trang_thai = $sach->trang_thai === 'active' ? 'inactive' : 'active';
        $sach->save();

        $status = $sach->trang_thai === 'active' ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()->back()
            ->with('tb_success', "Đã {$status} sách thành công");
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
                ->with('tb_danger', 'Vui lòng chọn ít nhất một sách');
        }

        switch ($action) {
            case 'delete':
                $count = Sach::whereIn('id', $ids)
                           ->whereDoesntHave('chiTietDonHangs')
                           ->delete();
                return redirect()->back()
                    ->with('tb_success', "Đã xóa {$count} sách");

            case 'activate':
                Sach::whereIn('id', $ids)->update(['trang_thai' => 'active']);
                return redirect()->back()
                    ->with('tb_success', 'Đã kích hoạt các sách đã chọn');

            case 'deactivate':
                Sach::whereIn('id', $ids)->update(['trang_thai' => 'inactive']);
                return redirect()->back()
                    ->with('tb_success', 'Đã vô hiệu hóa các sách đã chọn');

            default:
                return redirect()->back()
                    ->with('tb_danger', 'Hành động không hợp lệ');
        }
    }
}
