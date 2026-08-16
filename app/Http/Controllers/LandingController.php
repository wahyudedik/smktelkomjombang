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
     * Convention: view name = theme name (telkom.blade.php, maudu.blade.php, etc.)
     * Generic — no hardcoded theme names.
     *
     * @see plans/theme-system-refactoring.md — Fase 3
     */
    public function index()
    {
        $theme = current_theme();

        View::share('themeConfig', theme_config());
        View::share('currentTheme', $theme);

        $data = $this->buildData();

        // Convention: view name = theme name (telkom.blade.php, maudu.blade.php)
        return view($theme, $data);
    }

    /**
     * Display the telkom landing page (direct route).
     *
     * @deprecated Use GET / with DEFAULT_THEME=telkom, or GET /telkom
     */
    public function telkom()
    {
        View::share('themeConfig', theme_config());
        View::share('currentTheme', 'telkom');

        return view('telkom', $this->buildData());
    }

    /**
     * Display the maudu landing page (direct route).
     *
     * @deprecated Use GET / with DEFAULT_THEME=maudu, or GET /maudu
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
    private function getSiteSettings(): array
    {
        $theme = current_theme();
        $themeConfig = config("themes.available.{$theme}", []);
        $themeData = theme_config() ?: [];

        return [
            // Basic site info — read per-theme from theme_settings table
            'site_name' => theme_config('site_name') ?? $themeData['name'] ?? $themeConfig['name'] ?? config('app.name'),
            'site_description' => theme_config('site_description') ?? $themeData['tagline'] ?? '',
            'site_keywords' => theme_config('site_keywords') ?? $themeData['short_name'] ?? '',
            'logo' => theme_config('logo') ?? null,
            'favicon' => theme_config('favicon') ?? null,

            // Hero section
            'hero_title' => theme_config('hero_title') ?? $themeData['tagline'] ?? '',
            'hero_subtitle' => theme_config('hero_subtitle') ?? '',
            'hero_images' => theme_config('hero_images') ?? ($themeData['hero_images'] ?? []),

            // Hero slides — theme-aware defaults
            'hero_slide1_subtitle' => theme_config('hero_slide1_subtitle') ?? ($themeData['hero_slides'][0]['subtitle'] ?? 'Slide 1'),
            'hero_slide1_title' => theme_config('hero_slide1_title') ?? ($themeData['hero_slides'][0]['title'] ?? ''),
            'hero_slide1_description' => theme_config('hero_slide1_description') ?? ($themeData['hero_slides'][0]['description'] ?? ''),
            'hero_slide2_subtitle' => theme_config('hero_slide2_subtitle') ?? ($themeData['hero_slides'][1]['subtitle'] ?? 'Slide 2'),
            'hero_slide2_title' => theme_config('hero_slide2_title') ?? ($themeData['hero_slides'][1]['title'] ?? ''),
            'hero_slide2_description' => theme_config('hero_slide2_description') ?? ($themeData['hero_slides'][1]['description'] ?? ''),
            'hero_slide3_subtitle' => theme_config('hero_slide3_subtitle') ?? ($themeData['hero_slides'][2]['subtitle'] ?? 'Slide 3'),
            'hero_slide3_title' => theme_config('hero_slide3_title') ?? ($themeData['hero_slides'][2]['title'] ?? ''),
            'hero_slide3_description' => theme_config('hero_slide3_description') ?? ($themeData['hero_slides'][2]['description'] ?? ''),

            // Features — theme-aware defaults
            'feature1_title' => theme_config('feature1_title') ?? ($themeData['features'][0]['title'] ?? ''),
            'feature1_description' => theme_config('feature1_description') ?? ($themeData['features'][0]['desc'] ?? ''),
            'feature2_title' => theme_config('feature2_title') ?? ($themeData['features'][1]['title'] ?? ''),
            'feature2_description' => theme_config('feature2_description') ?? ($themeData['features'][1]['desc'] ?? ''),
            'feature3_title' => theme_config('feature3_title') ?? ($themeData['features'][2]['title'] ?? ''),
            'feature3_description' => theme_config('feature3_description') ?? ($themeData['features'][2]['desc'] ?? ''),

            // About section
            'about_section_title' => theme_config('about_section_title') ?? 'Tentang Kami',
            'about_section_subtitle' => theme_config('about_section_subtitle') ?? 'Mengenal Lebih Dekat',
            'about_section_description' => theme_config('about_section_description') ?? '',
            'about_image_1' => theme_config('about_image_1') ?? null,
            'about_image_2' => theme_config('about_image_2') ?? null,
            'about_image_3' => theme_config('about_image_3') ?? null,
            'about_feature_1_title' => theme_config('about_feature_1_title') ?? '',
            'about_feature_1_description' => theme_config('about_feature_1_description') ?? '',
            'about_feature_2_title' => theme_config('about_feature_2_title') ?? '',
            'about_feature_2_description' => theme_config('about_feature_2_description') ?? '',
            'about_feature_3_title' => theme_config('about_feature_3_title') ?? '',
            'about_feature_3_description' => theme_config('about_feature_3_description') ?? '',
            'about_feature_4_title' => theme_config('about_feature_4_title') ?? '',
            'about_feature_4_description' => theme_config('about_feature_4_description') ?? '',
            'about_button_text' => theme_config('about_button_text') ?? 'Detail',
            'about_contact_text' => theme_config('about_contact_text') ?? 'Hubungi Kami',
            'about_contact_phone' => theme_config('about_contact_phone') ?? '',

            // Headmaster — theme-aware defaults
            'headmaster_name' => theme_config('headmaster_name') ?? ($themeData['kepala_sekolah']['name'] ?? ''),
            'headmaster_description' => theme_config('headmaster_description') ?? ($themeData['kepala_sekolah']['description'] ?? ''),
            'headmaster_vision' => theme_config('headmaster_vision') ?? ($themeData['kepala_sekolah']['description_2'] ?? ''),
            'headmaster_photo' => theme_config('headmaster_photo') ?? ($themeData['kepala_sekolah']['photo'] ?? null),

            // Campus life headmaster — theme-aware defaults
            'campus_life_headmaster_name' => theme_config('campus_life_headmaster_name') ?? ($themeData['kepala_sekolah']['name'] ?? ''),
            'campus_life_headmaster_description' => theme_config('campus_life_headmaster_description') ?? ($themeData['kepala_sekolah']['description'] ?? ''),
            'campus_life_headmaster_vision' => theme_config('campus_life_headmaster_vision') ?? ($themeData['kepala_sekolah']['description_2'] ?? ''),
            'campus_life_headmaster_photo' => theme_config('campus_life_headmaster_photo') ?? ($themeData['kepala_sekolah']['photo'] ?? null),

            // Programs
            'program_section_title' => theme_config('program_section_title') ?? 'Program Unggulan',
            'program_section_subtitle' => theme_config('program_section_subtitle') ?? '',
            'program_ipa_title' => theme_config('program_ipa_title') ?? ($themeData['program_peminatan'][0]['full_name'] ?? 'Program IPA'),
            'program_ipa_description' => theme_config('program_ipa_description') ?? ($themeData['program_peminatan'][0]['desc'] ?? ''),
            'program_ips_title' => theme_config('program_ips_title') ?? ($themeData['program_peminatan'][1]['full_name'] ?? 'Program IPS'),
            'program_ips_description' => theme_config('program_ips_description') ?? ($themeData['program_peminatan'][1]['desc'] ?? ''),
            'program_religion_title' => theme_config('program_religion_title') ?? ($themeData['program_peminatan'][2]['full_name'] ?? 'Program Keagamaan'),
            'program_religion_description' => theme_config('program_religion_description') ?? ($themeData['program_peminatan'][2]['desc'] ?? ''),
            'program_section_image' => theme_config('program_section_image') ?? null,

            // Counters — theme-aware defaults
            'counter1_number' => theme_config('counter1_number') ?? '24',
            'counter1_label' => theme_config('counter1_label') ?? 'Mata Pelajaran',
            'counter2_number' => theme_config('counter2_number') ?? '800',
            'counter2_label' => theme_config('counter2_label') ?? 'Peserta Didik',
            'counter3_number' => theme_config('counter3_number') ?? '98',
            'counter3_label' => theme_config('counter3_label') ?? 'Tenaga Pendidik',

            // Gallery
            'gallery_title' => theme_config('gallery_title') ?? 'Galeri',
            'gallery_subtitle' => theme_config('gallery_subtitle') ?? 'Kegiatan Sekolah',

            // Contact — theme-aware defaults
            'contact_email' => theme_config('contact_email') ?? ($themeData['email'] ?? 'info@smktelekom.sch.id'),
            'contact_phone' => theme_config('contact_phone') ?? ($themeData['phone'] ?? ''),
            'contact_address' => theme_config('contact_address') ?? ($themeData['address'] ?? ''),
            'contact_section_subtitle' => theme_config('contact_section_subtitle') ?? 'Hubungi Kami',
            'contact_section_title' => theme_config('contact_section_title') ?? 'Kontak',
            'contact_section_description' => theme_config('contact_section_description') ?? 'Jangan ragu untuk menghubungi kami jika memiliki pertanyaan',

            // Social media — theme-aware defaults
            'social_facebook' => theme_config('social_facebook') ?? ($themeData['facebook_url'] ?? ''),
            'social_instagram' => theme_config('social_instagram') ?? ($themeData['instagram_url'] ?? ''),
            'social_youtube' => theme_config('social_youtube') ?? ($themeData['youtube_url'] ?? ''),
            'social_whatsapp' => theme_config('social_whatsapp') ?? ($themeData['whatsapp'] ?? ''),

            // Video — theme-aware defaults
            'video_url' => theme_config('video_url') ?? ($themeData['video_url'] ?? ''),
            'video_thumbnail' => theme_config('video_thumbnail') ?? ($themeData['video_thumbnail'] ?? null),

            // CTA Section — theme-aware defaults
            'cta_title' => theme_config('cta_title') ?? ($themeData['cta_title'] ?? 'Pendaftaran Siswa Baru ' . date('Y')),
            'cta_description' => theme_config('cta_description') ?? ($themeData['cta_description'] ?? ''),
            'cta_button_text' => theme_config('cta_button_text') ?? ($themeData['cta_button_text'] ?? 'DAFTAR'),
            'cta_button_url' => theme_config('cta_button_url') ?? ($themeData['cta_button_url'] ?? '#'),
            'cta_video_title' => theme_config('cta_video_title') ?? '',

            // Contact Map & Hours — theme-aware defaults
            'contact_map_url' => theme_config('contact_map_url') ?? ($themeData['google_maps_url'] ?? ''),
            'contact_operational_hours' => theme_config('contact_operational_hours') ?? (($themeData['working_hours']['days'] ?? 'Senin - Sabtu') . ': ' . ($themeData['working_hours']['hours'] ?? '07.00 - 16.00 WIB')),

            // Footer — theme-aware defaults
            'footer_text' => theme_config('footer_text') ?? '© ' . date('Y') . ' ' . ($themeData['name'] ?? config('app.name')) . '. All rights reserved.',
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
