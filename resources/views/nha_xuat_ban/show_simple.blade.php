@extends('layouts.app')

@section('title', 'Chi tiết nhà xuất bản')

@section('content')
<div class="container-fluid">
    <h1>Chi tiết nhà xuất bản</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>{{ $nhaXuatBan->ten_nxb }}</h5>
            <p>Mã NXB: {{ $nhaXuatBan->ma_nxb }}</p>
            <p>Địa chỉ: {{ $nhaXuatBan->dia_chi }}</p>
            
            <div class="mt-3">
                <a href="{{ route('admin.nhaxuatban.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ url('/admin/nhaxuatban/' . $nhaXuatBan->ma_nxb . '/edit') }}" class="btn btn-warning">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection