<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Quản lý sách - BookStore</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        body {
            background: linear-gradient(135deg,
                    #f5f7fb 0%,
                    #e3ecff 45%,
                    #fdfbff 100%);
            min-height: 100vh;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
        }

        .app-wrapper {
            max-width: 1200px;
        }

        .card-modern {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .card-modern .card-header {
            border-bottom: 1px solid #edf2ff;
            background: white;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-modern .card-body {
            background: white;
            border-radius: 0 0 16px 16px !important;
        }

        .table thead {
            background: #1f2937;
            color: #fff;
        }

        .action-btn i {
            cursor: pointer;
            font-size: 16px;
            margin-right: 8px;
            transition: transform 0.15s ease;
        }

        .action-btn i:hover {
            transform: translateY(-1px) scale(1.05);
        }

        /* Toast style giống mẫu bạn gửi, nhưng thuần HTML/JS */
        .app-toast {
            position: fixed;
            right: 24px;
            bottom: 24px;
            min-width: 260px;
            max-width: 360px;
            padding: 12px 14px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;

            /* NỀN HIỆN ĐẠI DẠNG GLASS + GRADIENT */
            background: linear-gradient(135deg,
                    rgba(0, 39, 77, 0.18),
                    rgba(238, 238, 34, 0.22),
                    rgba(255, 255, 255, 0.3));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);

            color: #0f172a;
            /* chữ đậm hơn trên nền sáng */

            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.25s ease;
            z-index: 9999;
        }

        .app-toast--visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Thay vì đổi background, chỉ tô viền trái + màu icon theo trạng thái */
        .app-toast--success {
            border-left: 4px solid #16a34a;
        }

        .app-toast--danger {
            border-left: 4px solid #dc2626;
        }

        .app-toast--info {
            border-left: 4px solid #2563eb;
        }

        .app-toast__icon {
            font-size: 18px;
        }

        /* Icon theo màu trạng thái */
        .app-toast--success .app-toast__icon {
            color: #16a34a;
        }

        .app-toast--danger .app-toast__icon {
            color: #dc2626;
        }

        .app-toast--info .app-toast__icon {
            color: #2563eb;
        }

        .app-toast__close {
            border: none;
            background: transparent;
            color: inherit;
            font-size: 18px;
            margin-left: auto;
            line-height: 1;
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .app-toast {
                left: 12px;
                right: 12px;
            }
        }
    </style>
</head>

<body>
    @yield('menu')
    @yield('alert')
    @yield('body')
    @yield('footer')
</body>

</html>