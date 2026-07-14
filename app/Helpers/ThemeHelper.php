<?php

/**
 * Theme Helper Functions
 *
 * Helper untuk mengakses konfigurasi tema secara global.
 *
 * Priority layers:
 *   - theme_config():  Database (theme_settings) > Config File (config/themes/*.php) > Default
 *   - theme_image():   Per-theme DB > Global site setting > Theme registry default > Hardcoded
 *   - theme_info():    Theme registry (config/themes.php) > Default
 *
 * @see plans/theme-system-refactoring.md
 */

use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Storage;

// ─── Theme Config (DB + File) ───────────────────────────────

if (!function_exists('theme_config')) {
    /**
     * Get theme configuration value.
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
        $theme = current_theme();

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
        return data_get($merged, $key, $default);
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

// ─── Current Theme ──────────────────────────────────────────

if (!function_exists('current_theme')) {
    /**
     * Get current theme name.
     * Priority: Route override → Config override → DEFAULT_THEME env → fallback 'telkom'
     *
     * @return string
     */
    function current_theme(): string
    {
        // 1. Check route override (set by /theme/{theme} route)
        $override = config('app.theme_override');
        if ($override && in_array($override, available_themes())) {
            return $override;
        }

        return config('app.default_theme', 'telkom');
    }
}

// ─── Theme Checks ───────────────────────────────────────────

if (!function_exists('is_theme')) {
    /**
     * Generic theme check — replaces is_telkom(), is_maudu(), etc.
     *
     * @param string $theme Theme name to check against
     * @return bool
     */
    function is_theme(string $theme): bool
    {
        return current_theme() === $theme;
    }
}

if (!function_exists('is_telkom')) {
    /**
     * Check if current theme is Telkom.
     *
     * @deprecated Use is_theme('telkom') instead
     * @return bool
     */
    function is_telkom(): bool
    {
        return current_theme() === 'telkom';
    }
}

if (!function_exists('is_maudu')) {
    /**
     * Check if current theme is MAUDU.
     *
     * @deprecated Use is_theme('maudu') instead
     * @return bool
     */
    function is_maudu(): bool
    {
        return current_theme() === 'maudu';
    }
}

// ─── Theme Registry (config/themes.php) ─────────────────────

if (!function_exists('theme_info')) {
    /**
     * Get current theme info from registry (config/themes.php).
     *
     * @param string|null $key Dot-notation key (e.g., 'name', 'colors.primary', 'defaults.favicon')
     * @param mixed $default Default value if key is not found
     * @return mixed
     */
    function theme_info(?string $key = null, mixed $default = null): mixed
    {
        $themes = config('themes.available', []);
        $current = current_theme();

        if (!isset($themes[$current])) {
            return $key === null ? [] : $default;
        }

        if ($key === null) {
            return $themes[$current];
        }

        return data_get($themes[$current], $key, $default);
    }
}

if (!function_exists('available_themes')) {
    /**
     * Get all available theme names from registry.
     *
     * @return array
     */
    function available_themes(): array
    {
        return array_keys(config('themes.available', []));
    }
}

// ─── Theme Assets ───────────────────────────────────────────

if (!function_exists('theme_asset')) {
    /**
     * Generate asset URL for current theme.
     * Auto-resolves from theme_info registry with fallback to theme_config.
     *
     * @param string $path Asset path relative to theme directory
     * @return string
     */
    function theme_asset(string $path): string
    {
        $assetsPath = theme_info('assets_path') ?? theme_config('assets_path', 'assets_telkom');
        return asset("{$assetsPath}/{$path}");
    }
}

// ─── Theme Images (Favicon, Logo, etc.) ─────────────────────

if (!function_exists('theme_image')) {
    /**
     * Resolve theme image (favicon, logo, etc.) with 4-tier fallback:
     *
     *   1. Per-theme config (theme_config: DB theme_settings > config/themes/{theme}.php)
     *   2. Global site setting (site_settings table — admin editable via SettingsController)
     *   3. Theme registry default (config/themes.php → defaults key)
     *   4. Hardcoded fallback path
     *
     * Resolution logic for tiers 1-2:
     *   - If value is a public asset path (exists in public/) → use asset()
     *   - If value is a storage path (admin upload) → use Storage::url()
     *
     * @param string $key         Image key (e.g., 'favicon', 'logo', 'logo_light')
     * @param string $defaultPath Fallback asset path if nothing found
     * @return string             Full URL to the image
     */
    function theme_image(string $key, string $defaultPath = ''): string
    {
        // Helper: resolve path as public asset or storage URL
        $resolvePath = function (string $value): string {
            // If file exists in public directory → it's a public asset path
            if (file_exists(public_path($value))) {
                return asset($value);
            }
            // Otherwise → treat as storage path (admin upload via ThemeSettingController/SettingsController)
            try {
                return Storage::url($value);
            } catch (\Exception $e) {
                return asset($value);
            }
        };

        // 1. Per-theme config (DB theme_settings > config file fallback)
        $themeValue = theme_config($key);
        if ($themeValue && is_string($themeValue) && !str_starts_with($themeValue, '#') && $themeValue !== '') {
            return $resolvePath($themeValue);
        }

        // 2. Global site setting (SettingsController uploads)
        $globalValue = cache("site_setting_{$key}");
        if ($globalValue && is_string($globalValue) && !str_starts_with($globalValue, '#') && $globalValue !== '') {
            return $resolvePath($globalValue);
        }

        // 3. Theme registry default (config/themes.php)
        $registryDefault = theme_info("defaults.{$key}");
        if ($registryDefault) {
            return asset($registryDefault);
        }

        // 4. Hardcoded fallback
        return $defaultPath ? asset($defaultPath) : '';
    }
}

// ─── Theme View Resolution ──────────────────────────────────

if (!function_exists('theme_view')) {
    /**
     * Return the correct view name based on the active theme.
     *
     * Strategy (Convention over Configuration):
     *   1. Check $overrides[theme_name] (explicit override)
     *   2. Check if {base}-{theme}.blade.php exists (convention)
     *   3. Fallback to {base} view (default)
     *
     * Examples:
     *   theme_view('berita.public.index')
     *     → tema 'maudu': cek 'berita/public/index-maudu.blade.php'
     *     → jika tidak ada: fallback 'berita/public/index.blade.php'
     *
     *   theme_view('berita.public.index', ['maudu' => 'berita.maudu.index'])
     *     → tema 'maudu': pakai 'berita.maudu.index' (explicit override)
     *
     * @param string $base      Base view name (e.g., 'berita.public.index')
     * @param array  $overrides Explicit overrides per theme
     * @return string Resolved view name
     */
    function theme_view(string $base, array $overrides = []): string
    {
        $theme = current_theme();

        // 1. Explicit override
        if (isset($overrides[$theme])) {
            return $overrides[$theme];
        }

        // 2. Convention: {base}-{theme}.blade.php
        $themed = "{$base}-{$theme}";
        $path = resource_path('views/' . str_replace('.', '/', $themed) . '.blade.php');
        if (file_exists($path)) {
            return $themed;
        }

        // 3. Fallback to base view
        return $base;
    }
}

// ─── URL Resolution ─────────────────────────────────────────

if (!function_exists('resolve_theme_url')) {
    /**
     * Resolve URL from config — supports 'route:name' syntax.
     *
     * Used in theme config menus where route() helper is not available.
     * Example: 'url' => 'route:berita.public.index' → route('berita.public.index')
     *          'url' => '/some/path' → '/some/path'
     *
     * @param string $url URL or route reference
     * @return string Resolved URL
     */
    function resolve_theme_url(string $url): string
    {
        if (str_starts_with($url, 'route:')) {
            $routeParts = substr($url, 6);
            $parts = explode(',', $routeParts, 2);
            $routeName = trim($parts[0]);
            $params = isset($parts[1]) ? array_map('trim', explode(',', $parts[1])) : [];

            try {
                return route($routeName, $params);
            } catch (\Exception $e) {
                return '#';
            }
        }

        return $url;
    }
}
