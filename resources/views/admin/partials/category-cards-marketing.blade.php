{{-- Category Cards Marketing Style - Hiển thị thể loại với hiệu ứng hover hấp dẫn --}}
@php
    // Icon mapping cho từng thể loại (có thể customize theo tên)
    $iconMapping = [
        'công nghệ' => 'fa-laptop-code',
        'khoa học' => 'fa-flask',
        'kinh tế' => 'fa-chart-line',
        'kỹ năng sống' => 'fa-lightbulb',
        'lịch sử' => 'fa-monument',
        'thiếu nhi' => 'fa-rainbow',
        'văn học' => 'fa-book-open',
        'tiểu thuyết' => 'fa-feather-alt',
        'tâm lý' => 'fa-brain',
        'giáo dục' => 'fa-graduation-cap',
        'y học' => 'fa-heartbeat',
        'ngoại ngữ' => 'fa-language',
        'truyện tranh' => 'fa-palette',
        'sách giáo khoa' => 'fa-school',
        'default' => 'fa-book'
    ];
    
    // Gradient colors cho từng card
    $gradients = [
        ['#667eea', '#764ba2'],
        ['#f093fb', '#f5576c'],
        ['#4facfe', '#00f2fe'],
        ['#43e97b', '#38f9d7'],
        ['#fa709a', '#fee140'],
        ['#a8edea', '#fed6e3'],
        ['#ff9a9e', '#fecfef'],
        ['#ffecd2', '#fcb69f'],
    ];
@endphp

<section class="categories-marketing-section">
    <div class="section-header-marketing">
        <h3>
            <i class="fas fa-tags me-2"></i>
            Khám Phá Thể Loại Sách 
            <span class="emoji-sparkle">🌟</span>
        </h3>
        <a href="{{ route('admin.theloai.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-list me-1"></i> Quản lý tất cả
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($categories ?? [] as $index => $category)
            @php
                $tenLower = mb_strtolower($category->ten_the_loai);
                $icon = $iconMapping['default'];
                foreach ($iconMapping as $key => $value) {
                    if (str_contains($tenLower, $key)) {
                        $icon = $value;
                        break;
                    }
                }
                $gradient = $gradients[$index % count($gradients)];
                $bookCount = $category->sach_count ?? $category->sach->count() ?? 0;
                
                // Xác định badge
                $badge = '';
                if ($bookCount > 20) {
                    $badge = 'hot';
                } elseif ($category->created_at && $category->created_at->diffInDays(now()) < 30) {
                    $badge = 'new';
                }
            @endphp
            
            <div class="col-xl-4 col-md-6">
                <a href="{{ route('admin.theloai.show', $category) }}" class="category-card-marketing">
                    <div class="card-inner-marketing">
                        {{-- Badge --}}
                        @if($badge === 'hot')
                            <span class="category-badge hot-badge">🔥 Hot</span>
                        @elseif($badge === 'new')
                            <span class="category-badge new-badge">🆕 Mới</span>
                        @endif
                        
                        {{-- Icon --}}
                        <div class="icon-wrapper-marketing" style="background: linear-gradient(135deg, {{ $gradient[0] }}, {{ $gradient[1] }});">
                            @if($category->hinh_anh)
                                <img src="{{ Storage::url($category->hinh_anh) }}" alt="{{ $category->ten_the_loai }}" class="category-img">
                            @else
                                <i class="fas {{ $icon }}"></i>
                            @endif
                        </div>
                        
                        {{-- Content --}}
                        <h4 class="category-title-marketing">{{ $category->ten_the_loai }}</h4>
                        <p class="category-subtitle-marketing">
                            {{ $bookCount }} cuốn sách
                            @if($category->mo_ta)
                                <br><small>{{ Str::limit($category->mo_ta, 40) }}</small>
                            @endif
                        </p>
                        
                        {{-- Status indicator --}}
                        <div class="category-status">
                            @if($category->trang_thai)
                                <span class="status-dot active"></span> Đang hoạt động
                            @else
                                <span class="status-dot inactive"></span> Tạm ẩn
                            @endif
                        </div>
                        
                        {{-- CTA --}}
                        <div class="category-cta-marketing">
                            Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-categories-state">
                    <i class="fas fa-folder-open"></i>
                    <h5>Chưa có thể loại nào</h5>
                    <p>Hãy thêm thể loại đầu tiên để bắt đầu</p>
                    <a href="{{ route('admin.theloai.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Thêm thể loại
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    {{-- View All Button --}}
    @if(isset($categories) && $categories->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('admin.theloai.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i> Xem tất cả {{ $totalCategories ?? $categories->count() }} thể loại
            </a>
        </div>
    @endif
</section>
