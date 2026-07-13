<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Barang;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Events;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingControllerComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function telkom_landing_page_loads(): void
    {
        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewIs('telkom');
    }

    /** @test */
    public function maudu_landing_page_loads(): void
    {
        $response = $this->get('/maudu');

        $response->assertStatus(200);
        $response->assertViewIs('maudu');
    }

    /** @test */
    public function telkom_landing_has_siswa_count(): void
    {
        Siswa::factory()->count(25)->create();

        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('siswaCount');
        $this->assertEquals(25, $response->viewData('siswaCount'));
    }

    /** @test */
    public function telkom_landing_has_kelulusan_percentage(): void
    {
        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('kelulusanPercentage');
        $this->assertIsNumeric($response->viewData('kelulusanPercentage'));
    }

    /** @test */
    public function telkom_landing_has_testimonials(): void
    {
        Testimonial::factory()->count(3)->create(['status' => 'approved', 'is_featured' => true]);

        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('testimonials');
    }

    /** @test */
    public function telkom_landing_has_blogs(): void
    {
        Page::factory()->count(5)->create(['status' => 'published']);

        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('blogs');
    }

    /** @test */
    public function telkom_landing_has_partners(): void
    {
        Partner::factory()->count(4)->create(['is_active' => true]);

        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('partners');
    }

    /** @test */
    public function telkom_landing_has_events(): void
    {
        Events::factory()->count(3)->create(['status' => 'active']);

        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    /** @test */
    public function maudu_landing_has_required_data(): void
    {
        $response = $this->get('/maudu');

        $response->assertStatus(200);
        $response->assertViewHas('siswaCount');
        $response->assertViewHas('kelulusanPercentage');
        $response->assertViewHas('testimonials');
        $response->assertViewHas('blogs');
        $response->assertViewHas('partners');
        $response->assertViewHas('events');
    }

    /** @test */
    public function default_landing_route_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function telkom_landing_page_has_site_settings(): void
    {
        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('siteSettings');
    }

    /** @test */
    public function maudu_landing_page_has_site_settings(): void
    {
        $response = $this->get('/maudu');

        $response->assertStatus(200);
        $response->assertViewHas('siteSettings');
    }

    /** @test */
    public function telkom_landing_includes_instagram_posts(): void
    {
        $response = $this->get('/telkom');

        $response->assertStatus(200);
        $response->assertViewHas('instagramPosts');
    }
}
