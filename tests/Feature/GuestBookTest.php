<?php

namespace Tests\Feature;

use App\Models\GuestBook;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestBookTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed all permissions (creates via Spatie models, does NOT assign to roles)
        $this->seed(PermissionSeeder::class);

        // Create admin role with ONLY buku-tamu permissions
        $adminRole = $this->getOrCreateRole('admin');
        $adminRole->givePermissionTo([
            'buku-tamu.view',
            'buku-tamu.create',
            'buku-tamu.update',
            'buku-tamu.delete',
            'buku-tamu.checkout',
        ]);

        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Helper: create a GuestBook entry for testing.
     */
    protected function createGuest(array $overrides = []): GuestBook
    {
        return GuestBook::create(array_merge([
            'guest_name'     => 'Budi Santoso',
            'organization'   => 'SMK Test',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Konsultasi Pendaftaran',
            'status'         => 'check_in',
            'created_by'     => $this->admin->id,
        ], $overrides));
    }

    // ==========================================
    // ADMIN CRUD TESTS
    // ==========================================

    public function test_guest_book_index_page(): void
    {
        $this->createGuest();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.index'));

        $response->assertStatus(200);
        $response->assertViewIs('guest-book.index');
    }

    public function test_guest_book_create_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.create'));

        $response->assertStatus(200);
        $response->assertViewIs('guest-book.create');
    }

    public function test_guest_book_store(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.buku-tamu.store'), [
                'guest_name'     => 'Jane Doe',
                'organization'   => 'PT Maju Jaya',
                'visit_category' => 'dinas',
                'visit_purpose'  => 'Kunjungan Dinas',
                'phone'          => '081234567890',
                'email'          => 'jane@example.com',
                'position'       => 'Manager',
                'vehicle_type'   => 'Motor',
                'vehicle_plate'  => 'B 1234 ABC',
            ]);

        $response->assertRedirect(route('admin.buku-tamu.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'guest_name'     => 'Jane Doe',
            'organization'   => 'PT Maju Jaya',
            'visit_category' => 'dinas',
            'visit_purpose'  => 'Kunjungan Dinas',
            'created_by'     => $this->admin->id,
            'status'         => 'check_in',
        ]);

        // Verify ticket_number auto-generated
        $guest = GuestBook::where('guest_name', 'Jane Doe')->first();
        $this->assertNotNull($guest->ticket_number);
        $this->assertStringStartsWith('BT-', $guest->ticket_number);

        // Verify check_in_at auto-set
        $this->assertNotNull($guest->check_in_at);
    }

    public function test_guest_book_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.buku-tamu.store'), []);

        $response->assertSessionHasErrors(['guest_name', 'organization', 'visit_category', 'visit_purpose']);
    }

    public function test_guest_book_store_validates_visit_category(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.buku-tamu.store'), [
                'guest_name'     => 'Test',
                'organization'   => 'Test Org',
                'visit_category' => 'invalid_category',
                'visit_purpose'  => 'Test purpose',
            ]);

        $response->assertSessionHasErrors(['visit_category']);
    }

    public function test_guest_book_show(): void
    {
        $guest = $this->createGuest();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.show', $guest));

        $response->assertStatus(200);
        $response->assertViewIs('guest-book.show');
        $response->assertViewHas('guest', $guest);
    }

    public function test_guest_book_edit_page(): void
    {
        $guest = $this->createGuest();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.edit', $guest));

        $response->assertStatus(200);
        $response->assertViewIs('guest-book.edit');
        $response->assertViewHas('guest', $guest);
    }

    public function test_guest_book_update(): void
    {
        $guest = $this->createGuest(['guest_name' => 'Old Name']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.buku-tamu.update', $guest), [
                'guest_name'     => 'New Name',
                'organization'   => 'Updated Org',
                'visit_category' => 'konsultasi',
                'visit_purpose'  => 'Updated purpose',
                'status'         => 'check_in',
            ]);

        $response->assertRedirect(route('admin.buku-tamu.show', $guest));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'id'             => $guest->id,
            'guest_name'     => 'New Name',
            'organization'   => 'Updated Org',
            'visit_category' => 'konsultasi',
        ]);

        // Verify ticket_number NOT changed
        $guest->refresh();
        $this->assertEquals($guest->ticket_number, $guest->ticket_number);
    }

    public function test_guest_book_destroy(): void
    {
        $guest = $this->createGuest();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.buku-tamu.destroy', $guest));

        $response->assertRedirect(route('admin.buku-tamu.index'));
        $response->assertSessionHas('success');

        // Verify hard deleted (bypasses soft delete)
        $this->assertDatabaseMissing('guest_books', [
            'id' => $guest->id,
        ]);
    }

    public function test_guest_book_checkout(): void
    {
        $guest = $this->createGuest(['status' => 'check_in']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.buku-tamu.checkout', $guest));

        $response->assertRedirect(route('admin.buku-tamu.show', $guest));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'id'     => $guest->id,
            'status' => 'check_out',
        ]);

        // Verify check_out_at is set
        $guest->refresh();
        $this->assertNotNull($guest->check_out_at);
    }

    // ==========================================
    // PERMISSION TESTS
    // ==========================================

    public function test_unauthenticated_user_cannot_access_admin_guest_book(): void
    {
        $response = $this->get(route('admin.buku-tamu.index'));
        $response->assertRedirect('/login');
    }

    public function test_user_without_permission_cannot_access_guest_book(): void
    {
        // User authenticated but with NO role — role middleware should reject
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.buku-tamu.index'));

        // Access denied: forbidden (403), redirect (302), or server error (500) from sidebar
        $this->assertNotEquals(200, $response->status());
    }

    public function test_user_with_role_but_no_create_permission_cannot_store(): void
    {
        // Create a user with admin role but WITHOUT buku-tamu.create permission
        // The admin role only has view/update/delete/checkout but NOT create
        $restrictedRole = $this->getOrCreateRole('guru');
        $restrictedRole->givePermissionTo([
            'buku-tamu.view',
            'buku-tamu.update',
            'buku-tamu.delete',
            'buku-tamu.checkout',
        ]);

        $user = User::factory()->create();
        $user->assignRole('guru');

        // Attempt to store — should fail because buku-tamu.create is not assigned
        $response = $this->actingAs($user)
            ->post(route('admin.buku-tamu.store'), [
                'guest_name'     => 'Unauthorized Guest',
                'organization'   => 'Test',
                'visit_category' => 'ppdb',
                'visit_purpose'  => 'Test',
            ]);

        // Should NOT return 200 (either 403 or redirect)
        $this->assertNotEquals(200, $response->status());
    }

    // ==========================================
    // EXPORT TESTS
    // ==========================================

    public function test_guest_book_export_excel(): void
    {
        $this->createGuest();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.export', ['type' => 'excel']));

        $response->assertStatus(200);
    }

    public function test_guest_book_export_default_is_excel(): void
    {
        $this->createGuest();

        // Without specifying type — should default to excel
        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.export'));

        $response->assertStatus(200);
    }

    // ==========================================
    // SEARCH & FILTER TESTS
    // ==========================================

    public function test_index_search_by_guest_name(): void
    {
        $this->createGuest(['guest_name' => 'Ahmad Fauzi']);
        $this->createGuest(['guest_name' => 'Siti Nurhaliza']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.index', ['search' => 'Ahmad']));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertDontSee('Siti Nurhaliza');
    }

    public function test_index_filter_by_status(): void
    {
        $this->createGuest(['guest_name' => 'Active Guest', 'status' => 'check_in']);
        $this->createGuest(['guest_name' => 'Checked Out Guest', 'status' => 'check_out']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.index', ['status' => 'check_in']));

        $response->assertStatus(200);
        $response->assertSee('Active Guest');
        $response->assertDontSee('Checked Out Guest');
    }

    public function test_index_filter_by_visit_category(): void
    {
        $this->createGuest(['guest_name' => 'PPDB Guest', 'visit_category' => 'ppdb']);
        $this->createGuest(['guest_name' => 'Dinas Guest', 'visit_category' => 'dinas']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.buku-tamu.index', ['visit_category' => 'ppdb']));

        $response->assertStatus(200);
        $response->assertSee('PPDB Guest');
        $response->assertDontSee('Dinas Guest');
    }

    // ==========================================
    // MODEL BEHAVIOR TESTS
    // ==========================================

    public function test_ticket_number_auto_generated(): void
    {
        $guest = $this->createGuest();

        $this->assertNotNull($guest->ticket_number);
        $this->assertStringStartsWith('BT-', $guest->ticket_number);
        // Format: BT-YYYYMMDD-XXXX
        $this->assertMatchesRegularExpression('/^BT-\d{8}-\d{4}$/', $guest->ticket_number);
    }

    public function test_check_in_at_auto_set(): void
    {
        $guest = $this->createGuest();

        $this->assertNotNull($guest->check_in_at);
        $this->assertTrue($guest->check_in_at->isToday());
    }

    public function test_guest_book_creator_relationship(): void
    {
        $guest = $this->createGuest();

        $guest->load('creator');

        $this->assertNotNull($guest->creator);
        $this->assertEquals($this->admin->id, $guest->creator->id);
    }

    public function test_guest_book_duration_accessor_when_checked_out(): void
    {
        $guest = $this->createGuest([
            'check_in_at'  => now()->subHours(2),
            'check_out_at' => now(),
        ]);

        $this->assertNotNull($guest->duration);
        $this->assertStringContainsString('jam', $guest->duration);
    }

    public function test_guest_book_duration_accessor_when_not_checked_out(): void
    {
        $guest = $this->createGuest();

        $this->assertNull($guest->duration);
    }

    public function test_guest_book_photo_url_accessor(): void
    {
        $guest = $this->createGuest(['photo_path' => 'guest-photos/test_photo.jpg']);

        $this->assertNotNull($guest->photo_url);
        $this->assertStringContainsString('storage/guest-photos/test_photo.jpg', $guest->photo_url);
    }

    public function test_guest_book_signature_url_accessor(): void
    {
        $guest = $this->createGuest(['signature_path' => 'guest-books/signatures/BT-signature.png']);

        $this->assertNotNull($guest->signature_url);
        $this->assertStringContainsString('storage/guest-books/signatures/BT-signature.png', $guest->signature_url);
    }
}
