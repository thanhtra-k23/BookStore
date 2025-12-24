@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Header Section -->
    <div class="card card-modern mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-book me-2"></i>
                        Quản lý sách
                    </h4>
                    <p class="mb-0 text-muted">
                        Theo dõi và chỉnh sửa danh mục sách trong cửa hàng
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.sach.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Thêm sách mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card card-modern mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sach.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tên sách, tác giả..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Thể loại</label>
                        <select name="the_loai_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($theLoai as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('the_loai_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tác giả</label>
                        <select name="tac_gia_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($tacGia as $author)
                                <option value="{{ $author->id }}" 
                                        {{ request('tac_gia_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->ten_tac_gia }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('trang_thai') == 'active' ? 'selected' : '' }}>
                                Đang bán
                            </option>
                            <option value="in_stock" {{ request('trang_thai') == 'in_stock' ? 'selected' : '' }}>
                                Còn hàng
                            </option>
                            <option value="on_sale" {{ request('trang_thai') == 'on_sale' ? 'selected' : '' }}>
                                Khuyến mãi
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('admin.sach.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card card-modern">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                Danh sách sách ({{ $sach->total() }} kết quả)
            </h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                    <i class="fas fa-check me-1"></i>
                    Kích hoạt
                </button>
                <button class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                    <i class="fas fa-pause me-1"></i>
                    Vô hiệu hóa
                </button>
                <button class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                    <i class="fas fa-trash me-1"></i>
                    Xóa
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($sach->count() > 0)
                <form id="bulkForm" method="POST" action="{{ route('admin.sach.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulkAction">
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Sách</th>
                                    <th>Thông tin</th>
                                    <th>Giá bán</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th width="150">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sach as $book)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $book->id }}" 
                                                   class="form-check-input book-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $book->anh_bia_url }}" 
                                                     alt="{{ $book->ten_sach }}"
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1">{{ Str::limit($book->ten_sach, 40) }}</h6>
                                                    <small class="text-muted">{{ $book->duong_dan }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $book->tacGia->ten_tac_gia }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $book->theLoai->ten_the_loai }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-building me-1"></i>
                                                    {{ $book->nhaXuatBan ? $book->nhaXuatBan->ten_nxb : 'Chưa có NXB' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($book->isOnSale())
                                                <div>
                                                    <span class="text-decoration-line-through text-muted small">
                                                        {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                                                    </span>
                                                    <br>
                                                    <span class="fw-bold text-danger">
                                                        {{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ
                                                    </span>
                                                    <span class="badge bg-danger ms-1">
                                                        -{{ $book->phan_tram_giam_gia }}%
                                                    </span>
                                                </div>
                                            @else
                                                <span class="fw-bold">
                                                    {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $book->so_luong_ton > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $book->so_luong_ton }} cuốn
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $book->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $book->trang_thai_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.sach.show', [$book->id, $book->duong_dan]) }}" 
                                                   class="btn btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.sach.edit', $book->id) }}" 
                                                   class="btn btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-warning" 
                                                        onclick="toggleStatus({{ $book->id }})" 
                                                        title="Đổi trạng thái">
                                                    <i class="fas fa-toggle-{{ $book->trang_thai === 'active' ? 'on' : 'off' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDelete('{{ route('admin.sach.destroy', [$book->id, $book->duong_dan]) }}')" 
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                {{ $sach->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Không có sách nào</h5>
                    <p class="text-muted">Hãy thêm sách mới để bắt đầu quản lý</p>
                    <a href="{{ route('admin.sach.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Thêm sách đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.book-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk actions
    function bulkAction(action) {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Vui lòng chọn ít nhất một sách');
            return;
        }

        let message = '';
        switch(action) {
            case 'activate':
                message = 'Bạn có chắc chắn muốn kích hoạt các sách đã chọn?';
                break;
            case 'deactivate':
                message = 'Bạn có chắc chắn muốn vô hiệu hóa các sách đã chọn?';
                break;
            case 'delete':
                message = 'Bạn có chắc chắn muốn xóa các sách đã chọn? Hành động này không thể hoàn tác!';
                break;
        }

        if (confirm(message)) {
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkForm').submit();
        }
    }

    // Toggle status
    function toggleStatus(bookId) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái sách này?')) {
            window.location.href = `/admin/sach/${bookId}/toggle-status`;
        }
    }

    // Confirm delete single item
    function confirmDelete(deleteUrl) {
        if (confirm('Bạn có chắc chắn muốn xóa sách này? Hành động này không thể hoàn tác!')) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush