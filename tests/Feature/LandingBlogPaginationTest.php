<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Tests for the landing page blog section (MAUDU theme):
 * - view count rendered in card meta row
 * - functional pagination (?page=N) with different data per page
 * - pagination hidden when there is only one page
 */
class LandingBlogPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Force MAUDU theme deterministically (independent from .env).
        config(['app.default_theme' => 'maudu']);
    }

    /**
     * Create $count published berita pages, newest first (published_at desc, id desc order).
     */
    private function createPublishedBerita(int $count, int $views = 0): Collection
    {
        return collect(range(1, $count))->map(function (int $i) use ($views) {
            return Page::factory()->create([
                'category'    => 'berita',
                'status'      => 'published',
                'title'       => 'Berita Uji ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'published_at' => now()->subMinutes($i),
                'views_count' => $views,
                'user_id'     => User::factory(),
            ]);
        });
    }

    public function test_landing_page_renders_blog_section_with_view_count(): void
    {
        $this->createPublishedBerita(3, 123);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Berita Uji 01');

        // View count rendered in card meta row with accessible label.
        $response->assertSee('fa-eye');
        $response->assertSee('aria-label="123 kali dilihat"', false);

        // Fewer than 6 items => single page => pagination controls must not render.
        $response->assertDontSee('?page=2', false);
    }

    public function test_landing_page_pagination_shows_different_data_per_page(): void
    {
        $this->createPublishedBerita(8);

        // Page 1 (default, no param): 6 newest items + link to page 2.
        $page1 = $this->get('/');
        $page1->assertStatus(200);
        $page1->assertSee('Berita Uji 01');
        $page1->assertSee('Berita Uji 06');
        $page1->assertDontSee('Berita Uji 07');
        $page1->assertSee('?page=2', false);

        // Page 2 (?page=2): remaining 2 oldest items — data must differ from page 1.
        $page2 = $this->get('/?page=2');
        $page2->assertStatus(200);
        $page2->assertSee('Berita Uji 07');
        $page2->assertSee('Berita Uji 08');
        $page2->assertDontSee('Berita Uji 01');
        $page2->assertDontSee('Berita Uji 06');
    }

    public function test_landing_page_out_of_range_page_renders_without_error(): void
    {
        $this->createPublishedBerita(8);

        $response = $this->get('/?page=99');

        $response->assertStatus(200);
        // No cards on out-of-range page, but no dummy fallback either (total > 0).
        $response->assertDontSee('Berita Uji 01');
    }
}
