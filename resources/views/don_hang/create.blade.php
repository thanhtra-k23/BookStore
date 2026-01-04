@extends('layouts.admin')

@section('title', $title ?? 'Tạo đơn hàng mới')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="margin: 0; font-weight: 600;">{{ $title ?? 'Tạo đơn hàng mới' }}</h3>
            <div>
                <a href="{{ route('admin.donhang.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chức năng tạo đơn hàng thủ công đang được phát triển. 
                        Hiện tại đơn hàng được tạo tự động khi khách hàng đặt hàng trên website.
                    </div>
                    
                    <div class="text-center">
                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Đang phát triển</h4>
                        <p class="text-muted">Tính năng này sẽ sớm được hoàn thiện</p>
                        <a href="{{ route('admin.donhang.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i>Xem danh sách đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection