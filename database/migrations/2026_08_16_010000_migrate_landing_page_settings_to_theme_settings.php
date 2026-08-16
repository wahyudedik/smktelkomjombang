<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Daftar semua site_setting_* keys yang mungkin ada di cache global.
     * Value akan dimigrate ke theme_settings table per tema.
     */
    private array $landingPageKeys = [
        // General / Site Info
        'site_name', 'site_description', 'site_keywords', 'footer_text',

        // Logo & Assets
        'logo', 'favicon',

        // Hero
        'hero_title', 'hero_subtitle', 'hero_images',
        'hero_slide1_subtitle', 'hero_slide1_title', 'hero_slide1_description',
        'hero_slide2_subtitle', 'hero_slide2_title', 'hero_slide2_description',
        'hero_slide3_subtitle', 'hero_slide3_title', 'hero_slide3_description',

        // Features
        'feature1_title', 'feature1_description',
        'feature2_title', 'feature2_description',
        'feature3_title', 'feature3_description',

        // About Section
        'about_section_title', 'about_section_subtitle', 'about_section_description',
        'about_image_1', 'about_image_2', 'about_image_3',
        'about_feature_1_title', 'about_feature_1_description',
        'about_feature_2_title', 'about_feature_2_description',
        'about_feature_3_title', 'about_feature_3_description',
        'about_feature_4_title', 'about_feature_4_description',
        'about_button_text', 'about_contact_text', 'about_contact_phone',

        // Headmaster
        'headmaster_name', 'headmaster_description', 'headmaster_vision', 'headmaster_photo',

        // Campus Life Headmaster
        'campus_life_headmaster_name', 'campus_life_headmaster_description',
        'campus_life_headmaster_vision', 'campus_life_headmaster_photo',

        // Programs
        'program_section_title', 'program_section_subtitle',
        'program_ipa_title', 'program_ipa_description',
        'program_ips_title', 'program_ips_description',
        'program_religion_title', 'program_religion_description',
        'program_section_image',

        // Counters
        'counter1_number', 'counter1_label',
        'counter2_number', 'counter2_label',
        'counter3_number', 'counter3_label',

        // Gallery
        'gallery_title', 'gallery_subtitle',

        // CTA
        'cta_title', 'cta_description', 'cta_button_text', 'cta_button_url', 'cta_video_title',

        // Contact
        'contact_email', 'contact_phone', 'contact_address',
        'contact_section_subtitle', 'contact_section_title', 'contact_section_description',
        'contact_map_url', 'contact_operational_hours',

        // Social Media
        'social_facebook', 'social_instagram', 'social_youtube', 'social_whatsapp',

        // Video
        'video_url', 'video_thumbnail',
    ];

    /**
     * Map keys ke group_name berdasarkan ThemeSetting::getDefaultGroupMap().
     */
    private array $groupMap = [
        'site_name' => 'general',
        'site_description' => 'general',
        'site_keywords' => 'general',
        'footer_text' => 'general',
        'logo' => 'general',
        'favicon' => 'general',
        'gallery_title' => 'general',
        'gallery_subtitle' => 'general',
        'social_facebook' => 'social',
        'social_instagram' => 'social',
        'social_youtube' => 'social',
        'social_whatsapp' => 'social',
        'hero_images' => 'hero',
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
        'feature1_title' => 'features',
        'feature1_description' => 'features',
        'feature2_title' => 'features',
        'feature2_description' => 'features',
        'feature3_title' => 'features',
        'feature3_description' => 'features',
        'program_section_title' => 'programs',
        'program_section_subtitle' => 'programs',
        'program_ipa_title' => 'programs',
        'program_ipa_description' => 'programs',
        'program_ips_title' => 'programs',
        'program_ips_description' => 'programs',
        'program_religion_title' => 'programs',
        'program_religion_description' => 'programs',
        'program_section_image' => 'programs',
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
        'headmaster_name' => 'about',
        'headmaster_description' => 'about',
        'headmaster_vision' => 'about',
        'headmaster_photo' => 'about',
        'campus_life_headmaster_name' => 'about',
        'campus_life_headmaster_description' => 'about',
        'campus_life_headmaster_vision' => 'about',
        'campus_life_headmaster_photo' => 'about',
        'video_url' => 'video',
        'video_thumbnail' => 'video',
        'counter1_number' => 'counter',
        'counter1_label' => 'counter',
        'counter2_number' => 'counter',
        'counter2_label' => 'counter',
        'counter3_number' => 'counter',
        'counter3_label' => 'counter',
        'cta_title' => 'cta',
        'cta_description' => 'cta',
        'cta_button_url' => 'cta',
        'cta_button_text' => 'cta',
        'cta_video_title' => 'cta',
        'contact_email' => 'contact',
        'contact_phone' => 'contact',
        'contact_address' => 'contact',
        'contact_section_subtitle' => 'contact',
        'contact_section_title' => 'contact',
        'contact_section_description' => 'contact',
        'contact_map_url' => 'contact',
        'contact_operational_hours' => 'contact',
    ];

    public function up(): void
    {
        $themes = ['telkom', 'maudu'];

        foreach ($themes as $theme) {
            $migrated = 0;

            foreach ($this->landingPageKeys as $index => $key) {
                $cacheKey = "site_setting_{$key}";
                $value = Cache::get($cacheKey);

                if ($value !== null) {
                    DB::table('theme_settings')->updateOrInsert(
                        ['theme' => $theme, 'key' => $key],
                        [
                            'value' => $value,
                            'type' => $this->inferType($key),
                            'group_name' => $this->groupMap[$key] ?? 'general',
                            'sort_order' => $index,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    $migrated++;
                }
            }

            // Clear theme cache supaya dibaca ulang dari DB
            if ($migrated > 0) {
                Cache::forget("theme_settings_{$theme}");
            }
        }

        // Hapus semua site_setting_* dari cache global (cleanup)
        foreach ($this->landingPageKeys as $key) {
            Cache::forget("site_setting_{$key}");
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback — data di theme_settings sudah benar.
        // Cache global sudah di-cleanup, tidak perlu restore.
    }

    /**
     * Infer type berdasarkan key name.
     */
    private function inferType(string $key): string
    {
        return match (true) {
            str_ends_with($key, '_photo') || str_ends_with($key, '_image') || str_ends_with($key, '_thumbnail') || $key === 'favicon' || $key === 'logo' => 'image',
            str_ends_with($key, '_url') => 'url',
            str_ends_with($key, '_description') || str_ends_with($key, '_text') => 'textarea',
            in_array($key, ['hero_images']) => 'json',
            default => 'text',
        };
    }
};
