<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login', [
            'title' => 'Đăng nhập'
        ]);
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'remember'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is admin
            $user = Auth::user();
            if ($user->vai_tro === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('tb_success', 'Chào mừng quản trị viên ' . $user->ho_ten);
            }

            return redirect()->intended(route('home'))
                ->with('tb_success', 'Đăng nhập thành công! Chào mừng ' . $user->ho_ten);
        }

        return back()
            ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác'])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.register', [
            'title' => 'Đăng ký tài khoản'
        ]);
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create user
        $user = User::create([
            'ho_ten' => $request->name,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->password),
            'vai_tro' => 'customer',
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('home')
            ->with('tb_success', 'Đăng ký thành công! Chào mừng bạn đến với BookStore');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('tb_success', 'Đăng xuất thành công!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Quên mật khẩu'
        ]);
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // TODO: Send password reset email
        // For now, just show success message
        
        return back()
            ->with('tb_success', 'Đã gửi email hướng dẫn đặt lại mật khẩu. Vui lòng kiểm tra hộp thư của bạn.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', [
            'title' => 'Đặt lại mật khẩu',
            'token' => $token
        ]);
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // TODO: Verify token and reset password
        // For now, just update password directly
        
        $user = User::where('email', $request->email)->first();
        $user->mat_khau = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')
            ->with('tb_success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }

    /**
     * Show verify email notice
     */
    public function showVerifyEmailNotice()
    {
        return view('auth.verify-email', [
            'title' => 'Xác thực email'
        ]);
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('home')
                ->with('tb_danger', 'Link xác thực không hợp lệ');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')
                ->with('tb_info', 'Email đã được xác thực trước đó');
        }

        $user->markEmailAsVerified();

        return redirect()->route('home')
            ->with('tb_success', 'Xác thực email thành công!');
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()
            ->with('tb_success', 'Đã gửi lại email xác thực!');
    }
}
