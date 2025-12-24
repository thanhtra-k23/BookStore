@extends('layouts.app')

@section('title', $title ?? 'Thêm người dùng mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title ?? 'Thêm người dùng mới' }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.nguoidung.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chức năng thêm người dùng thủ công đang được phát triển. 
                        Hiện tại người dùng có thể đăng ký tài khoản trực tiếp trên website.
                    </div>
                    
                    <div class="text-center">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Đang phát triển</h4>
                        <p class="text-muted">Tính năng này sẽ sớm được hoàn thiện</p>
                        <a href="{{ route('admin.nguoidung.index') }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i>Xem danh sách người dùng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection