@extends('layouts.app')

@section('title', 'Chi tiết nhà xuất bản')

@section('content')
<div class="container-fluid">
    <h1>Chi tiết nhà xuất bản: {{ $nhaXuatBan->ten_nxb }}</h1>
    
    <div class="card">
        <div class="card-body">
            <p><strong>Mã NXB:</strong> {{ $nhaXuatBan->ma_nxb }}</p>
            <p><strong>Địa chỉ:</strong> {{ $nhaXuatBan->dia_chi }}</p>
            <p><strong>Số sách:</strong> {{ $stats['total_books'] }}</p>
            
            <div class="mt-3">
                <a href="{{ route('admin.nhaxuatban.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="/admin/nhaxuatban/{{ $nhaXuatBan->ma_nxb }}/edit" class="btn btn-warning">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection