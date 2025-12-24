@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">📚 Tất cả thể loại sách</h1>
        <p style="margin: 0.5rem 0 0 0; color: #6b7280;">
            Khám phá các thể loại sách đa dạng tại BookStore
        </p>
    </div>
</div>

@if($categories->count() > 0)
    <div class="row">
        @foreach($categories as $category)
            <div class="col-4">
                <div class="card">
                    <div class="text-center">
                        <div style="margin-bottom: 1rem;">
                            @if($category->hinh_anh)
                                <img src="{{ asset('storage/' . $category->hinh_anh) }}" 
                                     alt="{{ $category->ten_the_loai }}" 
                                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 80px; height: 80px; background: #2563eb; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                    📖
                                </div>
                            @endif
                        </div>
                        
                        <h3 style="margin-bottom: 0.5rem; color: #1f2937;">{{ $category->ten_the_loai }}</h3>
                        
                        @if($category->mo_ta)
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">
                                {{ Str::limit($category->mo_ta, 100) }}
                            </p>
                        @endif
                        
                        <p style="color: #2563eb; font-weight: bold; margin-bottom: 1rem;">
                            {{ $category->sach_count }} cuốn sách
                        </p>
                        
                        <a href="{{ route('category', $category->duong_dan) }}" 
                           class="btn btn-primary">
                            👁️ Xem sách
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center" style="padding: 3rem 1rem;">
        <div style="font-size: 4rem; color: #9ca3af; margin-bottom: 1rem;">📁</div>
        <h3 style="color: #6b7280; margin-bottom: 0.5rem;">Chưa có thể loại nào</h3>
        <p style="color: #9ca3af;">Hệ thống chưa có thể loại sách nào được tạo.</p>
    </div>
@endif
@endsection

@push('styles')
<style>
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
@endpush