@extends('layouts.app')

@section('title', 'Trang quản trị')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>Trang quản trị
            </h1>
            <p class="text-muted">Tổng quan hệ thống quản lý nhà sách</p>
        </div>
        <div>
            <span class="badge bg-success fs-6">
                <i class="fas fa-clock me-1"></i>
                Cập nhật: {{ now()->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số sách
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_books'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đơn hàng hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['orders_today'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Doanh thu tháng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['revenue_month'] ?? 0, 0, ',', '.') }}đ
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Khách hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_customers'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu 12 tháng gần nhất</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố thể loại</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                </div>
                <div class="card-body">
                    @if(isset($recent_orders) && $recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.donhang.show', $order->id) }}" class="text-decoration-none">
                                                    {{ $order->ma_don }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($order->nguoiDung->ho_ten, 20) }}</td>
                                            <td>{{ number_format($order->tong_tien, 0, ',', '.') }}đ</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'cho_xac_nhan' => 'warning',
                                                        'da_xac_nhan' => 'info',
                                                        'dang_giao' => 'primary',
                                                        'da_giao' => 'success',
                                                        'da_huy' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$order->trang_thai] ?? 'secondary' }}">
                                                    {{ $order->trang_thai_text }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('admin.donhang.index') }}" class="btn btn-primary btn-sm">
                                Xem tất cả đơn hàng
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Chưa có đơn hàng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Books -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sách sắp hết hàng</h6>
                </div>
                <div class="card-body">
                    @if(isset($low_stock_books) && $low_stock_books->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tên sách</th>
                                        <th>Tác giả</th>
                                        <th>Tồn kho</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($low_stock_books as $book)
                                        <tr>
                                            <td>{{ Str::limit($book->ten_sach, 25) }}</td>
                                            <td>{{ Str::limit($book->tacGia->ten_tac_gia, 20) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $book->so_luong_ton <= 5 ? 'danger' : 'warning' }}">
                                                    {{ $book->so_luong_ton }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('sach.edit', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('sach.index', ['trang_thai' => 'in_stock']) }}" class="btn btn-warning btn-sm">
                                Xem tất cả sách tồn kho
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted">Tất cả sách đều có đủ tồn kho</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.sach.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <br>Thêm sách
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.theloai.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-tags fa-2x mb-2"></i>
                                <br>Thêm thể loại
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.tacgia.create') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-user-edit fa-2x mb-2"></i>
                                <br>Thêm tác giả
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.nhaxuatban.create') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <br>Thêm NXB
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.donhang.index', ['trang_thai' => 'cho_xac_nhan']) }}" class="btn btn-outline-danger w-100">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <br>Đơn chờ duyệt
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.magiamgia.create') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-percent fa-2x mb-2"></i>
                                <br>Tạo mã giảm giá
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($chart_data['revenue']['labels'] ?? []),
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: @json($chart_data['revenue']['data'] ?? []),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                    }
                }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json($chart_data['categories']['labels'] ?? []),
        datasets: [{
            data: @json($chart_data['categories']['data'] ?? []),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384',
                '#C9CBCF'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush