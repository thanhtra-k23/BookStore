<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-book-open me-2"></i>
                    BookStore
                </h5>
                <p class="text-muted">
                    Nhà sách trực tuyến hàng đầu Việt Nam, cung cấp hàng ngàn đầu sách chất lượng với giá cả hợp lý.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="mb-3">Danh mục</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('categories') }}" class="text-muted text-decoration-none">Tất cả thể loại</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Văn học</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Kinh tế</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Khoa học</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Thiếu nhi</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="mb-3">Hỗ trợ</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Liên hệ</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <h6 class="mb-3">Liên hệ</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2"></i>
                        0787905089
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        info@bookstore.vn
                    </li>
                </ul>

                <div class="mt-3">
                    <h6 class="mb-2">Đăng ký nhận tin</h6>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Email của bạn">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    &copy; {{ date('Y') }} BookStore. Tất cả quyền được bảo lưu.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end gap-3">
                    <a href="#" class="text-muted text-decoration-none">Điều khoản sử dụng</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách bảo mật</a>
                </div>
            </div>
        </div>
    </div>
</footer>