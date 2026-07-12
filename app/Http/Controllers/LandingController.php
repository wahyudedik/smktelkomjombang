<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelulusan;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\Partner;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    /**
     * Main entry point — checks DEFAULT_THEME and returns appropriate view.
     *
     * Environment variable DEFAULT_THEME can be:
     *   - 'telkom' (default) → resources/views/telkom.blade.php
     *   - 'maudu'            → resources/views/maudu.blade.php
     */
    public function index()
    {
        $theme = Config::get('app.default_theme', 'telkom');

        $themeConfig = theme_config();
        View::share('themeConfig', $themeConfig);
        View::share('currentTheme', $theme);

        $data = $this->buildData();

        return view($theme === 'maudu' ? 'maudu' : 'telkom', $data);
    }

    /**
     * Display the telkom landing page (direct route)
     */
    public function telkom()
    {
        return view('telkom', $this->buildData());
    }

    /**
     * Display the maudu landing page (direct route)
     */
    public function maudu()
    {
        View::share('themeConfig', theme_config());
        View::share('currentTheme', 'maudu');

        return view('maudu', $this->buildData());
    }

    /**
     * Build shared landing page data for all themes.
     * Avoids code duplication across index(), telkom(), and maudu().
     */
    private function buildData(): array
    {
        // Share site settings with all Blade views
        View::share('siteSettings', $this->getSiteSettings());

        return [
            'siswaCount' => $this->getSiswaCount(),
            'kelulusanPercentage' => $this->getKelulusanPercentage(),
            'testimonials' => $this->getTestimonials(),
            'blogs' => $this->getBlogs(),
            'partners' => $this->getPartners(),
            'events' => $this->getEvents(),
            'instagramPosts' => $this->getInstagramPosts(),
        ];
    }

    /**
     * Get active student count with caching
     */
    private function getSiswaCount()
    {
        return Cache::remember('telkom_siswa_count', 86400, function () {
            return Siswa::where('status', 'aktif')->count();
        });
    }

    /**
     * Get graduation percentage with caching
     */
    private function getKelulusanPercentage()
    {
        return Cache::remember('telkom_kelulusan_percentage', 86400, function () {
            $total = Kelulusan::count();
            if ($total === 0) return 0;
            // Count graduates who continued to higher education (have tempat_kuliah filled)
            $lanjutKuliah = Kelulusan::where('status', 'lulus')
                ->whereNotNull('tempat_kuliah')
                ->where('tempat_kuliah', '!=', '')
                ->count();
            return round(($lanjutKuliah / $total) * 100);
        });
    }

    /**
     * Get approved testimonials with caching
     */
    private function getTestimonials()
    {
        return Cache::remember('telkom_testimonials', 86400, function () {
            $testimonials = Testimonial::where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Fallback to dummy testimonials if empty
            if ($testimonials->isEmpty()) {
                return collect(Testimonial::getDummyTestimonials());
            }

            return $testimonials;
        });
    }

    /**
     * Get published blog pages with caching
     */
    private function getBlogs()
    {
        return Cache::remember('telkom_blogs', 86400, function () {
            return Page::where('status', 'published')
                ->where('category', 'berita')
                ->where('published_at', '<=', now())
                ->with('user')
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();
        });
    }

    /**
     * Get active partners with caching
     */
    private function getPartners()
    {
        return Cache::remember('telkom_partners', 86400, function () {
            return Partner::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get upcoming events with caching
     */
    private function getEvents()
    {
        return Cache::remember('telkom_events', 43200, function () {
            if (class_exists('App\Models\Events')) {
                // Show active events: upcoming first, then recent past events as fallback
                $events = \App\Models\Events::where('status', 'active')
                    ->where('date', '>=', now())
                    ->orderBy('date', 'asc')
                    ->limit(3)
                    ->get();

                // If fewer than 3 upcoming, fill with most recent active events
                if ($events->count() < 3) {
                    $events = \App\Models\Events::where('status', 'active')
                        ->orderBy('date', 'desc')
                        ->limit(3)
                        ->get();
                }

                return $events;
            }

            return collect();
        });
    }

    /**
     * Get Instagram posts for gallery section
     */
    private function getInstagramPosts()
    {
        try {
            $service = app(InstagramService::class);
            return $service->getCachedPosts(6);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all site settings from cache with fallback defaults
     */
    private function getSiteSettings()
    {
        return [
            // Basic site info
            'site_name' => cache('site_setting_site_name') ?? 'SMK Telekomunikasi Darul Ulum',
            'site_description' => cache('site_setting_site_description') ?? 'Sekolah Menengah Kejuruan Telekomunikasi',
            'site_keywords' => cache('site_setting_site_keywords') ?? 'SMK, Telekomunikasi, Darul Ulum',
            'logo' => cache('site_setting_logo') ?? null,
            'favicon' => cache('site_setting_favicon') ?? null,

            // Hero section
            'hero_title' => cache('site_setting_hero_title') ?? 'Selamat Datang di SMK Telekomunikasi Darul Ulum',
            'hero_subtitle' => cache('site_setting_hero_subtitle') ?? 'Membangun Generasi Unggul',
            'hero_images' => cache('site_setting_hero_images') ? json_decode(cache('site_setting_hero_images'), true) : [],

            // Hero slides
            'hero_slide1_subtitle' => cache('site_setting_hero_slide1_subtitle') ?? 'Slide 1',
            'hero_slide1_title' => cache('site_setting_hero_slide1_title') ?? 'Pendidikan Berkualitas',
            'hero_slide1_description' => cache('site_setting_hero_slide1_description') ?? '',
            'hero_slide2_subtitle' => cache('site_setting_hero_slide2_subtitle') ?? 'Slide 2',
            'hero_slide2_title' => cache('site_setting_hero_slide2_title') ?? 'Teknologi Terdepan',
            'hero_slide2_description' => cache('site_setting_hero_slide2_description') ?? '',
            'hero_slide3_subtitle' => cache('site_setting_hero_slide3_subtitle') ?? 'Slide 3',
            'hero_slide3_title' => cache('site_setting_hero_slide3_title') ?? 'Masa Depan Cerah',
            'hero_slide3_description' => cache('site_setting_hero_slide3_description') ?? '',

            // Features
            'feature1_title' => cache('site_setting_feature1_title') ?? 'Fasilitas Modern',
            'feature1_description' => cache('site_setting_feature1_description') ?? 'Fasilitas pembelajaran terkini',
            'feature2_title' => cache('site_setting_feature2_title') ?? 'Guru Berpengalaman',
            'feature2_description' => cache('site_setting_feature2_description') ?? 'Tenaga pengajar berkualitas',
            'feature3_title' => cache('site_setting_feature3_title') ?? 'Kurikulum Terbaik',
            'feature3_description' => cache('site_setting_feature3_description') ?? 'Kurikulum sesuai kebutuhan industri',

            // About section
            'about_section_title' => cache('site_setting_about_section_title') ?? 'Tentang Kami',
            'about_section_subtitle' => cache('site_setting_about_section_subtitle') ?? 'Mengenal Lebih Dekat',
            'about_section_description' => cache('site_setting_about_section_description') ?? '',
            'about_image_1' => cache('site_setting_about_image_1') ?? null,
            'about_image_2' => cache('site_setting_about_image_2') ?? null,
            'about_image_3' => cache('site_setting_about_image_3') ?? null,
            'about_feature_1_title' => cache('site_setting_about_feature_1_title') ?? '',
            'about_feature_1_description' => cache('site_setting_about_feature_1_description') ?? '',
            'about_feature_2_title' => cache('site_setting_about_feature_2_title') ?? '',
            'about_feature_2_description' => cache('site_setting_about_feature_2_description') ?? '',
            'about_feature_3_title' => cache('site_setting_about_feature_3_title') ?? '',
            'about_feature_3_description' => cache('site_setting_about_feature_3_description') ?? '',
            'about_feature_4_title' => cache('site_setting_about_feature_4_title') ?? '',
            'about_feature_4_description' => cache('site_setting_about_feature_4_description') ?? '',
            'about_button_text' => cache('site_setting_about_button_text') ?? 'Selengkapnya',
            'about_contact_text' => cache('site_setting_about_contact_text') ?? 'Hubungi Kami',
            'about_contact_phone' => cache('site_setting_about_contact_phone') ?? '',

            // Headmaster
            'headmaster_name' => cache('site_setting_headmaster_name') ?? '',
            'headmaster_description' => cache('site_setting_headmaster_description') ?? '',
            'headmaster_vision' => cache('site_setting_headmaster_vision') ?? '',
            'headmaster_photo' => cache('site_setting_headmaster_photo') ?? null,

            // Campus life headmaster
            'campus_life_headmaster_name' => cache('site_setting_campus_life_headmaster_name') ?? '',
            'campus_life_headmaster_description' => cache('site_setting_campus_life_headmaster_description') ?? '',
            'campus_life_headmaster_vision' => cache('site_setting_campus_life_headmaster_vision') ?? '',
            'campus_life_headmaster_photo' => cache('site_setting_campus_life_headmaster_photo') ?? null,

            // Programs
            'program_section_title' => cache('site_setting_program_section_title') ?? 'Program Unggulan',
            'program_section_subtitle' => cache('site_setting_program_section_subtitle') ?? 'Kerjasama Industri',
            'program_ipa_title' => cache('site_setting_program_ipa_title') ?? 'Program IPA',
            'program_ipa_description' => cache('site_setting_program_ipa_description') ?? '',
            'program_ips_title' => cache('site_setting_program_ips_title') ?? 'Program IPS',
            'program_ips_description' => cache('site_setting_program_ips_description') ?? '',
            'program_religion_title' => cache('site_setting_program_religion_title') ?? 'Program Keagamaan',
            'program_religion_description' => cache('site_setting_program_religion_description') ?? '',
            'program_section_image' => cache('site_setting_program_section_image') ?? null,

            // Counters
            'counter1_number' => cache('site_setting_counter1_number') ?? '500',
            'counter1_label' => cache('site_setting_counter1_label') ?? 'Siswa',
            'counter2_number' => cache('site_setting_counter2_number') ?? '50',
            'counter2_label' => cache('site_setting_counter2_label') ?? 'Guru',
            'counter3_number' => cache('site_setting_counter3_number') ?? '20',
            'counter3_label' => cache('site_setting_counter3_label') ?? 'Program',

            // Gallery
            'gallery_title' => cache('site_setting_gallery_title') ?? 'Galeri',
            'gallery_subtitle' => cache('site_setting_gallery_subtitle') ?? 'Kegiatan Sekolah',

            // Contact
            'contact_email' => cache('site_setting_contact_email') ?? 'info@smktelekom.sch.id',
            'contact_phone' => cache('site_setting_contact_phone') ?? '(021) 123456',
            'contact_address' => cache('site_setting_contact_address') ?? '',
            'contact_section_subtitle' => cache('site_setting_contact_section_subtitle') ?? 'Hubungi Kami',
            'contact_section_title' => cache('site_setting_contact_section_title') ?? 'Kontak',
            'contact_section_description' => cache('site_setting_contact_section_description') ?? 'Jangan ragu untuk menghubungi kami jika memiliki pertanyaan',

            // Social media
            'social_facebook' => cache('site_setting_social_facebook') ?? '',
            'social_instagram' => cache('site_setting_social_instagram') ?? '',
            'social_youtube' => cache('site_setting_social_youtube') ?? '',
            'social_whatsapp' => cache('site_setting_social_whatsapp') ?? '',

            // Video
            'video_url' => cache('site_setting_video_url') ?? '',
            'video_thumbnail' => cache('site_setting_video_thumbnail') ?? null,

            // CTA Section
            'cta_title' => cache('site_setting_cta_title') ?? 'Pendaftaran Siswa Baru ' . date('Y'),
            'cta_description' => cache('site_setting_cta_description') ?? "Tempat Pendaftaran\n1. Online mandiri (24 jam) dengan alamat web psb.ponpesdarululum.id\n2. Kantor Pusat Pondok Pesantren Darul 'Ulum Jombang\n3. Buka Hari Sabtu - Kamis pukul 08:00 - 16:00 WIB\n4. Hari Jum'at & hari libur nasional pendaftaran kantor pusat libur",
            'cta_button_text' => cache('site_setting_cta_button_text') ?? 'DAFTAR',
            'cta_button_url' => cache('site_setting_cta_button_url') ?? 'https://psb.ponpesdarululum.id/',
            'cta_video_title' => cache('site_setting_cta_video_title') ?? 'Profil SMK Telekomunikasi DU',

            // Contact Map & Hours
            'contact_map_url' => cache('site_setting_contact_map_url') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.8547!2d112.2327!3d-7.5469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e2c1e8e8e8e9%3A0x1234567890abcdef!2sSMK%20Telekomunikasi%20Darul%20Ulum!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid',
            'contact_operational_hours' => cache('site_setting_contact_operational_hours') ?? 'Senin - Sabtu: 07.00 - 16.00 WIB',

            // Footer
            'footer_text' => cache('site_setting_footer_text') ?? '© ' . date('Y') . ' SMK Telekomunikasi Darul Ulum. All rights reserved.',
        ];
    }

    /**
     * Buat halaman-halaman statis untuk navigasi landing page.
     * Delegate ke StaticPageGenerator service.
     *
     * Jalankan sekali: php artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()"
     */
    public function createStaticPages(): string
    {
        return app(\App\Services\StaticPageGenerator::class)->generate();
    }
}
