<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $adminRole = $this->getOrCreateRole('admin');
        $this->admin->syncRoles([$adminRole]);
    }

    /** @test */
    public function admin_can_view_pages_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pages.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.admin');
    }

    /** @test */
    public function admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pages.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.create');
    }

    /** @test */
    public function admin_can_store_page(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Tentang Kami',
            'content' => '<p>Ini adalah halaman tentang kami</p>',
            'template' => 'about',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'status' => 'draft',
        ]);
    }

    /** @test */
    public function admin_can_store_published_page(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Berita Terbaru',
            'content' => '<p>Konten berita</p>',
            'template' => 'blog',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'title' => 'Berita Terbaru',
            'status' => 'published',
        ]);
    }

    /** @test */
    public function admin_can_view_page(): void
    {
        $page = Page::factory()->create(['title' => 'Detail Page']);

        $response = $this->actingAs($this->admin)->get(route('admin.pages.show', $page));

        $response->assertStatus(200);
        $response->assertViewIs('pages.show');
    }

    /** @test */
    public function admin_can_view_edit_form(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.pages.edit', $page));

        $response->assertStatus(200);
        $response->assertViewIs('pages.edit');
    }

    /** @test */
    public function admin_can_update_page(): void
    {
        $page = Page::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($this->admin)->put(route('admin.pages.update', $page), [
            'title' => 'New Title',
            'content' => '<p>Updated content</p>',
            'template' => 'default',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'title' => 'New Title']);
    }

    /** @test */
    public function admin_can_delete_page(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.pages.destroy', $page));

        $response->assertRedirect();
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    /** @test */
    public function admin_can_publish_page(): void
    {
        $page = Page::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($this->admin)->post(route('admin.pages.publish', $page));

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'status' => 'published']);
    }

    /** @test */
    public function admin_can_unpublish_page(): void
    {
        $page = Page::factory()->create(['status' => 'published']);

        $response = $this->actingAs($this->admin)->post(route('admin.pages.unpublish', $page));

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'status' => 'draft']);
    }

    /** @test */
    public function admin_can_duplicate_page(): void
    {
        $page = Page::factory()->create(['title' => 'Original Page', 'content' => '<p>Content</p>']);

        $response = $this->actingAs($this->admin)->post(route('admin.pages.duplicate', $page));

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', ['title' => 'Original Page']);
        $this->assertEquals(2, Page::where('title', 'Original Page')->count());
    }

    /** @test */
    public function store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pages.store'), []);

        $response->assertSessionHasErrors(['title', 'content', 'template', 'status']);
    }

    /** @test */
    public function public_can_view_published_pages(): void
    {
        $page = Page::factory()->create([
            'status' => 'published',
            'slug' => 'public-page',
        ]);

        $response = $this->get('/pages/' . $page->slug);

        $response->assertStatus(200);
    }

    /** @test */
    public function public_cannot_view_draft_pages(): void
    {
        $page = Page::factory()->create([
            'status' => 'draft',
            'slug' => 'draft-page',
        ]);

        $response = $this->get('/pages/' . $page->slug);

        $response->assertStatus(404);
    }

    /** @test */
    public function admin_can_view_page_versions(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.pages.versions', $page));

        $response->assertStatus(200);
        $response->assertViewIs('pages.versions');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_pages(): void
    {
        $page = Page::factory()->create();

        $response = $this->get(route('admin.pages.index'));

        $response->assertRedirect('/login');
    }
}
