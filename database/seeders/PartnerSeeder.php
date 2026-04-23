<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Axioo',
                'description' => 'Kerjasama Kurikulum SMK dengan Industri Axioo',
                'logo' => 'axioo.png',
                'website' => 'https://www.axioo.com',
                'category' => 'Technology',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'GAMELAB Indonesia',
                'description' => 'Kerjasama Kurikulum SMK dengan Industri GAMELAB',
                'logo' => 'gamelab.png',
                'website' => 'https://www.gamelab.id',
                'category' => 'Game Development',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Lab PLTS',
                'description' => 'Praktik Pembangkit Listrik Tenaga Surya',
                'logo' => 'plts.png',
                'website' => '#',
                'category' => 'Energy',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Lab Fiber Optik',
                'description' => 'Praktik Pengkabel Fiber Optik',
                'logo' => 'fiber-optik.png',
                'website' => '#',
                'category' => 'Networking',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Studio Seje',
                'description' => 'Praktik Fotografi dan Videografi',
                'logo' => 'studio-seje.png',
                'website' => '#',
                'category' => 'Media',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }
    }
}
