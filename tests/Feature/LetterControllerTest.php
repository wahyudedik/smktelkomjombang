<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\LetterFormat;
use App\Models\LetterFormatSegment;
use App\Models\LetterActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LetterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected LetterFormat $letterFormat;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role & permission
        $adminRole = $this->getOrCreateRole('admin');
        $this->getOrCreatePermission('surat.in.view');
        $this->getOrCreatePermission('surat.in.create');
        $this->getOrCreatePermission('surat.out.view');
        $this->getOrCreatePermission('surat.out.create');
        $this->getOrCreatePermission('surat.out.upload');
        $this->getOrCreatePermission('surat.out.print');

        // Create admin user with all letter permissions
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->admin->givePermissionTo([
            'surat.in.view',
            'surat.in.create',
            'surat.out.view',
            'surat.out.create',
            'surat.out.upload',
            'surat.out.print',
        ]);

        // Create a letter format for outgoing letters
        $this->letterFormat = LetterFormat::create([
            'name' => 'Surat Undangan',
            'description' => 'Format surat undangan',
            'type' => 'out',
            'period_mode' => 'year',
            'counter_scope' => 'global',
            'is_active' => true,
        ]);

        // Create format segments: text "/" + sequence + text "/UND/" + year
        LetterFormatSegment::create([
            'letter_format_id' => $this->letterFormat->id,
            'type' => 'text',
            'value' => '/',
            'padding' => null,
            'order' => 1,
        ]);
        LetterFormatSegment::create([
            'letter_format_id' => $this->letterFormat->id,
            'type' => 'sequence',
            'value' => null,
            'padding' => 3,
            'order' => 2,
        ]);
        LetterFormatSegment::create([
            'letter_format_id' => $this->letterFormat->id,
            'type' => 'text',
            'value' => '/UND/',
            'padding' => null,
            'order' => 3,
        ]);
        LetterFormatSegment::create([
            'letter_format_id' => $this->letterFormat->id,
            'type' => 'year',
            'value' => null,
            'padding' => null,
            'order' => 4,
        ]);
    }

    // ==========================================
    // SURAT MASUK (INCOMING) TESTS
    // ==========================================

    public function test_admin_can_view_incoming_letter_index(): void
    {
        Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/2026/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas Pendidikan',
            'subject' => 'Surat Edaran Libur',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.in.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.in.index');
    }

    public function test_incoming_letter_index_shows_only_incoming(): void
    {
        // Create incoming letter
        Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas Pendidikan',
            'subject' => 'Surat Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        // Create outgoing letter
        Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.in.index'));

        $response->assertStatus(200);
        $response->assertSee('Dinas Pendidikan');
        $response->assertDontSee('Orang Tua Siswa');
    }

    public function test_unauthenticated_user_cannot_view_incoming_letters(): void
    {
        $response = $this->get(route('admin.letters.in.index'));
        $response->assertRedirect('/login');
    }

    public function test_user_without_permission_cannot_view_incoming_letters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.letters.in.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_create_incoming_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.in.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.in.create');
    }

    public function test_admin_can_store_incoming_letter(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.in.store'), [
                'reference_number' => 'REF/2026/001',
                'letter_date' => '2026-01-15',
                'sender' => 'Dinas Pendidikan',
                'subject' => 'Surat Edaran Libur',
                'description' => 'Surat edaran tentang jadwal libur semester',
            ]);

        $response->assertRedirect(route('admin.letters.in.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('letters', [
            'type' => 'incoming',
            'reference_number' => 'REF/2026/001',
            'sender' => 'Dinas Pendidikan',
            'subject' => 'Surat Edaran Libur',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        // Check activity log was created
        $letter = Letter::where('reference_number', 'REF/2026/001')->first();
        $this->assertDatabaseHas('letter_activity_logs', [
            'letter_id' => $letter->id,
            'action' => 'created',
        ]);
    }

    public function test_admin_can_store_incoming_letter_with_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.in.store'), [
                'reference_number' => 'REF/2026/002',
                'letter_date' => '2026-01-20',
                'sender' => 'Kementerian Pendidikan',
                'subject' => 'Kurikulum Baru',
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.letters.in.index'));

        $letter = Letter::where('reference_number', 'REF/2026/002')->first();
        $this->assertNotNull($letter->file_path);
        $this->assertStringStartsWith('letters/incoming/', $letter->file_path);
    }

    public function test_store_incoming_letter_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.in.store'), []);

        $response->assertSessionHasErrors(['reference_number', 'letter_date', 'sender', 'subject']);
    }

    public function test_store_incoming_letter_validates_file_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/exe');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.in.store'), [
                'reference_number' => 'REF/2026/003',
                'letter_date' => '2026-01-20',
                'sender' => 'Test',
                'subject' => 'Test',
                'file' => $file,
            ]);

        $response->assertSessionHasErrors(['file']);
    }

    public function test_admin_can_show_incoming_letter(): void
    {
        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/2026/010',
            'letter_date' => '2026-03-10',
            'sender' => 'Kantor Kecamatan',
            'subject' => 'Undangan Musyawarah',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.in.show', $letter));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.in.show');
        $response->assertSee('Kantor Kecamatan');
    }

    public function test_show_incoming_letter_aborts_404_for_outgoing(): void
    {
        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.in.show', $letter));

        $response->assertStatus(404);
    }

    // ==========================================
    // SURAT KELUAR (OUTGOING) TESTS
    // ==========================================

    public function test_admin_can_view_outgoing_letter_index(): void
    {
        Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.out.index');
    }

    public function test_outgoing_letter_index_shows_only_outgoing(): void
    {
        // Create incoming
        Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas',
            'subject' => 'Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        // Create outgoing
        Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.index'));

        $response->assertStatus(200);
        $response->assertSee('Orang Tua Siswa');
        $response->assertDontSee('Dinas');
    }

    public function test_unauthenticated_user_cannot_view_outgoing_letters(): void
    {
        $response = $this->get(route('admin.letters.out.index'));
        $response->assertRedirect('/login');
    }

    public function test_user_without_permission_cannot_view_outgoing_letters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.letters.out.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_create_outgoing_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.out.create');
    }

    public function test_create_outgoing_form_shows_active_formats(): void
    {
        // Create inactive format
        LetterFormat::create([
            'name' => 'Inactive Format',
            'type' => 'out',
            'period_mode' => 'year',
            'counter_scope' => 'global',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.create'));

        $response->assertStatus(200);
        $response->assertSee('Surat Undangan');
        // Inactive format should not be passed to view (filtered in controller)
    }

    public function test_admin_can_store_outgoing_letter(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.store'), [
                'letter_format_id' => $this->letterFormat->id,
                'letter_date' => '2026-06-15',
                'recipient' => 'Kepala Dinas Pendidikan',
                'subject' => 'Laporan Tahunan',
                'description' => 'Laporan kegiatan sekolah tahun ajaran 2025/2026',
            ]);

        $response->assertRedirect(route('admin.letters.out.index'));
        $response->assertSessionHas('success');

        $letter = Letter::where('recipient', 'Kepala Dinas Pendidikan')->first();
        $this->assertNotNull($letter);
        $this->assertEquals('outgoing', $letter->type);
        $this->assertEquals('draft', $letter->status);
        $this->assertEquals(1, $letter->sequence_number);
        $this->assertNotNull($letter->letter_number);

        // Check activity log
        $this->assertDatabaseHas('letter_activity_logs', [
            'letter_id' => $letter->id,
            'action' => 'created',
        ]);
    }

    public function test_store_outgoing_letter_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.store'), []);

        $response->assertSessionHasErrors(['letter_format_id', 'letter_date', 'recipient', 'subject']);
    }

    public function test_store_outgoing_letter_validates_format_exists(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.store'), [
                'letter_format_id' => 99999,
                'letter_date' => '2026-06-15',
                'recipient' => 'Test',
                'subject' => 'Test',
            ]);

        $response->assertSessionHasErrors(['letter_format_id']);
    }

    public function test_outgoing_letter_sequence_number_increments(): void
    {
        // Create first letter
        $this->actingAs($this->admin)
            ->post(route('admin.letters.out.store'), [
                'letter_format_id' => $this->letterFormat->id,
                'letter_date' => '2026-06-15',
                'recipient' => 'Recipient 1',
                'subject' => 'Subject 1',
            ]);

        // Create second letter
        $this->actingAs($this->admin)
            ->post(route('admin.letters.out.store'), [
                'letter_format_id' => $this->letterFormat->id,
                'letter_date' => '2026-06-16',
                'recipient' => 'Recipient 2',
                'subject' => 'Subject 2',
            ]);

        $letter1 = Letter::where('recipient', 'Recipient 1')->first();
        $letter2 = Letter::where('recipient', 'Recipient 2')->first();

        $this->assertEquals(1, $letter1->sequence_number);
        $this->assertEquals(2, $letter2->sequence_number);
    }

    public function test_admin_can_show_outgoing_letter(): void
    {
        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.show', $letter));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.out.show');
        $response->assertSee('Orang Tua Siswa');
    }

    public function test_show_outgoing_letter_aborts_404_for_incoming(): void
    {
        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas',
            'subject' => 'Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.show', $letter));

        $response->assertStatus(404);
    }

    public function test_admin_can_upload_scan_for_outgoing_letter(): void
    {
        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.upload', $letter));

        $response->assertStatus(200);
        $response->assertViewIs('admin.letters.out.upload');
    }

    public function test_admin_can_process_upload_for_outgoing_letter(): void
    {
        Storage::fake('public');

        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $file = UploadedFile::fake()->create('scan-surat.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.upload.process', $letter), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.letters.out.index'));
        $response->assertSessionHas('success');

        $letter->refresh();
        $this->assertEquals('sent', $letter->status);
        $this->assertNotNull($letter->file_path);
        $this->assertStringStartsWith('letters/outgoing/', $letter->file_path);

        // Check activity log
        $this->assertDatabaseHas('letter_activity_logs', [
            'letter_id' => $letter->id,
            'action' => 'uploaded',
        ]);
    }

    public function test_upload_requires_file(): void
    {
        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.upload.process', $letter), []);

        $response->assertSessionHasErrors(['file']);
    }

    public function test_upload_validates_file_type(): void
    {
        Storage::fake('public');

        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/exe');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.upload.process', $letter), [
                'file' => $file,
            ]);

        $response->assertSessionHasErrors(['file']);
    }

    public function test_upload_aborts_404_for_incoming_letter(): void
    {
        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas',
            'subject' => 'Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.upload', $letter));

        $response->assertStatus(404);
    }

    public function test_upload_form_aborts_404_for_incoming_letter(): void
    {
        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas',
            'subject' => 'Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.letters.out.upload.process', $letter), []);

        $response->assertStatus(404);
    }

    public function test_admin_can_print_outgoing_letter(): void
    {
        $letter = Letter::create([
            'type' => 'outgoing',
            'letter_format_id' => $this->letterFormat->id,
            'letter_number' => '/001/UND/2026',
            'sequence_number' => 1,
            'letter_date' => '2026-01-15',
            'recipient' => 'Orang Tua Siswa',
            'subject' => 'Undangan Rapat',
            'status' => 'sent',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.print', $letter));

        // PDF response returns 200 with content-type application/pdf
        $response->assertStatus(200);
    }

    public function test_print_aborts_404_for_incoming_letter(): void
    {
        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => 'REF/001',
            'letter_date' => '2026-01-15',
            'sender' => 'Dinas',
            'subject' => 'Edaran',
            'status' => 'received',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.letters.out.print', $letter));

        $response->assertStatus(404);
    }
}
