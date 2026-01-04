@extends('layouts.admin')

@section('title', 'Dashboard - Quản trị')

@push('styles')
<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3b82f6 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-banner h1 { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
    .welcome-banner p { opacity: 0.9; font-size: 1rem; margin: 0; }
    .welcome-stats { display: flex; gap: 2rem; margin-top: 1.5rem; }
    .welcome-stat { text-align: center; }
    .welcome-stat-value { font-size: 1.5rem; font-weight: 800; }
    .welcome-stat-label { font-size: 0.8rem; opacity: 0.8; }

    /* Stats Grid */
    .stats-grid-modern {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .stat-card-modern {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        transition: all 0.3s;
        border: 1px solid #f1f5f9;
    }
    .stat-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }
</style>
<style>
    .stat-icon-modern {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; margin-bottom: 1rem;
    }
    .stat-card-modern.primary .stat-icon-modern { background: #eff6ff; color: #3b82f6; }
    .stat-card-modern.success .stat-icon-modern { background: #f0fdf4; color: #22c55e; }
    .stat-card-modern.warning .stat-icon-modern { background: #fffbeb; color: #f59e0b; }
    .stat-card-modern.purple .stat-icon-modern { background: #faf5ff; color: #a855f7; }
    .stat-value-modern { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
    .stat-label-modern { font-size: 0.85rem; color: #64748b; font-weight: 500; }
    .stat-change-modern {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.75rem; font-weight: 600; padding: 4px 8px;
        border-radius: 6px; margin-top: 0.75rem;
    }
    .stat-change-modern.positive { background: #f0fdf4; color: #16a34a; }
    .stat-change-modern.negative { background: #fef2f2; color: #dc2626; }
    .stat-change-modern.neutral { background: #f8fafc; color: #64748b; }

    /* Quick Actions */
    .quick-actions-modern { display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .quick-action-modern {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.25rem; background: white; border: 1px solid #e2e8f0;
        border-radius: 10px; color: #475569; text-decoration: none;
        font-weight: 600; font-size: 0.9rem; transition: all 0.2s;
    }
    .quick-action-modern:hover { background: #3b82f6; color: white; border-color: #3b82f6; transform: translateY(-2px); }
    .quick-action-modern.danger:hover { background: #ef4444; border-color: #ef4444; }
    .quick-action-modern.success:hover { background: #22c55e; border-color: #22c55e; }

    /* Grid Layout */
    .dashboard-grid { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; margin-bottom: 2rem; }
    .dashboard-grid-full { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }

    /* Card Modern */
    .card-modern { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; overflow: hidden; }
    .card-modern-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .card-modern-header h3 { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px; }
    .card-modern-body { padding: 1.5rem; }
    .card-modern-body.no-padding { padding: 0; }
    .chart-container-modern { height: 280px; position: relative; }
</style>
<style>
    /* Order Status Pills */
    .order-status-pills { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .order-pill { flex: 1; min-width: 140px; padding: 1rem; border-radius: 12px; text-align: center; text-decoration: none; transition: all 0.3s; }
    .order-pill:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .order-pill.pending { background: #fef3c7; }
    .order-pill.confirmed { background: #dbeafe; }
    .order-pill.shipping { background: #e0e7ff; }
    .order-pill.completed { background: #d1fae5; }
    .order-pill-count { font-size: 1.5rem; font-weight: 800; color: #1e293b; display: block; }
    .order-pill-label { font-size: 0.8rem; color: #64748b; font-weight: 600; }

    /* Table Modern */
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern th { padding: 0.875rem 1rem; text-align: left; font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .table-modern td { padding: 1rem; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 0.9rem; }
    .table-modern tr:hover td { background: #f8fafc; }
    .table-modern tr:last-child td { border-bottom: none; }

    /* Low Stock */
    .low-stock-item { display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 0; border-bottom: 1px solid #f1f5f9; }
    .low-stock-item:last-child { border-bottom: none; }
    .low-stock-info { display: flex; align-items: center; gap: 0.75rem; }
    .low-stock-thumb { width: 40px; height: 50px; border-radius: 6px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .low-stock-name { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
    .low-stock-author { font-size: 0.8rem; color: #64748b; }
    .stock-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
    .stock-badge.critical { background: #fef2f2; color: #dc2626; }
    .stock-badge.warning { background: #fffbeb; color: #d97706; }

    /* Best Sellers */
    .bestseller-item { display: flex; align-items: center; gap: 1rem; padding: 0.875rem 0; border-bottom: 1px solid #f1f5f9; }
    .bestseller-item:last-child { border-bottom: none; }
    .bestseller-rank { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    .bestseller-rank.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; }
    .bestseller-rank.silver { background: linear-gradient(135deg, #9ca3af, #6b7280); color: white; }
    .bestseller-rank.bronze { background: linear-gradient(135deg, #d97706, #b45309); color: white; }
    .bestseller-rank.normal { background: #f1f5f9; color: #64748b; }
    .bestseller-info { flex: 1; }
    .bestseller-name { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
    .bestseller-stats { font-size: 0.8rem; color: #64748b; }
    .bestseller-revenue { font-weight: 700; color: #16a34a; font-size: 0.9rem; }
</style>
<style>
    /* Empty State */
    .empty-state-modern { text-align: center; padding: 2.5rem 1.5rem; }
    .empty-state-modern .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
    .empty-state-modern h4 { color: #64748b; font-size: 1rem; margin-bottom: 0.25rem; }
    .empty-state-modern p { color: #94a3b8; font-size: 0.85rem; }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid-modern { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .stats-grid-modern { grid-template-columns: 1fr; }
        .dashboard-grid-full { grid-template-columns: 1fr; }
        .welcome-stats { flex-wrap: wrap; gap: 1rem; }
        .order-status-pills { flex-direction: column; }
    }
</style>
@endpush

@section('content')
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h1>👋 Xin chào, {{ Auth::user()->ho_ten ?? 'Admin' }}!</h1>
        <p>Đây là tổng quan hoạt động kinh doanh của bạn hôm nay</p>
        <div class="welcome-stats">
            <div class="welcome-stat">
                <div class="welcome-stat-value">{{ number_format($stats['orders_today'] ?? 0) }}</div>
                <div class="welcome-stat-label">Đơn hàng hôm nay</div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-value">{{ number_format($stats['revenue_month'] ?? 0, 0, ',', '.') }}đ</div>
                <div class="welcome-stat-label">Doanh thu tháng {{ now()->month }}</div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-value">{{ $stats['pending_orders'] ?? 0 }}</div>
                <div class="welcome-stat-label">Đơn chờ xử lý</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid-modern">
        <div class="stat-card-modern primary">
            <div class="stat-icon-modern"><i class="fas fa-book"></i></div>
            <div class="stat-value-modern">{{ number_format($stats['total_books'] ?? 0) }}</div>
            <div class="stat-label-modern">Tổng số sách</div>
            <div class="stat-change-modern positive"><i class="fas fa-check-circle"></i> {{ $stats['active_books'] ?? 0 }} đang bán</div>
        </div>
        <div class="stat-card-modern success">
            <div class="stat-icon-modern"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-value-modern">{{ number_format($stats['orders_today'] ?? 0) }}</div>
            <div class="stat-label-modern">Đơn hàng hôm nay</div>
            <div class="stat-change-modern {{ ($stats['pending_orders'] ?? 0) > 0 ? 'negative' : 'positive' }}"><i class="fas fa-clock"></i> {{ $stats['pending_orders'] ?? 0 }} chờ xử lý</div>
        </div>
        <div class="stat-card-modern warning">
            <div class="stat-icon-modern"><i class="fas fa-coins"></i></div>
            <div class="stat-value-modern">{{ number_format(($stats['revenue_month'] ?? 0) / 1000000, 1) }}M</div>
            <div class="stat-label-modern">Doanh thu tháng</div>
            <div class="stat-change-modern neutral"><i class="fas fa-calendar"></i> Tháng {{ now()->month }}/{{ now()->year }}</div>
        </div>
        <div class="stat-card-modern purple">
            <div class="stat-icon-modern"><i class="fas fa-users"></i></div>
            <div class="stat-value-modern">{{ number_format($stats['total_customers'] ?? 0) }}</div>
            <div class="stat-label-modern">Khách hàng</div>
            <div class="stat-change-modern positive"><i class="fas fa-user-plus"></i> +{{ $stats['new_customers_month'] ?? 0 }} tháng này</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-modern">
        <a href="{{ route('admin.sach.create') }}" class="quick-action-modern"><i class="fas fa-plus"></i> Thêm sách mới</a>
        <a href="{{ route('admin.donhang.index', ['trang_thai' => 'cho_xac_nhan']) }}" class="quick-action-modern danger"><i class="fas fa-clock"></i> Đơn chờ duyệt ({{ $stats['pending_orders'] ?? 0 }})</a>
        <a href="{{ route('admin.magiamgia.create') }}" class="quick-action-modern success"><i class="fas fa-tag"></i> Tạo mã giảm giá</a>
        <a href="{{ route('admin.sach.index') }}" class="quick-action-modern"><i class="fas fa-warehouse"></i> Quản lý kho</a>
        <a href="{{ route('cart.index') }}" class="quick-action-modern" style="background: #fef3c7; border-color: #f59e0b;"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a>
    </div>

    <!-- Order Status Pills -->
    <div class="card-modern" style="margin-bottom: 2rem;">
        <div class="card-modern-header">
            <h3><i class="fas fa-truck"></i> Trạng thái đơn hàng</h3>
            <a href="{{ route('admin.donhang.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="card-modern-body">
            <div class="order-status-pills">
                <a href="{{ route('admin.donhang.index', ['trang_thai' => 'cho_xac_nhan']) }}" class="order-pill pending">
                    <span class="order-pill-count">{{ $order_stats['pending'] ?? 0 }}</span>
                    <span class="order-pill-label">⏳ Chờ xác nhận</span>
                </a>
                <a href="{{ route('admin.donhang.index', ['trang_thai' => 'da_xac_nhan']) }}" class="order-pill confirmed">
                    <span class="order-pill-count">{{ $order_stats['confirmed'] ?? 0 }}</span>
                    <span class="order-pill-label">✅ Đã xác nhận</span>
                </a>
                <a href="{{ route('admin.donhang.index', ['trang_thai' => 'dang_giao']) }}" class="order-pill shipping">
                    <span class="order-pill-count">{{ $order_stats['shipping'] ?? 0 }}</span>
                    <span class="order-pill-label">🚚 Đang giao</span>
                </a>
                <a href="{{ route('admin.donhang.index', ['trang_thai' => 'da_giao']) }}" class="order-pill completed">
                    <span class="order-pill-count">{{ $order_stats['completed'] ?? 0 }}</span>
                    <span class="order-pill-label">🎉 Hoàn thành</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Revenue Chart -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-chart-line"></i> Biểu đồ doanh thu</h3>
                <select id="chartPeriod" class="form-select" style="width: auto; padding: 0.4rem 2rem 0.4rem 0.75rem;" onchange="updateChart()">
                    <option value="7days">7 ngày qua</option>
                    <option value="30days">30 ngày qua</option>
                    <option value="12months" selected>12 tháng qua</option>
                </select>
            </div>
            <div class="card-modern-body">
                <div class="chart-container-modern">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i> Sách sắp hết</h3>
                <a href="{{ route('admin.sach.index') }}" class="btn btn-sm btn-outline-warning">Xem kho</a>
            </div>
            <div class="card-modern-body">
                @if(isset($low_stock_books) && $low_stock_books->count() > 0)
                    @foreach($low_stock_books->take(5) as $book)
                        <div class="low-stock-item">
                            <div class="low-stock-info">
                                <div class="low-stock-thumb">📚</div>
                                <div>
                                    <div class="low-stock-name">{{ Str::limit($book->ten_sach, 25) }}</div>
                                    <div class="low-stock-author">{{ $book->tacGia->ten_tac_gia ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <span class="stock-badge {{ $book->so_luong_ton <= 5 ? 'critical' : 'warning' }}">{{ $book->so_luong_ton }} còn</span>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state-modern">
                        <div class="icon">✅</div>
                        <h4>Kho hàng ổn định</h4>
                        <p>Tất cả sách đều đủ tồn kho</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders & Best Sellers -->
    <div class="dashboard-grid-full">
        <!-- Recent Orders -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-shopping-bag"></i> Đơn hàng gần đây</h3>
                <a href="{{ route('admin.donhang.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-modern-body no-padding">
                @if(isset($recent_orders) && $recent_orders->count() > 0)
                    <table class="table-modern">
                        <thead>
                            <tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders->take(5) as $order)
                                <tr>
                                    <td><a href="{{ route('admin.donhang.show', $order->ma_don_hang) }}" style="font-weight: 600; color: #3b82f6; text-decoration: none;">#{{ $order->ma_don }}</a></td>
                                    <td>{{ Str::limit($order->ho_ten_nguoi_nhan ?? 'N/A', 20) }}</td>
                                    <td style="font-weight: 600;">{{ number_format($order->tong_tien, 0, ',', '.') }}đ</td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'cho_xac_nhan' => ['class' => 'warning', 'text' => '⏳ Chờ'],
                                                'da_xac_nhan' => ['class' => 'info', 'text' => '✅ Xác nhận'],
                                                'dang_giao' => ['class' => 'primary', 'text' => '🚚 Giao'],
                                                'da_giao' => ['class' => 'success', 'text' => '🎉 Xong'],
                                                'da_huy' => ['class' => 'danger', 'text' => '❌ Hủy']
                                            ];
                                            $config = $statusConfig[$order->trang_thai] ?? ['class' => 'secondary', 'text' => '?'];
                                        @endphp
                                        <span class="badge badge-{{ $config['class'] }}">{{ $config['text'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state-modern">
                        <div class="icon">📭</div>
                        <h4>Chưa có đơn hàng</h4>
                        <p>Đơn hàng mới sẽ hiển thị ở đây</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-trophy" style="color: #f59e0b;"></i> Sách bán chạy</h3>
            </div>
            <div class="card-modern-body">
                @if(isset($best_sellers) && $best_sellers->count() > 0)
                    @foreach($best_sellers->take(5) as $index => $book)
                        <div class="bestseller-item">
                            <div class="bestseller-rank {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal')) }}">{{ $index + 1 }}</div>
                            <div class="bestseller-info">
                                <div class="bestseller-name">{{ Str::limit($book->ten_sach, 30) }}</div>
                                <div class="bestseller-stats">Đã bán: {{ $book->total_sold }}</div>
                            </div>
                            <div class="bestseller-revenue">{{ number_format($book->total_revenue / 1000) }}K</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state-modern">
                        <div class="icon">📊</div>
                        <h4>Chưa có dữ liệu</h4>
                        <p>Dữ liệu bán hàng sẽ hiển thị ở đây</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Distribution & Featured Categories -->
    <div class="dashboard-grid-full">
        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-folder"></i> Phân bố thể loại</h3>
            </div>
            <div class="card-modern-body">
                <div class="chart-container-modern" style="height: 250px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-modern-header">
                <h3><i class="fas fa-tags"></i> Thể loại nổi bật</h3>
                <a href="{{ route('admin.theloai.index') }}" class="btn btn-sm btn-outline-primary">Quản lý</a>
            </div>
            <div class="card-modern-body">
                @if(isset($categories) && $categories->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                        @foreach($categories->take(4) as $category)
                            <a href="{{ route('admin.theloai.show', $category) }}" style="text-decoration: none; padding: 1rem; background: #f8fafc; border-radius: 10px; display: block; transition: all 0.2s;">
                                <div style="font-weight: 600; color: #1e293b;">{{ $category->ten_the_loai }}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">{{ $category->sach_count ?? 0 }} sách</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let revenueChart;

document.addEventListener('DOMContentLoaded', function() {
    initRevenueChart();
    initCategoryChart();
});

function initRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart_data['revenue']['labels'] ?? []),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chart_data['revenue']['data'] ?? []),
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } }
            }
        }
    });
}

function initCategoryChart() {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($chart_data['categories']['labels'] ?? []),
            datasets: [{
                data: @json($chart_data['categories']['data'] ?? []),
                backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'right', labels: { padding: 15, usePointStyle: true } } }
        }
    });
}

function updateChart() {
    const period = document.getElementById('chartPeriod').value;
    fetch(`/admin/revenue-chart?period=${period}`)
        .then(response => response.json())
        .then(data => {
            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.data;
            revenueChart.update();
        });
}
</script>
@endpush
