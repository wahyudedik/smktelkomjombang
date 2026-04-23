<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelulusan;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\Partner;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Display the telkom landing page
     */
    public function telkom()
    {
        $data = [
            'siswaCount' => $this->getSiswaCount(),
            'kelulusanPercentage' => $this->getKelulusanPercentage(),
            'testimonials' => $this->getTestimonials(),
            'blogs' => $this->getBlogs(),
            'partners' => $this->getPartners(),
            'events' => $this->getEvents(),
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
}
