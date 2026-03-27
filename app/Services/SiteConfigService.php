<?php

namespace App\Services;

use App\Models\SiteConfig;
use Illuminate\Database\Eloquent\Collection;

/**
 * Mirrors Java SiteConfigService.
 * Wraps SiteConfig model operations — business logic stays in the service,
 * keeping controllers thin.
 */
class SiteConfigService
{
    // ── get ───────────────────────────────────────────────────────────────────
    /** Java: String get(String key, String defaultValue) */
    public function get(string $key, string $default = ''): string
    {
        return SiteConfig::getValue($key, $default);
    }

    // ── findAll ───────────────────────────────────────────────────────────────
    /** Java: List<SiteConfig> findAll() */
    public function findAll(): Collection
    {
        return SiteConfig::orderBy('config_key')->get();
    }

    // ── saveAll ───────────────────────────────────────────────────────────────
    /**
     * Java: void saveAll(Map<String,String> configs)
     * $configs = ['key' => 'value', ...]
     * Called from AdminController POST /admin/settings
     */
    public function saveAll(array $configs): void
    {
        foreach ($configs as $key => $value) {
            SiteConfig::updateOrCreate(
                ['config_key' => $key],
                ['config_value' => $value]
            );
        }
    }

    // ── asMap ─────────────────────────────────────────────────────────────────
    /**
     * Returns all configs as a flat key→value array.
     * Mirrors Java buildSiteConfigMap() in HomeController.
     * Usage in Blade: $siteConfig['site_name']
     */
    public function asMap(): array
    {
        $map = SiteConfig::asMap();

        // Java: map.putIfAbsent("siteName", ...) — short aliases for templates
        $map['siteName'] = $map['siteName'] ?? ($map['site_name'] ?? 'LaundryShop');
        $map['address']  = $map['address']  ?? ($map['footer_address'] ?? '123 Đường Láng, Hà Nội');
        $map['phone']    = $map['phone']    ?? ($map['footer_phone']   ?? '0901 234 567');
        $map['email']    = $map['email']    ?? ($map['footer_email']   ?? 'hello@laundryshop.vn');

        return $map;
    }
}
