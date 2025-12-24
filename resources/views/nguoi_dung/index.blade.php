@extends('layout.header')
@extends('layout.menu')
@extends('layout.alert')
@section('body')
@php
$stt = 1;
use Carbon\Carbon;
@endphp
<div class="container app-wrapper mt-4 mb-5">
    <div class="card card-modern">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1 fw-bold">Quản lý sách</h4>
                <p class="mb-0 text-muted">
                    Theo dõi và chỉnh sửa danh mục tác giả trong cửa hàng.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="fa fa-magnifying-glass"></i></span>
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="Tìm theo tên sách hoặc tác giả" />
                </div>

                <button id="btnAddBook" class="btn btn-primary btn-sm ms-md-2" data-bs-toggle="modal"
                    data-bs-target="#bookModal">
                    <i class="fa fa-plus me-1"></i> Thêm người dùng
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Vai trò</th>
                            <th>Xác thực</th>
                            <th style="width: 110px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="bookTable">
                        @foreach ($nguoiDung as $item)
                        <tr>
                            <td>{{$stt++}}</td>
                            <td>{{$item->ho_ten}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->so_dien_thoai}}</td>
                            <td>{{$item->dia_chi}}</td>
                            <td>
                                @if ($item->vai_tro == 'quan_tri')
                                <span class="badge bg-warning">Quản trị</span>
                                @else
                                <span class="badge bg-primary">Khách hàng</span>
                                @endif
                            </td>
                            <td>
                                @if (empty($item->xac_minh_email_luc) )
                                <span class="badge bg-danger">Chưa xác thực</span>
                                @else
                                <span class="">{{ Carbon::parse($item->xac_minh_email_luc)->format('d/m/Y, h:i:s A') }}</span>
                                @endif
                            </td>
                            <td class="action-btn">
                                <i class="fa fa-pen text-primary btn-edit" title="Sửa"></i>
                                <i class="fa fa-trash text-danger btn-delete" title="Xóa"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Phân modal thêm --}}
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalTitle">Thêm người dùng</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="">
                <form method="POST" enctype="multipart/form-data" action="">@csrf
                    <div class="modal-body">
                        <form id="bookForm">
                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="bookName" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" id="bookAuthor" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="bookName" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="bookAuthor" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vai trò</label>
                                <input type="text" class="form-control" id="bookName" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác thực</label>
                                <input type="text" class="form-control" id="bookAuthor" required />
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light border" data-bs-dismiss="modal">
                            Hủy
                        </button>
                        <button id="saveBookBtn" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Thêm người dùng
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>
<script>
    document.getElementById("btnAddBook").addEventListener("click", () => {
        bookModalTitle.textContent = "Thêm người dùng mới";
        bookForm.reset();
        editingRowIndex.value = "";
      });
</script>
@endsection
@extends('layout.footer')