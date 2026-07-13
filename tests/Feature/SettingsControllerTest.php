<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
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
    public function admin_can_view_settings_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.index');
    }

    /** @test */
    public function admin_can_view_landing_page_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.landing-page'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.landing-page');
    }

    /** @test */
    public function admin_can_update_landing_page_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.landing-page.update'), [
            'site_name' => 'SMK Telekomunikasi Updated',
            'site_description' => 'Deskripsi baru',
            'site_keywords' => 'keywords baru',
            'hero_slide1_subtitle' => 'Subtitle baru',
            'hero_slide1_title' => 'Title baru',
            'hero_slide1_description' => 'Deskripsi slide 1',
            'hero_slide2_subtitle' => 'Slide 2 subtitle',
            'hero_slide2_title' => 'Slide 2 title',
            'hero_slide2_description' => 'Slide 2 desc',
            'hero_slide3_subtitle' => 'Slide 3 subtitle',
            'hero_slide3_title' => 'Slide 3 title',
            'hero_slide3_description' => 'Slide 3 desc',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('theme_settings', [
            'key' => 'site_name',
            'value' => 'SMK Telekomunikasi Updated',
        ]);
    }

    /** @test */
    public function admin_can_view_seo_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.seo'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.seo');
    }

    /** @test */
    public function admin_can_update_seo_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.seo.update'), [
            'seo_title' => 'SEO Title Updated',
            'seo_description' => 'SEO Description Updated',
            'seo_keywords' => 'seo, keywords, updated',
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_view_data_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.data-management'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.data-management');
    }

    /** @test */
    public function admin_can_view_kelas_jurusan(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.kelas-jurusan'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.kelas-jurusan');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function landing_page_settings_returns_correct_view(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.landing-page'));

        $response->assertStatus(200);
        $response->assertViewHas('settings');
    }
}
