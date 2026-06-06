<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelulusan;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\Partner;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    /**
     * Display the telkom landing page
     */
    public function telkom()
    {
        // Load site settings and share with all views
        $siteSettings = $this->getSiteSettings();
        View::share('siteSettings', $siteSettings);

        $data = [
            'siswaCount' => $this->getSiswaCount(),
            'kelulusanPercentage' => $this->getKelulusanPercentage(),
            'testimonials' => $this->getTestimonials(),
            'blogs' => $this->getBlogs(),
            'partners' => $this->getPartners(),
            'events' => $this->getEvents(),
            'instagramPosts' => $this->getInstagramPosts(),
        ];

        return view('telkom', $data);
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
     * Jalankan sekali: php artisan tinker --execute="(new \App\Http\Controllers\LandingController)->createStaticPages()"
     */
    public function createStaticPages()
    {
        $pages = [
            [
                'title' => 'PP. Darul Ulum',
                'slug' => 'pp-darul-ulum',
                'category' => 'profil',
                'template' => 'about',
                'excerpt' => 'Tentang Pondok Pesantren Darul Ulum Jombang',
                'content' => '<div class="row">
                    <div class="col-lg-12">
                        <h2>Pondok Pesantren Darul Ulum Jombang</h2>
                        <p class="lead">Pondok Pesantren Darul Ulum merupakan salah satu pondok pesantren tertua dan terbesar di Kabupaten Jombang, Jawa Timur. Berdiri sejak tahun 1928, pondok pesantren ini telah menjadi pusat pendidikan Islam yang dikenal luas di kalangan masyarakat.</p>
                        <p>SMK Telekomunikasi Darul Ulum merupakan salah satu unit pendidikan di bawah naungan Pondok Pesantren Darul Ulum yang menyelenggarakan pendidikan vokasi dengan fokus pada bidang telekomunikasi dan teknologi informasi.</p>

                        <h3>Sejarah Singkat</h3>
                        <p>Pondok Pesantren Darul Ulum didirikan oleh Mbah Kyai Haji Abdul Wahab Chabas dan Mbah Kyai Haji Hashim yang merupakan ulama besar dari Jombang. Sejak awal berdirinya, pondok pesantren ini telah berkomitmen untuk mengembangkan pendidikan Islam yang berkualitas.</p>

                        <h3>Visi & Misi Ponpes</h3>
                        <p>Menjadi lembaga pendidikan Islam terdepan yang menghasilkan kader-kader ulama, umat, dan bangsa yang bertaqwa, cerdas, mandiri, dan peduli lingkungan.</p>
                        <ul>
                            <li>Menyelenggarakan pendidikan Islam yang berkualitas</li>
                            <li>Mengembangkan potensi santri secara holistik</li>
                            <li>Melestarikan tradisi keilmuan Islam</li>
                            <li>Berkontribusi dalam pembangunan bangsa</li>
                        </ul>

                        <h3>Lokasi</h3>
                        <p>Pondok Pesantren Darul Ulum berlokasi di Jombang, Jawa Timur, Indonesia. Lokasi yang strategis menjadikan pondok pesantren ini mudah diakses dari berbagai daerah.</p>
                    </div>
                </div>',
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'PP. Darul Ulum',
                'menu_sort_order' => 1,
            ],
            [
                'title' => 'Visi & Misi SMK',
                'slug' => 'visi-misi-smk',
                'category' => 'profil',
                'template' => 'about',
                'excerpt' => 'Visi dan Misi SMK Telekomunikasi Darul Ulum',
                'content' => '<div class="row">
                    <div class="col-lg-12">
                        <h2>Visi & Misi SMK Telekomunikasi Darul Ulum</h2>

                        <div class="card mb-4" style="border-left: 4px solid #007bff;">
                            <div class="card-body">
                                <h3 style="color: #007bff;">Visi</h3>
                                <p class="lead">"Terwujudnya insan SMK Telekomunikasi Darul Ulum yang Beriman, Bertaqwa, Cerdas, Kompeten, dan Berakhlak Mulia"</p>
                            </div>
                        </div>

                        <h3>Misi</h3>
                        <ol>
                            <li>Menyelenggarakan pendidikan berbasis iman dan taqwa sesuai kurikulum nasional</li>
                            <li>Mengembangkan kompetensi peserta didik di bidang telekomunikasi dan teknologi informasi</li>
                            <li>Membentuk karakter peserta didik yang berakhlak mulia dan bertanggung jawab</li>
                            <li>Menyiapkan peserta didik siap kerja dan siap melanjutkan ke jenjang pendidikan yang lebih tinggi</li>
                            <li>Menjalin kerjasama dengan dunia industri dan perguruan tinggi</li>
                            <li>Mengembangkan potensi peserta didik secara optimal melalui kegiatan ekstrakurikuler</li>
                        </ol>

                        <h3>Tujuan</h3>
                        <ul>
                            <li>Menghasilkan lulusan yang kompeten di bidang teknologi informasi dan komunikasi</li>
                            <li>Membentuk peserta didik yang beriman, bertaqwa, dan berakhlak mulia</li>
                            <li>Menyiapkan tenaga kerja tingkat menengah yang profesional</li>
                            <li>Mengembangkan kreativitas dan inovasi peserta didik</li>
                        </ul>
                    </div>
                </div>',
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'Visi & Misi',
                'menu_sort_order' => 2,
            ],
            [
                'title' => 'Struktur Organisasi SMK',
                'slug' => 'struktur-smk',
                'category' => 'profil',
                'template' => 'about',
                'excerpt' => 'Struktur organisasi SMK Telekomunikasi Darul Ulum',
                'content' => '<div class="row">
                    <div class="col-lg-12">
                        <h2>Struktur Organisasi SMK Telekomunikasi Darul Ulum</h2>
                        <p class="lead">Struktur organisasi SMK Telekomunikasi Darul Ulum dirancang untuk menjalankan fungsi manajemen pendidikan secara efektif dan efisien.</p>

                        <div class="org-chart">
                            <h3>Kepala Sekolah</h3>
                            <p>Pimpinan tertinggi sekolah yang bertanggung jawab atas seluruh kegiatan pendidikan dan pengelolaan sekolah.</p>

                            <h3>Wakil Kepala Sekolah</h3>
                            <ul>
                                <li><strong>Wakil Kurikulum</strong> - Mengkoordinasikan kegiatan kurikulum dan akademik</li>
                                <li><strong>Wakil Kesiswaan</strong> - Mengkoordinasikan kegiatan kesiswaan dan bimbingan konseling</li>
                                <li><strong>Wakil Sarana & Prasarana</strong> - Mengelola sarana dan prasarana sekolah</li>
                                <li><strong>Wakil Humas & Hubungan Industri</strong> - Mengelola hubungan dengan pihak luar dan dunia industri</li>
                            </ul>

                            <h3>Unit Pelaksana Teknis</h3>
                            <ul>
                                <li><strong>Program Keahlian</strong> - Produkti Film, DKV, TKJ, RPL</li>
                                <li><strong>BPPPP</strong> - Bimbingan dan Penyuluhan, Pendidikan, Pelatihan</li>
                                <li><strong>Perpustakaan</strong></li>
                                <li><strong>Lab Komputer</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>',
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'Struktur Organisasi',
                'menu_sort_order' => 3,
            ],
            [
                'title' => 'Tenaga Pendidik',
                'slug' => 'tenaga-pendidik',
                'category' => 'akademik',
                'template' => 'about',
                'excerpt' => 'Daftar guru dan tenaga pendidik SMK Telekomunikasi Darul Ulum',
                'content' => '<div class="row">
                    <div class="col-lg-12">
                        <h2>Tenaga Pendidik</h2>
                        <p class="lead">SMK Telekomunikasi Darul Ulum memiliki tenaga pendidik yang berkualitas dan berkompeten di bidangnya masing-masing.</p>
                    </div>
                </div>
                <div id="tenaga-pendidik-list" data-source="guru">
                    <p class="text-muted">Daftar guru akan ditampilkan dari database. Hubungi sekolah untuk informasi terkini mengenai tenaga pendidik kami.</p>
                </div>',
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'Tenaga Pendidik',
                'menu_sort_order' => 4,
            ],
            [
                'title' => 'Staf & Karyawan',
                'slug' => 'staf-karyawan',
                'category' => 'akademik',
                'template' => 'about',
                'excerpt' => 'Daftar staf dan karyawan SMK Telekomunikasi Darul Ulum',
                'content' => '<div class="row">
                    <div class="col-lg-12">
                        <h2>Staf & Karyawan</h2>
                        <p class="lead">Staf dan karyawan SMK Telekomunikasi Darul Ulum yang mendukung kelancaran operasional sekolah.</p>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-user-tie fa-3x mb-3" style="color: #007bff;"></i>
                                        <h5>Tata Usaha</h5>
                                        <p class="text-muted">Mengelola administrasi dan kearsipan sekolah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-broom fa-3x mb-3" style="color: #28a745;"></i>
                                        <h5>Kebersihan & Keamanan</h5>
                                        <p class="text-muted">Menjaga kebersihan dan keamanan lingkungan sekolah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-laptop-code fa-3x mb-3" style="color: #ffc107;"></i>
                                        <h5>Teknisi</h5>
                                        <p class="text-muted">Mengelola infrastruktur teknologi dan jaringan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-book fa-3x mb-3" style="color: #17a2b8;"></i>
                                        <h5>Pustakawan</h5>
                                        <p class="text-muted">Mengelola perpustakaan dan layanan informasi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'Staf & Karyawan',
                'menu_sort_order' => 5,
            ],
        ];

        $created = 0;
        $updated = 0;
        $userId = 1; // Default admin user

        // Coba dapatkan user_id dari admin
        if (class_exists('App\Models\User')) {
            $adminUser = \App\Models\User::where('email', 'admin@smktelkom.sch.id')->first();
            if ($adminUser) {
                $userId = $adminUser->id;
            }
        }

        foreach ($pages as $pageData) {
            $pageData['user_id'] = $userId;
            $pageData['published_at'] = now();

            $existing = Page::where('slug', $pageData['slug'])->first();

            if ($existing) {
                $existing->update($pageData);
                $updated++;
            } else {
                Page::create($pageData);
                $created++;
            }
        }

        return "Selesai! Dibuat: {$created} halaman baru, Diperbarui: {$updated} halaman yang sudah ada.";
    }
}
