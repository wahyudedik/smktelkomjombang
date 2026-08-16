<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class ThemeSetting extends Model
{
    protected $fillable = [
        'theme',
        'key',
        'value',
        'type',
        'group_name',
        'sort_order',
    ];

    // ─── Scopes ────────────────────────────────────────────

    public function scopeByTheme($query, string $theme)
    {
        return $query->where('theme', $theme);
    }

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group_name', $group);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('key');
    }

    // ─── Accessors ─────────────────────────────────────────

    /**
     * Cast value based on type column.
     */
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'json' => json_decode($this->value, true),
            'image' => $this->value ? asset('storage/' . $this->value) : null,
            default => $this->value,
        };
    }

    // ─── Static Methods ────────────────────────────────────

    /**
     * Get all settings for a theme as associative array.
     *
     * @param string $theme
     * @return array<string, mixed>
     */
    public static function getThemeConfig(string $theme): array
    {
        $settings = static::where('theme', $theme)
            ->ordered()
            ->get();

        $config = [];

        foreach ($settings as $setting) {
            $value = $setting->value;

            // Cast based on type
            $config[$setting->key] = match ($setting->type) {
                'json' => json_decode($value, true),
                default => $value,
            };
        }

        return $config;
    }

    /**
     * Save multiple settings for a theme at once.
     *
     * @param string $theme
     * @param array<string, mixed> $data ['key' => 'value', ...]
     * @param array<string, string> $types ['key' => 'type', ...] optional type overrides
     */
    public static function saveThemeConfig(string $theme, array $data, array $types = []): void
    {
        $groupMap = static::getDefaultGroupMap();

        foreach ($data as $key => $value) {
            $type = $types[$key] ?? 'text';

            // Auto-detect type for images
            if (in_array($key, [
                'logo',
                'logo_light',
                'favicon',
                'headmaster_photo',
                'video_thumbnail',
                'program_section_image',
                'about_image_1',
                'about_image_2',
                'about_image_3',
                'campus_life_headmaster_photo',
            ])) {
                $type = 'image';
            }

            // Auto-detect type for URLs
            if (str_ends_with($key, '_url') || str_ends_with($key, '_href')) {
                $type = 'url';
            }

            // Auto-detect type for json fields
            if (in_array($key, [
                'hero_images',
                'program_peminatan',
                'features',
                'program_unggulan',
                'menu',
                'working_hours',
                'kepala_sekolah',
                'jurusan',
            ])) {
                $type = 'json';
                if (is_array($value)) {
                    $value = json_encode($value);
                }
            }

            $group = $groupMap[$key] ?? 'general';
            $sortOrder = array_search($key, array_keys($groupMap)) !== false
                ? array_search($key, array_keys($groupMap))
                : 0;

            static::updateOrCreate(
                ['theme' => $theme, 'key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'group_name' => $group,
                    'sort_order' => $sortOrder,
                ]
            );
        }
    }

    /**
     * Seed default values from config file into database.
     *
     * @param string $theme
     */
    public static function seedDefaults(string $theme): int
    {
        $fileConfig = config("themes.{$theme}", []);

        if (empty($fileConfig)) {
            return 0;
        }

        $count = 0;

        foreach ($fileConfig as $key => $value) {
            // Skip array-only keys that should not be individual settings
            // (like 'program_peminatan', 'features', etc. — keep as JSON)
            $existing = static::where('theme', $theme)->where('key', $key)->first();

            if (!$existing) {
                $type = 'text';
                $serializedValue = $value;

                // Determine type and serialize
                if (is_array($value)) {
                    $type = 'json';
                    $serializedValue = json_encode($value);
                } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                    $type = 'url';
                } elseif (in_array($key, [
                    'logo',
                    'logo_light',
                    'favicon',
                    'headmaster_photo',
                    'video_thumbnail',
                    'program_section_image',
                    'about_image_1',
                    'about_image_2',
                    'about_image_3',
                ])) {
                    $type = 'image';
                }

                $groupMap = static::getDefaultGroupMap();

                static::create([
                    'theme' => $theme,
                    'key' => $key,
                    'value' => is_array($serializedValue) ? json_encode($serializedValue) : $serializedValue,
                    'type' => $type,
                    'group_name' => $groupMap[$key] ?? 'general',
                    'sort_order' => 0,
                ]);

                $count++;
            }
        }

        return $count;
    }

    /**
     * Get list of registered themes.
     *
     * @return array<string, array{name: string, short_name: string}>
     */
    public static function getRegisteredThemes(): array
    {
        $themes = [];
        $available = config('themes.available', []);

        foreach ($available as $key => $config) {
            $themes[$key] = [
                'name' => $config['name'] ?? $key,
                'short_name' => $config['short_name'] ?? strtoupper($key),
            ];
        }

        return $themes;
    }

    /**
     * Clear cache for a theme.
     */
    public static function clearCache(string $theme): void
    {
        cache()->forget("theme_settings_{$theme}");
    }

    /**
     * Clear all theme caches.
     */
    public static function clearAllCache(): void
    {
        foreach (array_keys(static::getRegisteredThemes()) as $theme) {
            static::clearCache($theme);
        }
    }

    // ─── Private Helpers ───────────────────────────────────

    /**
     * Map setting keys to their group names.
     */
    private static function getDefaultGroupMap(): array
    {
        return [
            // ═══ General ═══
            'name' => 'general',
            'short_name' => 'general',
            'tagline' => 'general',
            'type' => 'general',
            'address' => 'general',
            'phone' => 'general',
            'phone_secondary' => 'general',
            'whatsapp' => 'general',
            'email' => 'general',
            'ppdb_url' => 'general',

            // Logo & Assets
            'assets_path' => 'general',
            'favicon' => 'general',
            'logo' => 'general',
            'logo_light' => 'general',

            // ═══ Landing Page: Site Info ═══
            'site_name' => 'general',
            'site_description' => 'general',
            'site_keywords' => 'general',
            'footer_text' => 'general',

            // ═══ Landing Page: Gallery ═══
            'gallery_title' => 'general',
            'gallery_subtitle' => 'general',

            // ═══ Social Media ═══
            'facebook' => 'social',
            'instagram' => 'social',
            'youtube' => 'social',
            'tiktok' => 'social',
            'facebook_url' => 'social',
            'instagram_url' => 'social',
            'youtube_url' => 'social',
            'tiktok_url' => 'social',

            // ═══ Landing Page: Social (admin) ═══
            'social_facebook' => 'social',
            'social_instagram' => 'social',
            'social_youtube' => 'social',
            'social_whatsapp' => 'social',

            // ═══ Hero ═══
            'hero_images' => 'hero',

            // ═══ Landing Page: Hero Slides ═══
            'hero_title' => 'hero',
            'hero_subtitle' => 'hero',
            'hero_slide1_subtitle' => 'hero',
            'hero_slide1_title' => 'hero',
            'hero_slide1_description' => 'hero',
            'hero_slide2_subtitle' => 'hero',
            'hero_slide2_title' => 'hero',
            'hero_slide2_description' => 'hero',
            'hero_slide3_subtitle' => 'hero',
            'hero_slide3_title' => 'hero',
            'hero_slide3_description' => 'hero',

            // ═══ Features ═══
            'features' => 'features',

            // ═══ Landing Page: Feature Cards ═══
            'feature1_title' => 'features',
            'feature1_description' => 'features',
            'feature2_title' => 'features',
            'feature2_description' => 'features',
            'feature3_title' => 'features',
            'feature3_description' => 'features',

            // ═══ Programs ═══
            'program_peminatan' => 'programs',
            'program_unggulan' => 'programs',
            'jurusan' => 'programs',

            // ═══ Landing Page: Programs ═══
            'program_section_title' => 'programs',
            'program_section_subtitle' => 'programs',
            'program_ipa_title' => 'programs',
            'program_ipa_description' => 'programs',
            'program_ips_title' => 'programs',
            'program_ips_description' => 'programs',
            'program_religion_title' => 'programs',
            'program_religion_description' => 'programs',
            'program_section_image' => 'programs',

            // ═══ About / Kepala Sekolah ═══
            'kepala_sekolah' => 'about',

            // ═══ Landing Page: About Section ═══
            'about_section_title' => 'about',
            'about_section_subtitle' => 'about',
            'about_section_description' => 'about',
            'about_image_1' => 'about',
            'about_image_2' => 'about',
            'about_image_3' => 'about',
            'about_feature_1_title' => 'about',
            'about_feature_1_description' => 'about',
            'about_feature_2_title' => 'about',
            'about_feature_2_description' => 'about',
            'about_feature_3_title' => 'about',
            'about_feature_3_description' => 'about',
            'about_feature_4_title' => 'about',
            'about_feature_4_description' => 'about',
            'about_button_text' => 'about',
            'about_contact_text' => 'about',
            'about_contact_phone' => 'about',

            // ═══ Landing Page: Headmaster ═══
            'headmaster_name' => 'about',
            'headmaster_description' => 'about',
            'headmaster_vision' => 'about',
            'headmaster_photo' => 'about',

            // ═══ Landing Page: Campus Life Headmaster ═══
            'campus_life_headmaster_name' => 'about',
            'campus_life_headmaster_description' => 'about',
            'campus_life_headmaster_vision' => 'about',
            'campus_life_headmaster_photo' => 'about',

            // ═══ Video ═══
            'video_url' => 'video',
            'video_thumbnail' => 'video',

            // ═══ Counter ═══
            'counter1_number' => 'counter',
            'counter1_label' => 'counter',
            'counter2_number' => 'counter',
            'counter2_label' => 'counter',
            'counter3_number' => 'counter',
            'counter3_label' => 'counter',

            // ═══ CTA ═══
            'cta_title' => 'cta',
            'cta_description' => 'cta',
            'cta_button_url' => 'cta',
            'cta_button_text' => 'cta',
            'cta_video_title' => 'cta',

            // ═══ Contact ═══
            'working_hours' => 'contact',
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'contact_address' => 'contact',
            'contact_section_subtitle' => 'contact',
            'contact_section_title' => 'contact',
            'contact_section_description' => 'contact',
            'contact_map_url' => 'contact',
            'contact_operational_hours' => 'contact',

            // ═══ Menu ═══
            'menu' => 'menu',
        ];
    }
}
