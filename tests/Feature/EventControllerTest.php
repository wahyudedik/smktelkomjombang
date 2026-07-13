<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Events;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EventControllerTest extends TestCase
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
        $this->admin->givePermissionTo('events.create');
        $this->admin->givePermissionTo('events.view');
        $this->admin->givePermissionTo('events.edit');
        $this->admin->givePermissionTo('events.delete');
    }

    /** @test */
    public function admin_can_view_events_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
    }

    /** @test */
    public function events_index_displays_events(): void
    {
        Events::factory()->count(3)->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->get(route('admin.events.index'));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(3, $events);
    }

    /** @test */
    public function admin_can_search_events(): void
    {
        Events::factory()->create(['title' => 'Seminar Teknologi', 'status' => 'active']);
        Events::factory()->create(['title' => 'Festival Budaya', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->get(route('admin.events.index', ['search' => 'Teknologi']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(1, $events);
    }

    /** @test */
    public function admin_can_filter_events_by_status(): void
    {
        Events::factory()->create(['status' => 'active']);
        Events::factory()->create(['status' => 'inactive']);
        Events::factory()->create(['status' => 'archived']);

        $response = $this->actingAs($this->admin)->get(route('admin.events.index', ['status' => 'active']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(1, $events);
    }

    /** @test */
    public function admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.events.create'));

        $response->assertStatus(200);
        $response->assertViewIs('events.create');
    }

    /** @test */
    public function admin_can_store_event(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title' => 'Seminar AI',
            'description' => 'Seminar tentang kecerdasan buatan',
            'date' => now()->addDays(7)->format('Y-m-d'),
            'category' => 'seminar',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title' => 'Seminar AI',
            'category' => 'seminar',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function admin_can_store_event_with_image(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('event.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title' => 'Workshop IoT',
            'description' => 'Workshop Internet of Things',
            'date' => now()->addDays(14)->format('Y-m-d'),
            'category' => 'workshop',
            'status' => 'active',
            'image' => $image,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['title' => 'Workshop IoT']);
        Storage::disk('public')->assertExists('events/' . $image->hashName());
    }

    /** @test */
    public function admin_can_view_event(): void
    {
        $event = Events::factory()->create(['title' => 'Tech Conference']);

        $response = $this->actingAs($this->admin)->get(route('admin.events.show', $event));

        $response->assertStatus(200);
        $response->assertViewIs('events.show');
        $response->assertViewHas('event', $event);
    }

    /** @test */
    public function admin_can_view_edit_form(): void
    {
        $event = Events::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.events.edit', $event));

        $response->assertStatus(200);
        $response->assertViewIs('events.edit');
    }

    /** @test */
    public function admin_can_update_event(): void
    {
        $event = Events::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($this->admin)->put(route('admin.events.update', $event), [
            'title' => 'New Title',
            'description' => 'Updated description',
            'date' => now()->addDays(7)->format('Y-m-d'),
            'category' => 'updated',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'New Title']);
    }

    /** @test */
    public function admin_can_delete_event(): void
    {
        $event = Events::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.events.destroy', $event));

        $response->assertRedirect();
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /** @test */
    public function admin_can_search_events_by_category(): void
    {
        Events::factory()->create(['category' => 'seminar', 'status' => 'active']);
        Events::factory()->create(['category' => 'workshop', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->get(route('admin.events.index', ['category' => 'seminar']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(1, $events);
    }

    /** @test */
    public function store_event_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), []);

        $response->assertSessionHasErrors(['title', 'date']);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_events(): void
    {
        $event = Events::factory()->create();

        $response = $this->get(route('admin.events.index'));

        $response->assertRedirect('/login');
    }
}
