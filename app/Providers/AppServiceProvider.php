<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Rate limit cho API - 60 requests/phút
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limit cho đăng nhập - 5 lần/phút (chống brute force)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau 1 phút.'
                    ]);
                });
        });

        // Rate limit cho đăng ký - 3 lần/phút
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Bạn đã thử đăng ký quá nhiều lần. Vui lòng thử lại sau 1 phút.'
                    ]);
                });
        });

        // Rate limit cho quên mật khẩu - 3 lần/phút
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Bạn đã gửi yêu cầu quá nhiều lần. Vui lòng thử lại sau 1 phút.'
                    ]);
                });
        });

        // Rate limit cho thêm vào giỏ hàng - 30 lần/phút
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limit cho checkout - 5 lần/phút
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'error' => 'Bạn đã thực hiện thanh toán quá nhiều lần. Vui lòng thử lại sau.'
                    ]);
                });
        });

        // Rate limit cho tìm kiếm - 30 lần/phút
        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Rate limit cho admin actions - 100 lần/phút
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });
    }
}
