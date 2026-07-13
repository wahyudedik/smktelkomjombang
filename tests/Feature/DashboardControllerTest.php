<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Barang;
use App\Models\Page;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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
    public function admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboards.admin');
    }

    /** @test */
    public function dashboard_displays_statistics(): void
    {
        // Create some test data
        Siswa::factory()->count(3)->create();
        Guru::factory()->count(2)->create();
        Barang::factory()->count(5)->create();
        Page::factory()->count(2)->create(['status' => 'published']);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistics');
        $response->assertViewHas('recentActivities');
        $response->assertViewHas('moduleUsage');
        $response->assertViewHas('userGrowth');
    }

    /** @test */
    public function dashboard_includes_model_counts(): void
    {
        Siswa::factory()->count(10)->create();
        Guru::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $stats = $response->viewData('statistics');
        $this->assertEquals(10, $stats['siswa_count']);
        $this->assertEquals(5, $stats['guru_count']);
    }

    /** @test */
    public function dashboard_includes_recent_activities(): void
    {
        // Create audit log
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'create',
            'auditable_type' => 'App\\Models\\Siswa',
            'auditable_id' => 1,
            'old_values' => null,
            'new_values' => ['name' => 'Test'],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $activities = $response->viewData('recentActivities');
        $this->assertCount(1, $activities);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function dashboard_includes_module_usage(): void
    {
        Siswa::factory()->count(5)->create();
        Guru::factory()->count(3)->create();
        Calon::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $moduleUsage = $response->viewData('moduleUsage');
        $this->assertIsArray($moduleUsage);
    }

    /** @test */
    public function dashboard_includes_user_growth(): void
    {
        // Create users with different creation dates
        User::factory()->count(3)->create(['created_at' => now()->subDays(30)]);
        User::factory()->count(2)->create(['created_at' => now()]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $userGrowth = $response->viewData('userGrowth');
        $this->assertIsArray($userGrowth);
    }
}
