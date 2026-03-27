<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Replaces Laravel Breeze's default AuthenticatedSessionController.
 *
 * Key change vs Breeze default:
 *   - Login with 'username' field (not 'email')
 *   - Role-based redirect after login mirrors Java SecurityConfig successHandler:
 *       ROLE_ADMIN  → /admin/dashboard
 *       ROLE_STAFF  → /delivery/dashboard
 *       ROLE_USER   → /
 */
class AuthenticatedSessionController extends Controller
{
    // ── GET /login ────────────────────────────────────────────────────────────
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');  // resources/views/auth/login.blade.php
    }

    // ── POST /login ───────────────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Mirrors Java: .usernameParameter("username").passwordParameter("password")
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Mirrors Java: .failureUrl("/auth/login?error=true")
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Tên đăng nhập hoặc mật khẩu không đúng.']);
        }

        // Check if account is active — mirrors Java: .disabled(!user.isEnabled())
        if (!Auth::user()->active) {
            Auth::logout();
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Tài khoản của bạn đã bị vô hiệu hóa.']);
        }

        $request->session()->regenerate();

        // Mirrors Java successHandler role-based redirect
        return $this->redirectByRole(Auth::user());
    }

    // ── POST /logout ──────────────────────────────────────────────────────────
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mirrors Java: .logoutSuccessUrl("/auth/login?logout=true")
        return redirect()->route('login')->with('logoutSuccess', true);
    }

    // ── Helper: role-based redirect ───────────────────────────────────────────
    /**
     * Java successHandler logic:
     *   ROLE_ADMIN  → /admin/dashboard
     *   ROLE_STAFF  → /delivery/dashboard
     *   ROLE_USER   → /
     */
    private function redirectByRole($user): RedirectResponse
    {
        return match ($user->role) {
            Role::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            Role::ROLE_STAFF => redirect()->route('delivery.dashboard'),
            default          => redirect()->route('home'),
        };
    }
}
