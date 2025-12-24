@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('admin.magiamgia.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm mã giảm giá
                    </a>
                </div>

                <div class="card-body">
                    <!-- Statistics -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-tags"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng số</span>
                                    <span class="info-box-number">{{ $stats['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đang hoạt động</span>
                                    <span class="info-box-number">{{ $stats['active'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-gift"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Có thể sử dụng</span>
                                    <span class="info-box-number">{{ $stats['available'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã hết hạn</span>
                                    <span class="info-box-number">{{ $stats['expired'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="trang_thai" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="loai_giam_gia" class="form-control">
                                    <option value="">Tất cả loại</option>
                                    <option value="phan_tram" {{ request('loai_giam_gia') == 'phan_tram' ? 'selected' : '' }}>Phần trăm</option>
                                    <option value="so_tien" {{ request('loai_giam_gia') == 'so_tien' ? 'selected' : '' }}>Số tiền</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Mã code</th>
                                    <th>Tên mã giảm giá</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Số lượng</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maGiamGia as $item)
                                <tr>
                                    <td><code>{{ $item->ma_code }}</code></td>
                                    <td>{{ $item->ten_ma_giam_gia }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->loai_giam_gia == 'phan_tram' ? 'info' : 'success' }}">
                                            {{ $item->loai_giam_gia_text }}
                                        </span>
                                    </td>
                                    <td>{{ $item->gia_tri_giam_text }}</td>
                                    <td>
                                        @if($item->so_luong)
                                            {{ $item->da_su_dung }}/{{ $item->so_luong }}
                                        @else
                                            Không giới hạn
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $item->ngay_bat_dau->format('d/m/Y') }} - 
                                            {{ $item->ngay_ket_thuc->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->trang_thai ? 'success' : 'secondary' }}">
                                            {{ $item->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.magiamgia.show', $item->ma_giam_gia) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.magiamgia.edit', $item->ma_giam_gia) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.magiamgia.destroy', $item->ma_giam_gia) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $maGiamGia->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection