@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">✍️ Tất cả tác giả</h1>
        <p style="margin: 0.5rem 0 0 0; color: #6b7280;">
            Khám phá các tác giả nổi tiếng tại BookStore
        </p>
    </div>
</div>

<!-- Search Form -->
<div class="card">
    <form method="GET" action="{{ route('authors') }}">
        <div class="d-flex" style="gap: 0.5rem;">
            <input type="text" 
                   class="form-control" 
                   name="search" 
                   placeholder="Tìm kiếm tác giả..." 
                   value="{{ request('search') }}"
                   style="flex: 1;">
            <button class="btn btn-primary" type="submit">
                🔍 Tìm kiếm
            </button>
        </div>
    </form>
</div>

@if($authors->count() > 0)
    <div class="row">
        @foreach($authors as $author)
            <div class="col-4">
                <div class="card">
                    <div class="text-center">
                        <div style="margin-bottom: 1rem;">
                            @if($author->hinh_anh)
                                <img src="{{ asset('storage/' . $author->hinh_anh) }}" 
                                     alt="{{ $author->ten_tac_gia }}" 
                                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 80px; height: 80px; background: #16a34a; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                    👤
                                </div>
                            @endif
                        </div>
                        
                        <h3 style="margin-bottom: 0.5rem; color: #1f2937;">{{ $author->ten_tac_gia }}</h3>
                        
                        @if($author->tieu_su)
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">
                                {{ Str::limit($author->tieu_su, 100) }}
                            </p>
                        @endif
                        
                        @if($author->quoc_tich)
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                🏳️ {{ $author->quoc_tich }}
                            </p>
                        @endif
                        
                        <p style="color: #16a34a; font-weight: bold; margin-bottom: 1rem;">
                            {{ $author->sach_count }} cuốn sách
                        </p>
                        
                        <a href="{{ route('author', $author->duong_dan) }}" 
                           class="btn btn-success">
                            👁️ Xem sách
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($authors->hasPages())
        <div class="text-center" style="margin-top: 2rem;">
            {{ $authors->links() }}
        </div>
    @endif
@else
    <div class="text-center" style="padding: 3rem 1rem;">
        <div style="font-size: 4rem; color: #9ca3af; margin-bottom: 1rem;">👥</div>
        <h3 style="color: #6b7280; margin-bottom: 0.5rem;">
            @if(request('search'))
                Không tìm thấy tác giả nào
            @else
                Chưa có tác giả nào
            @endif
        </h3>
        <p style="color: #9ca3af;">
            @if(request('search'))
                Không có tác giả nào phù hợp với từ khóa "{{ request('search') }}"
            @else
                Hệ thống chưa có tác giả nào được tạo.
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('authors') }}" class="btn btn-primary">
                Xem tất cả tác giả
            </a>
        @endif
    </div>
@endif
@endsection