<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\SiteConfigService;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // ── Register ──────────────────────────────────────────────────────────────
    public function register(): void
    {
        // Bind services as singletons — mirrors Spring @Service singleton scope
        $this->app->singleton(ProductService::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(UserService::class);
        $this->app->singleton(SiteConfigService::class);
    }

    // ── Boot ──────────────────────────────────────────────────────────────────
    public function boot(): void
    {
        $this->defineGates();
        $this->shareGlobalViewData();
    }

    // ── Gates (mirrors Java @PreAuthorize + SecurityConfig) ───────────────────
    /**
     * Java @PreAuthorize("hasRole('ADMIN')") → Gate::define('admin', ...)
     * Java @PreAuthorize("hasAnyRole('STAFF','ADMIN')") → Gate::define('staff-or-admin', ...)
     *
     * Usage in Blade:   @can('admin') ... @endcan
     * Usage in routes:  ->middleware('can:admin')
     * Usage in controllers: $this->authorize('admin')
     */
    private function defineGates(): void
    {
        Gate::define('admin', fn(User $user) => $user->role === Role::ROLE_ADMIN);

        Gate::define('staff', fn(User $user) => $user->role === Role::ROLE_STAFF);

        Gate::define('staff-or-admin', fn(User $user) => in_array($user->role, [
            Role::ROLE_STAFF,
            Role::ROLE_ADMIN,
        ]));
    }

    // ── Global View Composers ─────────────────────────────────────────────────
    /**
     * Share site config with ALL views so layout.blade.php can always access
     * $siteConfig['site_name'], $siteConfig['navbar_color'], etc.
     * Mirrors Java model.addAttribute("siteConfig", buildSiteConfigMap()).
     */
    private function shareGlobalViewData(): void
    {
        View::composer('*', function ($view) {
            // Lazy-loaded once per request via app singleton
            $siteConfigService = app(SiteConfigService::class);
            $view->with('siteConfig', $siteConfigService->asMap());
        });
    }
}
