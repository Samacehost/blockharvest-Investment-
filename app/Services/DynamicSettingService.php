<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\BrandingSetting;
use Illuminate\Support\Facades\Cache;

class DynamicSettingService
{
    const CACHE_KEY = 'app_dynamic_settings';
    const BRANDING_CACHE_KEY = 'app_branding_settings';

    /**
     * Get a setting by key with fallback value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value and clear cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'group_name' => $group, 'type' => $type]
        );

        self::clearCache();
    }

    /**
     * Get Branding Settings (Primary color, logo, radius, etc.)
     */
    public static function branding(): BrandingSetting
    {
        return Cache::rememberForever(self::BRANDING_CACHE_KEY, function () {
            return BrandingSetting::first() ?? BrandingSetting::create([
                'primary_color' => '#4F46E5',
                'secondary_color' => '#0EA5E9',
                'accent_color' => '#F59E0B',
                'button_radius' => '0.5rem',
                'font_family' => 'Inter',
            ]);
        });
    }

    /**
     * Generate dynamic CSS variables string for HTML head injection
     */
    public static function generateCssVariables(): string
    {
        $b = self::branding();
        return "
            :root {
                --primary-color: {$b->primary_color};
                --secondary-color: {$b->secondary_color};
                --accent-color: {$b->accent_color};
                --button-radius: {$b->button_radius};
                --font-family: '{$b->font_family}', sans-serif;
            }
        ";
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::BRANDING_CACHE_KEY);
    }
}
