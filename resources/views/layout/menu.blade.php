@section('menu')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid app-wrapper">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="fa-solid fa-book-open-reader me-2"></i> BookStore Manager
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-home"></i> Trang chủ</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.nhaxuatban.index') }}"><i class="fas fa-store-alt"></i> Nhà xuất bản</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.donhang.index') }}"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.nguoidung.index') }}"><i class="fas fa-users"></i> Người dung</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.sach.index') }}"><i class="fas fa-book"></i> Sách</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.tacgia.index') }}"><i class="fas fa-user-edit"></i> Tác giả</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.theloai.index') }}"><i class="fas fa-list"></i> Thể loại</a></li>
            </ul>
        </div>
    </div>
</nav>
@endsection