<?php

namespace Tests\Feature;

use App\Models\GuestBook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestBookPublicTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // PUBLIC FORM TESTS
    // ==========================================

    public function test_public_form_render(): void
    {
        $response = $this->get(route('guest-book.form'));

        $response->assertStatus(200);
    }

    public function test_public_check_in(): void
    {
        $response = $this->post(route('guest-book.store'), [
            'guest_name'     => 'Public Guest',
            'organization'   => 'SMA Negeri 1',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Informasi Pendaftaran',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify record created in database
        $this->assertDatabaseHas('guest_books', [
            'guest_name'     => 'Public Guest',
            'organization'   => 'SMA Negeri 1',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Informasi Pendaftaran',
            'status'         => 'check_in',
            'created_by'     => null, // No auth — self check-in
        ]);

        // Verify auto-generated fields
        $guest = GuestBook::where('guest_name', 'Public Guest')->first();
        $this->assertNotNull($guest->ticket_number);
        $this->assertStringStartsWith('BT-', $guest->ticket_number);
        $this->assertNotNull($guest->check_in_at);
    }

    public function test_public_check_in_validates_required_fields(): void
    {
        $response = $this->post(route('guest-book.store'), []);

        $response->assertSessionHasErrors(['guest_name', 'organization', 'visit_category', 'visit_purpose']);
    }

    public function test_public_check_in_validates_visit_category(): void
    {
        $response = $this->post(route('guest-book.store'), [
            'guest_name'     => 'Test',
            'organization'   => 'Test',
            'visit_category' => 'invalid_category',
            'visit_purpose'  => 'Test',
        ]);

        $response->assertSessionHasErrors(['visit_category']);
    }

    public function test_public_check_in_validates_email_format(): void
    {
        $response = $this->post(route('guest-book.store'), [
            'guest_name'     => 'Test',
            'organization'   => 'Test',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Test',
            'email'          => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_public_check_in_with_optional_fields(): void
    {
        $response = $this->post(route('guest-book.store'), [
            'guest_name'     => 'Full Guest',
            'nik'            => '3201234567890001',
            'phone'          => '081234567890',
            'email'          => 'full@example.com',
            'organization'   => 'PT Lengkap',
            'position'       => 'Direktur',
            'visit_category' => 'dinas',
            'visit_purpose'  => 'Kunjungan Dinas Resmi',
            'visit_target'   => 'Kepala Sekolah',
            'vehicle_type'   => 'Mobil',
            'vehicle_plate'  => 'B 1234 CD',
            'notes'          => 'Ada janji jam 10',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'guest_name'     => 'Full Guest',
            'nik'            => '3201234567890001',
            'phone'          => '081234567890',
            'email'          => 'full@example.com',
            'organization'   => 'PT Lengkap',
            'position'       => 'Direktur',
            'visit_category' => 'dinas',
            'visit_target'   => 'Kepala Sekolah',
            'vehicle_type'   => 'Mobil',
            'vehicle_plate'  => 'B 1234 CD',
        ]);
    }

    public function test_public_thank_you_page(): void
    {
        // Create a guest entry first
        $guest = GuestBook::create([
            'guest_name'     => 'Thank You Guest',
            'organization'   => 'SMK Test',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Test',
            'status'         => 'check_in',
        ]);

        $response = $this->get(route('guest-book.thank-you', $guest));

        $response->assertStatus(200);
    }

    public function test_public_check_in_does_not_require_auth(): void
    {
        // Verify no auth middleware — guest should be able to access
        $response = $this->get(route('guest-book.form'));
        $response->assertStatus(200); // Not redirected to /login
    }

    public function test_public_check_in_multiple_guests(): void
    {
        // First check-in
        $this->post(route('guest-book.store'), [
            'guest_name'     => 'Guest One',
            'organization'   => 'Org One',
            'visit_category' => 'ppdb',
            'visit_purpose'  => 'Purpose One',
        ]);

        // Second check-in
        $this->post(route('guest-book.store'), [
            'guest_name'     => 'Guest Two',
            'organization'   => 'Org Two',
            'visit_category' => 'dinas',
            'visit_purpose'  => 'Purpose Two',
        ]);

        $this->assertDatabaseHas('guest_books', ['guest_name' => 'Guest One']);
        $this->assertDatabaseHas('guest_books', ['guest_name' => 'Guest Two']);
        $this->assertEquals(2, GuestBook::count());
    }
}
