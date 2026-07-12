<?php

/**
 * Theme Helper Functions
 *
 * Helper untuk mengakses konfigurasi tema secara global.
 * Priority: Database (theme_settings) > Config File (config/themes/*.php) > Default
 */

use App\Models\ThemeSetting;

if (!function_exists('theme_config')) {
    /**
     * Get theme configuration value
     *
     * Priority:
     *   1. Database (theme_settings table) — editable from admin dashboard
     *   2. Config file (config/themes/{theme}.php) — hardcoded defaults
     *   3. $default parameter
     *
     * @param string|null $key Dot-notation key to retrieve (e.g., 'name', 'phone')
     * @param mixed $default Default value if key is not found
     * @return mixed
     */
    function theme_config($key = null, $default = null)
    {
        $theme = config('app.default_theme', 'telkom');

        // 1. Try database (with cache)
        $dbConfig = cache()->remember("theme_settings_{$theme}", 3600, function () use ($theme) {
            try {
                return ThemeSetting::getThemeConfig($theme);
            } catch (\Exception $e) {
                return [];
            }
        });

        // 2. Merge with config file (config file = fallback defaults)
        $fileConfig = config("themes.{$theme}", []);

        // Database values override config file values
        $merged = array_merge($fileConfig, $dbConfig);

        if ($key === null) {
            return $merged;
        }

        // Support dot-notation
        $keys = explode('.', $key);
        $value = $merged;

        foreach ($keys as $k) {
            if (is_array($value) && array_key_exists($k, $value)) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value ?? $default;
    }
}

if (!function_exists('theme_config_db_only')) {
    /**
     * Get theme config from database only (no file fallback).
     * Useful for admin settings form.
     *
     * @param string $theme
     * @return array
     */
    function theme_config_db_only(string $theme): array
    {
        $dbConfig = cache()->remember("theme_settings_{$theme}", 3600, function () use ($theme) {
            return ThemeSetting::getThemeConfig($theme);
        });

        return $dbConfig;
    }
}

if (!function_exists('theme_config_set')) {
    /**
     * Set theme config value in database and clear cache.
     *
     * @param string $theme
     * @param string $key
     * @param mixed $value
     * @return void
     */
    function theme_config_set(string $theme, string $key, $value): void
    {
        ThemeSetting::updateOrCreate(
            ['theme' => $theme, 'key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        ThemeSetting::clearCache($theme);
    }
}

if (!function_exists('current_theme')) {
    /**
     * Get current theme name
     *
     * @return string
     */
    function current_theme()
    {
        return config('app.default_theme', 'telkom');
    }
}

if (!function_exists('is_telkom')) {
    /**
     * Check if current theme is Telkom
     *
     * @return bool
     */
    function is_telkom()
    {
        return current_theme() === 'telkom';
    }
}

if (!function_exists('is_maudu')) {
    /**
     * Check if current theme is MAUDU
     *
     * @return bool
     */
    function is_maudu()
    {
        return current_theme() === 'maudu';
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Generate asset URL for current theme
     *
     * @param string $path Asset path relative to theme directory
     * @return string
     */
    function theme_asset($path)
    {
        $assetsPath = theme_config('assets_path', 'assets_telkom');
        return asset("{$assetsPath}/{$path}");
    }
}
