<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn($q) => $q->whereIn('name', ['superadmin', 'admin']))->first();
        $userId = $admin?->id ?? 1;

        $beritas = [
            [
                'title'       => 'Siswa SMK Telkom Raih Juara 1 Lomba Desain Grafis Nasional',
                'excerpt'     => 'Tim DKV SMK Telekomunikasi Darul Ulum berhasil meraih juara pertama dalam kompetisi desain grafis tingkat nasional yang diselenggarakan di Jakarta.',
                'content'     => "Tim siswa jurusan Desain Komunikasi Visual (DKV) SMK Telekomunikasi Darul Ulum Jombang berhasil menorehkan prestasi membanggakan dengan meraih Juara 1 dalam Lomba Desain Grafis Nasional 2026.\n\nKompetisi yang diikuti oleh lebih dari 200 tim dari seluruh Indonesia ini berlangsung selama dua hari di Jakarta Convention Center. Tim SMK Telkom berhasil mengalahkan pesaing dari berbagai sekolah unggulan di Indonesia.\n\nKepala Sekolah Nur Laila, S.Pd menyampaikan rasa bangga dan apresiasinya kepada seluruh siswa yang telah berjuang keras. \"Ini adalah bukti nyata bahwa siswa-siswa kita memiliki kemampuan yang tidak kalah dengan sekolah-sekolah terbaik di Indonesia,\" ujarnya.\n\nPrestasi ini diharapkan dapat menjadi motivasi bagi seluruh siswa SMK Telkom untuk terus berprestasi dan mengharumkan nama sekolah.",
                'is_featured' => true,
                'status'      => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title'       => 'SMK Telkom Jalin Kerjasama dengan PT. Telkom Indonesia',
                'excerpt'     => 'Penandatanganan MoU antara SMK Telekomunikasi Darul Ulum dengan PT. Telkom Indonesia untuk program magang dan rekrutmen langsung.',
                'content'     => "SMK Telekomunikasi Darul Ulum Jombang resmi menjalin kerjasama strategis dengan PT. Telkom Indonesia melalui penandatanganan Memorandum of Understanding (MoU) yang berlangsung di aula sekolah.\n\nKerjasama ini mencakup program magang bagi siswa kelas XI dan XII, rekrutmen langsung bagi lulusan terbaik, serta pengembangan kurikulum berbasis industri telekomunikasi.\n\nDirektur SDM PT. Telkom Indonesia menyatakan bahwa SMK Telkom Darul Ulum merupakan salah satu sekolah mitra terpercaya yang menghasilkan lulusan berkualitas tinggi.\n\nProgram ini akan mulai berjalan pada semester genap tahun ajaran 2025/2026 dan diharapkan dapat memberikan manfaat nyata bagi siswa dalam mempersiapkan karir di dunia industri.",
                'is_featured' => false,
                'status'      => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title'       => 'Workshop Kecerdasan Buatan untuk Siswa SMK Telkom',
                'excerpt'     => 'Ratusan siswa mengikuti workshop AI dan Machine Learning yang diselenggarakan bersama komunitas teknologi Jombang.',
                'content'     => "Sebanyak 150 siswa SMK Telekomunikasi Darul Ulum mengikuti Workshop Kecerdasan Buatan (Artificial Intelligence) yang diselenggarakan bekerja sama dengan komunitas teknologi Jombang Tech Community.\n\nWorkshop yang berlangsung selama dua hari ini memperkenalkan siswa pada konsep dasar AI, Machine Learning, dan penerapannya dalam kehidupan sehari-hari.\n\nPara peserta mendapatkan kesempatan untuk langsung mempraktikkan pembuatan model AI sederhana menggunakan Python dan berbagai tools modern.\n\nAntusiasme siswa sangat tinggi, terbukti dari banyaknya pertanyaan dan diskusi yang berlangsung selama workshop. Kegiatan ini merupakan bagian dari program peningkatan kompetensi digital siswa SMK Telkom.",
                'is_featured' => false,
                'status'      => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title'       => 'Penerimaan Peserta Didik Baru 2026/2027 Resmi Dibuka',
                'excerpt'     => 'SMK Telekomunikasi Darul Ulum membuka pendaftaran siswa baru untuk tahun ajaran 2026/2027 dengan kuota terbatas.',
                'content'     => "SMK Telekomunikasi Darul Ulum Jombang resmi membuka Penerimaan Peserta Didik Baru (PPDB) untuk tahun ajaran 2026/2027. Pendaftaran dapat dilakukan secara online melalui website psb.ponpesdarululum.id.\n\nTersedia empat jurusan unggulan:\n- Produksi Film\n- Desain Komunikasi Visual (DKV)\n- Teknik Komputer dan Jaringan (TKJ)\n- Rekayasa Perangkat Lunak (RPL)\n\nPendaftaran dibuka mulai 1 Mei hingga 30 Juni 2026. Calon siswa dapat mendaftar secara online 24 jam atau datang langsung ke kantor pusat Pondok Pesantren Darul Ulum Jombang pada hari Sabtu-Kamis pukul 08.00-16.00 WIB.\n\nInformasi lebih lanjut dapat menghubungi 085649400339 atau email smktelkomdujbg@gmail.com.",
                'is_featured' => false,
                'status'      => 'published',
                'published_at' => now()->subDays(15),
            ],
            [
                'title'       => 'Tim Robotik SMK Telkom Wakili Jawa Timur di Kompetisi Nasional',
                'excerpt'     => 'Tim robotik SMK Telkom berhasil lolos seleksi provinsi dan akan mewakili Jawa Timur dalam kompetisi robotik nasional.',
                'content'     => "Tim robotik SMK Telekomunikasi Darul Ulum berhasil meraih tiket ke kompetisi robotik nasional setelah memenangkan seleksi tingkat Jawa Timur yang berlangsung di Surabaya.\n\nTim yang terdiri dari 5 siswa jurusan TKJ dan RPL ini berhasil mengungguli 45 tim lainnya dari berbagai sekolah di Jawa Timur.\n\nRobot yang mereka ciptakan mampu menyelesaikan berbagai tantangan dengan presisi tinggi, termasuk navigasi otomatis dan pengenalan objek menggunakan kamera.\n\nKompetisi nasional akan berlangsung di Bandung pada bulan Agustus 2026. Seluruh warga SMK Telkom memberikan dukungan penuh kepada tim untuk meraih prestasi terbaik.",
                'is_featured' => false,
                'status'      => 'published',
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($beritas as $data) {
            $slug = Str::slug($data['title']);
            $original = $slug;
            $count = 1;
            while (Page::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $count++;
            }

            Page::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug'     => $slug,
                    'category' => 'berita',
                    'user_id'  => $userId,
                ])
            );
        }

        $this->command->info('Berita seeder completed: ' . count($beritas) . ' articles created.');
    }
}
