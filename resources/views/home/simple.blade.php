@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container py-5">
    <h1>Trang chủ - Nhà sách online</h1>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Thống kê</h3>
            <ul>
                <li>Tổng số sách: {{ $stats['total_books'] }}</li>
                <li>Tổng số tác giả: {{ $stats['total_authors'] }}</li>
                <li>Tổng số thể loại: {{ $stats['total_categories'] }}</li>
                <li>Tổng số đơn hàng: {{ $stats['total_orders'] }}</li>
            </ul>
        </div>
        
        <div class="col-md-6">
            <h3>Sách nổi bật ({{ $featuredBooks->count() }})</h3>
            @foreach($featuredBooks as $book)
                <div class="mb-2">
                    <strong>{{ $book->ten_sach }}</strong><br>
                    Giá: {{ number_format($book->gia_ban) }}đ<br>
                    Tác giả: {{ $book->tacGia->ten_tac_gia ?? 'N/A' }}<br>
                    Thể loại: {{ $book->theLoai->ten_the_loai ?? 'N/A' }}<br>
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection