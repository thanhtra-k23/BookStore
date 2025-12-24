@section('alert')
<style>
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
                rgba(255, 255, 255, 0.30));
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
<div id="toast-container">
    @if (Session::get('tb_success'))
    <div class="app-toast app-toast--success app-toast--visible">
        <i class="fas fa-check-circle app-toast__icon app-toast--success"></i>
        <div>{{ Session::get('tb_success') }}</div>
        <button type="button" class="app-toast__close">&times;</button>
    </div>
    @endif

    @if (Session::get('tb_danger'))
    <div class="app-toast app-toast--danger app-toast--visible">
        <i class="fas fa-times-circle app-toast__icon app-toast--danger"></i>
        <div>{{ Session::get('tb_danger') }}</div>
        <button type="button" class="app-toast__close">&times;</button>
    </div>
    @endif

    @if ($errors->any())
    <div class="app-toast app-toast--warning">
        <i class="fas fa-exclamation-triangle app-toast__icon"></i>
        <div>
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="app-toast__close">&times;</button>
    </div>
    @endif
</div>

<script>
    (function() {
            const toasts = document.querySelectorAll('.app-toast');

            toasts.forEach(toast => {
                setTimeout(() => toast.classList.add('app-toast--visible'), 80);

                const timer = setTimeout(() => {
                    toast.classList.remove('app-toast--visible');
                    setTimeout(() => toast.remove(), 300);
                }, 3500);

                const close = toast.querySelector('.app-toast__close');
                close.addEventListener('click', () => {
                    clearTimeout(timer);
                    toast.classList.remove('app-toast--visible');
                    setTimeout(() => toast.remove(), 200);
                });
            });
        })();
</script>
@endsection