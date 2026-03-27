<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role-based access control middleware.
 * Mirrors Java SecurityConfig:
 *   .requestMatchers("/admin/**").hasRole("ADMIN")
 *   .requestMatchers("/staff/**","/delivery/**").hasAnyRole("STAFF","ADMIN")
 *
 * Usage in routes/web.php:
 *   Route::middleware(['auth', 'role:ROLE_ADMIN'])->group(...)
 *   Route::middleware(['auth', 'role:ROLE_STAFF,ROLE_ADMIN'])->group(...)
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user's role matches any of the allowed roles
        $allowed = collect($roles)->map(fn($r) => Role::from($r));

        if (!$allowed->contains($user->role)) {
            // Mirrors Java: AccessDeniedException → common/403
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Mirrors Java: .disabled(!user.isEnabled())
        if (!$user->active) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Tài khoản của bạn đã bị vô hiệu hóa.']);
        }

        return $next($request);
    }
}
