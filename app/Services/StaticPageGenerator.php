<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\Log;

class StaticPageGenerator
{
    /**
     * Generate static pages for the landing page navigation.
     * Run once via: php artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()"
     */
    public function generate(): string
    {
        $pages = $this->getPageDefinitions();

        $adminUserId = $this->getAdminUserId();
        $created = 0;
        $updated = 0;

        foreach ($pages as $pageData) {
            $pageData['user_id'] = $adminUserId;
            $pageData['published_at'] = now();

            $slug = $pageData['slug'];
            unset($pageData['slug']); // Hapus slug dari data untuk updateOrCreate

            $result = Page::updateOrCreate(
                ['slug' => $slug],
                $pageData
            );

            if ($result->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        return "Selesai! Dibuat: {$created} halaman baru, Diperbarui: {$updated} halaman yang sudah ada.";
    }

    /**
     * Get admin user ID for page creation.
     */
    private function getAdminUserId(): int
    {
        try {
            $adminUser = \App\Models\User::where('email', 'admin@smktelkom.sch.id')->first();
            return $adminUser?->id ?? 1;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * Define all static pages data.
     */
    private function getPageDefinitions(): array
    {
        return [
            [
                'title' => 'PP. Darul Ulum',
                'slug' => 'pp-darul-ulum',
                'category' => 'profil',
                'template' => 'about',
                'excerpt' => 'Tentang Pondok Pesantren Darul Ulum Jombang',
                'content' => $this->renderPpDarulUlam(),
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
                'content' => $this->renderVisiMisi(),
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
                'content' => $this->renderStrukturOrganisasi(),
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
                'content' => $this->renderTenagaPendidik(),
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
                'content' => $this->renderStafKaryawan(),
                'status' => 'published',
                'is_menu' => true,
                'menu_title' => 'Staf & Karyawan',
                'menu_sort_order' => 5,
            ],
        ];
    }

    private function renderPpDarulUlam(): string
    {
        return <<<'HTML'
<div class="row">
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
</div>
HTML;
    }

    private function renderVisiMisi(): string
    {
        return <<<'HTML'
<div class="row">
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
</div>
HTML;
    }

    private function renderStrukturOrganisasi(): string
    {
        return <<<'HTML'
<div class="row">
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
</div>
HTML;
    }

    private function renderTenagaPendidik(): string
    {
        return <<<'HTML'
<div class="row">
    <div class="col-lg-12">
        <h2>Tenaga Pendidik</h2>
        <p class="lead">SMK Telekomunikasi Darul Ulum memiliki tenaga pendidik yang berkualitas dan berkompeten di bidangnya masing-masing.</p>
    </div>
</div>
<div id="tenaga-pendidik-list" data-source="guru">
    <p class="text-muted">Daftar guru akan ditampilkan dari database. Hubungi sekolah untuk informasi terkini mengenai tenaga pendidik kami.</p>
</div>
HTML;
    }

    private function renderStafKaryawan(): string
    {
        return <<<'HTML'
<div class="row">
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
</div>
HTML;
    }
}
