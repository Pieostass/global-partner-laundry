<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    // ── String primary key (mirrors Java @Id String configKey) ────────────────
    protected $primaryKey = 'config_key';
    public    $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'config_key',
        'config_value',
        'description',
    ];

    // ── Static helper: get a single config value with a fallback ──────────────

    /**
     * Mirrors Java SiteConfigService::get(key, defaultValue)
     * Usage: SiteConfig::getValue('site_name', 'LaundryShop')
     */
    public static function getValue(string $key, string $default = ''): string
    {
        return static::find($key)?->config_value ?? $default;
    }

    /**
     * Returns all configs as a flat key→value array.
     * Mirrors Java buildSiteConfigMap() in HomeController.
     */
    public static function asMap(): array
    {
        return static::all()->pluck('config_value', 'config_key')->toArray();
    }
}
