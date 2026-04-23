<?php

namespace Database\Seeders;

use App\Models\Events;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'SKATELDU 2026',
                'description' => 'Acara tahunan SMK Telekomunikasi Darul Ulum yang menampilkan berbagai kegiatan seru dan edukatif',
                'date' => now()->addDays(30),
                'category' => 'Acara Sekolah',
                'image' => 'event/1.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Lomba Inovasi Teknologi',
                'description' => 'Kompetisi inovasi teknologi untuk siswa dengan hadiah menarik',
                'date' => now()->addDays(45),
                'category' => 'Kompetisi',
                'image' => 'event/2.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Workshop Industri 4.0',
                'description' => 'Workshop tentang perkembangan industri 4.0 dan transformasi digital',
                'date' => now()->addDays(60),
                'category' => 'Workshop',
                'image' => 'event/3.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Seminar Karir dan Beasiswa',
                'description' => 'Seminar tentang peluang karir dan program beasiswa untuk siswa',
                'date' => now()->addDays(75),
                'category' => 'Seminar',
                'image' => 'event/4.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($events as $event) {
            Events::updateOrCreate(
                ['title' => $event['title']],
                $event
            );
        }
    }
}

