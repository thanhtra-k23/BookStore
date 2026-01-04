@extends('layouts.admin')

@section('title', 'API Testing Dashboard')

@section('content')
<div class="api-test-container">
    <!-- Header -->
    <div class="test-header">
        <h1><i class="fas fa-flask"></i> API Testing Dashboard</h1>
        <p>Kiểm tra các endpoint và chức năng của hệ thống</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        @foreach($stats as $key => $value)
        <div class="stat-card">
            <div class="stat-icon">
                @switch($key)
                    @case('sach') <i class="fas fa-book"></i> @break
                    @case('tac_gia') <i class="fas fa-user-edit"></i> @break
                    @case('the_loai') <i class="fas fa-tags"></i> @break
                    @case('nha_xuat_ban') <i class="fas fa-building"></i> @break
                    @case('nguoi_dung') <i class="fas fa-users"></i> @break
                    @case('don_hang') <i class="fas fa-shopping-bag"></i> @break
                    @case('gio_hang') <i class="fas fa-shopping-cart"></i> @break
                    @case('ma_giam_gia') <i class="fas fa-ticket-alt"></i> @break
                @endswitch
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($value) }}</span>
                <span class="stat-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- API Tester -->
    <div class="test-section">
        <h2><i class="fas fa-terminal"></i> API Tester</h2>
        <div class="api-tester">
            <div class="request-form">
                <div class="form-row">
                    <select id="requestMethod" class="method-select">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                    <input type="text" id="requestUrl" class="url-input" placeholder="Nhập URL (vd: /api/cart/count)" value="/api/cart/count">
                    <button onclick="sendRequest()" class="send-btn">
                        <i class="fas fa-paper-plane"></i> Gửi
                    </button>
                </div>
                <div class="form-row">
                    <textarea id="requestBody" class="body-input" placeholder='Body JSON (cho POST/PUT): {"key": "value"}'></textarea>
                </div>
            </div>
            <div class="response-area">
                <div class="response-header">
                    <span id="responseStatus">Chưa có response</span>
                    <span id="responseDuration"></span>
                </div>
                <pre id="responseBody" class="response-body">Kết quả sẽ hiển thị ở đây...</pre>
            </div>
        </div>
    </div>

    <!-- Quick Tests -->
    <div class="test-section">
        <h2><i class="fas fa-bolt"></i> Quick Tests</h2>
        <div class="quick-tests-grid">

            <!-- Public APIs -->
            <div class="test-group">
                <h3>🌐 Public APIs</h3>
                <button onclick="quickTest('GET', '/')" class="test-btn">
                    <i class="fas fa-home"></i> Trang chủ
                </button>
                <button onclick="quickTest('GET', '/search?q=sach')" class="test-btn">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <button onclick="quickTest('GET', '/categories')" class="test-btn">
                    <i class="fas fa-tags"></i> Danh mục
                </button>
                <button onclick="quickTest('GET', '/authors')" class="test-btn">
                    <i class="fas fa-users"></i> Tác giả
                </button>
                @if($sampleBook)
                <button onclick="quickTest('GET', '/book/{{ $sampleBook->id }}')" class="test-btn">
                    <i class="fas fa-book"></i> Chi tiết sách
                </button>
                @endif
            </div>

            <!-- Cart APIs -->
            <div class="test-group">
                <h3>🛒 Giỏ hàng APIs</h3>
                <button onclick="quickTest('GET', '/api/cart/count')" class="test-btn">
                    <i class="fas fa-hashtag"></i> Số lượng giỏ
                </button>
                <button onclick="quickTest('GET', '/api/cart/items')" class="test-btn">
                    <i class="fas fa-list"></i> Items giỏ hàng
                </button>
                <button onclick="quickTest('GET', '/cart')" class="test-btn">
                    <i class="fas fa-shopping-cart"></i> Trang giỏ hàng
                </button>
                @if($sampleBook)
                <button onclick="quickTest('POST', '/cart/add', {sach_id: {{ $sampleBook->id }}, so_luong: 1})" class="test-btn test-btn-warning">
                    <i class="fas fa-plus"></i> Thêm vào giỏ
                </button>
                @endif
            </div>

            <!-- Admin APIs -->
            <div class="test-group">
                <h3>👨‍💼 Admin APIs</h3>
                <button onclick="quickTest('GET', '/admin/dashboard')" class="test-btn">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </button>
                <button onclick="quickTest('GET', '/admin/stats')" class="test-btn">
                    <i class="fas fa-chart-bar"></i> Thống kê
                </button>
                <button onclick="quickTest('GET', '/admin/revenue-chart')" class="test-btn">
                    <i class="fas fa-chart-line"></i> Biểu đồ doanh thu
                </button>
                <button onclick="quickTest('GET', '/admin/top-selling')" class="test-btn">
                    <i class="fas fa-trophy"></i> Sách bán chạy
                </button>
            </div>

            <!-- CRUD Tests -->
            <div class="test-group">
                <h3>📚 CRUD Sách</h3>
                <button onclick="quickTest('GET', '/admin/sach')" class="test-btn">
                    <i class="fas fa-list"></i> Danh sách
                </button>
                <button onclick="quickTest('GET', '/admin/sach/create')" class="test-btn">
                    <i class="fas fa-plus"></i> Form tạo mới
                </button>
                @if($sampleBook)
                <button onclick="quickTest('GET', '/admin/sach/{{ $sampleBook->id }}')" class="test-btn">
                    <i class="fas fa-eye"></i> Chi tiết
                </button>
                <button onclick="quickTest('GET', '/admin/sach/{{ $sampleBook->id }}/edit')" class="test-btn">
                    <i class="fas fa-edit"></i> Form sửa
                </button>
                @endif
            </div>

            <!-- Auth Tests -->
            <div class="test-group">
                <h3>🔐 Authentication</h3>
                <button onclick="quickTest('GET', '/login')" class="test-btn">
                    <i class="fas fa-sign-in-alt"></i> Trang đăng nhập
                </button>
                <button onclick="quickTest('GET', '/register')" class="test-btn">
                    <i class="fas fa-user-plus"></i> Trang đăng ký
                </button>
                <button onclick="quickTest('GET', '/profile')" class="test-btn">
                    <i class="fas fa-user"></i> Profile
                </button>
            </div>

            <!-- Wishlist & Reviews -->
            <div class="test-group">
                <h3>❤️ Yêu thích & Đánh giá</h3>
                <button onclick="quickTest('GET', '/api/wishlist/count')" class="test-btn">
                    <i class="fas fa-heart"></i> Số yêu thích
                </button>
                <button onclick="quickTest('GET', '/wishlist')" class="test-btn">
                    <i class="fas fa-list"></i> DS yêu thích
                </button>
                @if($sampleBook)
                <button onclick="quickTest('GET', '/api/books/{{ $sampleBook->id }}/reviews')" class="test-btn">
                    <i class="fas fa-star"></i> Đánh giá sách
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Routes List -->
    <div class="test-section">
        <h2><i class="fas fa-route"></i> Tất cả Routes ({{ count($routes) }})</h2>
        <div class="routes-filter">
            <input type="text" id="routeFilter" placeholder="Lọc routes..." onkeyup="filterRoutes()">
            <select id="methodFilter" onchange="filterRoutes()">
                <option value="">Tất cả methods</option>
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
        </div>
        <div class="routes-table-wrapper">
            <table class="routes-table" id="routesTable">
                <thead>
                    <tr>
                        <th>Method</th>
                        <th>URI</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $route)
                    <tr onclick="selectRoute('{{ $route['method'] }}', '/{{ $route['uri'] }}')" class="route-row">
                        <td>
                            <span class="method-badge method-{{ strtolower(explode('|', $route['method'])[0]) }}">
                                {{ $route['method'] }}
                            </span>
                        </td>
                        <td class="route-uri">/{{ $route['uri'] }}</td>
                        <td class="route-name">{{ $route['name'] ?? '-' }}</td>
                        <td class="route-action">{{ class_basename($route['action']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.api-test-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.test-header {
    margin-bottom: 2rem;
}

.test-header h1 {
    font-size: 1.75rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.test-header p {
    color: #64748b;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
}

/* Test Section */
.test-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.test-section h2 {
    font-size: 1.25rem;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* API Tester */
.api-tester {
    display: grid;
    gap: 1rem;
}

.form-row {
    display: flex;
    gap: 0.5rem;
}

.method-select {
    width: 100px;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-weight: 600;
    background: #f8fafc;
}

.url-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-family: monospace;
}

.send-btn {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.send-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.body-input {
    width: 100%;
    min-height: 80px;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-family: monospace;
    resize: vertical;
}

.response-area {
    background: #1e293b;
    border-radius: 8px;
    overflow: hidden;
}

.response-header {
    padding: 0.75rem 1rem;
    background: #334155;
    display: flex;
    justify-content: space-between;
    color: white;
    font-size: 0.875rem;
}

#responseStatus.success { color: #4ade80; }
#responseStatus.error { color: #f87171; }

.response-body {
    padding: 1rem;
    color: #e2e8f0;
    font-family: monospace;
    font-size: 0.875rem;
    max-height: 400px;
    overflow: auto;
    margin: 0;
    white-space: pre-wrap;
    word-break: break-all;
}

/* Quick Tests */
.quick-tests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.test-group {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
}

.test-group h3 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    color: #475569;
}

.test-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
}

.test-btn:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.test-btn-warning {
    background: #fef3c7;
    border-color: #f59e0b;
}

.test-btn-warning:hover {
    background: #f59e0b;
}

/* Routes Table */
.routes-filter {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.routes-filter input,
.routes-filter select {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
}

.routes-filter input {
    flex: 1;
}

.routes-table-wrapper {
    max-height: 500px;
    overflow: auto;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.routes-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.routes-table th {
    background: #f1f5f9;
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    position: sticky;
    top: 0;
}

.routes-table td {
    padding: 0.5rem 1rem;
    border-top: 1px solid #e2e8f0;
}

.route-row {
    cursor: pointer;
    transition: background 0.2s;
}

.route-row:hover {
    background: #f0f9ff;
}

.method-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.method-get { background: #dcfce7; color: #166534; }
.method-post { background: #dbeafe; color: #1e40af; }
.method-put { background: #fef3c7; color: #92400e; }
.method-delete { background: #fee2e2; color: #991b1b; }

.route-uri { font-family: monospace; color: #1e293b; }
.route-name { color: #64748b; }
.route-action { color: #64748b; font-size: 0.75rem; }
</style>

<script>
const csrfToken = '{{ csrf_token() }}';

async function sendRequest() {
    const method = document.getElementById('requestMethod').value;
    const url = document.getElementById('requestUrl').value;
    const bodyText = document.getElementById('requestBody').value;
    
    const statusEl = document.getElementById('responseStatus');
    const durationEl = document.getElementById('responseDuration');
    const bodyEl = document.getElementById('responseBody');
    
    statusEl.textContent = 'Đang gửi...';
    statusEl.className = '';
    durationEl.textContent = '';
    bodyEl.textContent = 'Loading...';
    
    try {
        let body = {};
        if (bodyText.trim()) {
            body = JSON.parse(bodyText);
        }
        
        const response = await fetch('/admin/api-test/run', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                method: method,
                url: url,
                data: body
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            statusEl.textContent = `Status: ${result.status_code}`;
            statusEl.className = result.status_code < 400 ? 'success' : 'error';
            durationEl.textContent = `${result.duration_ms}ms`;
            
            if (result.is_json) {
                bodyEl.textContent = JSON.stringify(result.response, null, 2);
            } else {
                bodyEl.textContent = result.response;
            }
        } else {
            statusEl.textContent = 'Error';
            statusEl.className = 'error';
            bodyEl.textContent = result.error + (result.trace ? '\n\n' + result.trace : '');
        }
    } catch (e) {
        statusEl.textContent = 'Request Failed';
        statusEl.className = 'error';
        bodyEl.textContent = e.message;
    }
}

function quickTest(method, url, data = null) {
    document.getElementById('requestMethod').value = method;
    document.getElementById('requestUrl').value = url;
    document.getElementById('requestBody').value = data ? JSON.stringify(data, null, 2) : '';
    sendRequest();
}

function selectRoute(method, uri) {
    const mainMethod = method.split('|')[0];
    document.getElementById('requestMethod').value = mainMethod;
    document.getElementById('requestUrl').value = uri;
    document.getElementById('requestBody').value = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function filterRoutes() {
    const filter = document.getElementById('routeFilter').value.toLowerCase();
    const methodFilter = document.getElementById('methodFilter').value;
    const rows = document.querySelectorAll('#routesTable tbody tr');
    
    rows.forEach(row => {
        const uri = row.querySelector('.route-uri').textContent.toLowerCase();
        const method = row.querySelector('.method-badge').textContent;
        
        const matchUri = uri.includes(filter);
        const matchMethod = !methodFilter || method.includes(methodFilter);
        
        row.style.display = matchUri && matchMethod ? '' : 'none';
    });
}
</script>
@endsection
