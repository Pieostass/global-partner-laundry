<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Mirrors Java AuthController /auth/register GET + POST
 */
class RegisteredUserController extends Controller
{
    public function __construct(private UserService $userService) {}

    // ── GET /register ─────────────────────────────────────────────────────────
    public function create(): View
    {
        return view('auth.register');
    }

    // ── POST /register ────────────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        // Mirrors Java RegisterDto validation annotations
        $validated = $request->validate([
            'username'  => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'     => ['required', 'email', 'max:150'],
            'password'  => ['required', 'min:8', 'confirmed',
                            Password::min(8)->mixedCase()->numbers()],
            'full_name' => ['required', 'string', 'max:150'],
            'phone'     => ['nullable', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
        ], [
            'username.regex'  => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'password.min'    => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'phone.regex'     => 'Số điện thoại không hợp lệ.',
        ]);

        try {
            $this->userService->register($validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        // Mirrors Java: redirect:/auth/login?registered=true
        return redirect()->route('login')
            ->with('registered', true)
            ->with('success', 'Tạo tài khoản thành công! Hãy đăng nhập.');
    }
}
