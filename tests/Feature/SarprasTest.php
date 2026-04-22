<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\KategoriSarpras;
use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SarprasTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $kategori;
    protected $ruang;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user with sarpras role
        $this->user = User::factory()->create([
            'user_type' => 'sarpras'
        ]);

        // Assign sarpras role using Spatie
        $role = $this->getOrCreateRole('sarpras');
        $this->user->assignRole($role);

        // Create test kategori
        $this->kategori = KategoriSarpras::factory()->create([
            'nama_kategori' => 'Test Kategori',
            'kode_kategori' => 'TEST-KAT-001',
            'is_active' => true
        ]);

        // Create test ruang
        $this->ruang = Ruang::factory()->create([
            'nama_ruang' => 'Test Ruang',
            'kode_ruang' => 'TEST-RUANG-001',
            'is_active' => true
        ]);
    }

    /** @test */
    public function user_can_view_sarpras_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/admin/sarpras');

        $response->assertStatus(200);
        $response->assertViewIs('sarpras.dashboard');
    }

    /** @test */
    public function user_can_view_kategori_index()
    {
        $response = $this->actingAs($this->user)->get('/admin/sarpras/kategori');

        $response->assertStatus(200);
        $response->assertViewIs('sarpras.kategori.index');
        $response->assertViewHas('kategoris');
    }

    /** @test */
    public function user_can_create_kategori()
    {
        $kategoriData = [
            'nama_kategori' => 'Test Kategori Baru',
            'kode_kategori' => 'TEST-KAT-NEW',
            'deskripsi' => 'Deskripsi test kategori',
            'is_active' => true,
            'sort_order' => 1
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/kategori', $kategoriData);

        $response->assertRedirect('/admin/sarpras/kategori');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kategori_sarpras', [
            'nama_kategori' => 'Test Kategori Baru',
            'kode_kategori' => 'TEST-KAT-NEW'
        ]);
    }

    /** @test */
    public function kategori_creation_requires_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/sarpras/kategori', []);

        $response->assertSessionHasErrors(['nama_kategori', 'kode_kategori']);
    }

    /** @test */
    public function kategori_kode_must_be_unique()
    {
        $kategoriData = [
            'nama_kategori' => 'Test Kategori Duplicate',
            'kode_kategori' => 'TEST-KAT-001', // Same as existing
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/kategori', $kategoriData);

        $response->assertSessionHasErrors(['kode_kategori']);
    }

    /** @test */
    public function user_can_update_kategori()
    {
        $updateData = [
            'nama_kategori' => 'Updated Kategori Name',
            'kode_kategori' => 'TEST-KAT-001',
            'deskripsi' => 'Updated description',
            'is_active' => true,
            'sort_order' => 2
        ];

        $response = $this->actingAs($this->user)->put("/admin/sarpras/kategori/{$this->kategori->id}", $updateData);

        $response->assertRedirect('/admin/sarpras/kategori');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kategori_sarpras', [
            'id' => $this->kategori->id,
            'nama_kategori' => 'Updated Kategori Name'
        ]);
    }

    /** @test */
    public function user_can_delete_kategori()
    {
        $response = $this->actingAs($this->user)->delete("/admin/sarpras/kategori/{$this->kategori->id}");

        $response->assertRedirect('/admin/sarpras/kategori');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('kategori_sarpras', [
            'id' => $this->kategori->id
        ]);
    }

    /** @test */
    public function user_can_view_barang_index()
    {
        $response = $this->actingAs($this->user)->get('/admin/sarpras/barang');

        $response->assertStatus(200);
        $response->assertViewIs('sarpras.barang.index');
        $response->assertViewHas('barangs');
    }

    /** @test */
    public function user_can_create_barang()
    {
        Storage::fake('local');

        $barangData = [
            'kode_barang' => 'TEST-BARANG-001',
            'nama_barang' => 'Test Barang',
            'deskripsi' => 'Deskripsi test barang',
            'kategori_id' => $this->kategori->id,
            'merk' => 'Test Brand',
            'model' => 'Test Model',
            'serial_number' => 'SN123456',
            'harga_beli' => 1000000,
            'tanggal_pembelian' => '2024-01-01',
            'sumber_dana' => 'APBN',
            'kondisi' => 'baik',
            'ruang_id' => $this->ruang->id,
            'status' => 'tersedia',
            'catatan' => 'Test notes',
            'is_active' => true,
            'foto' => UploadedFile::fake()->image('test.jpg', 100, 100)
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/barang', $barangData);

        $response->assertRedirect('/admin/sarpras/barang');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('barang', [
            'nama_barang' => 'Test Barang',
            'kode_barang' => 'TEST-BARANG-001'
        ]);

        // Check if file was uploaded to public storage
        $barang = Barang::where('nama_barang', 'Test Barang')->first();
        $this->assertTrue(Storage::disk('public')->exists($barang->foto));
    }

    /** @test */
    public function barang_creation_requires_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/admin/sarpras/barang', []);

        $response->assertSessionHasErrors(['kode_barang', 'nama_barang', 'kategori_id', 'kondisi', 'status']);
    }

    /** @test */
    public function barang_kode_must_be_unique()
    {
        // Create existing barang
        Barang::factory()->create([
            'kode_barang' => 'EXISTING-CODE'
        ]);

        $barangData = [
            'kode_barang' => 'EXISTING-CODE', // Duplicate
            'nama_barang' => 'Test Barang',
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'baik',
            'status' => 'tersedia'
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/barang', $barangData);

        $response->assertSessionHasErrors(['kode_barang']);
    }

    /** @test */
    public function user_can_update_barang()
    {
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'ruang_id' => $this->ruang->id
        ]);

        $updateData = [
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => 'Updated Barang Name',
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'rusak',
            'status' => 'tersedia',
            'merk' => 'Updated Brand'
        ];

        $response = $this->actingAs($this->user)->put("/admin/sarpras/barang/{$barang->id}", $updateData);

        $response->assertRedirect('/admin/sarpras/barang');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('barang', [
            'id' => $barang->id,
            'nama_barang' => 'Updated Barang Name',
            'kondisi' => 'rusak'
        ]);
    }

    /** @test */
    public function user_can_delete_barang()
    {
        Storage::fake('local');

        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'foto' => 'private/barang/test.jpg'
        ]);

        // Create fake file in public storage
        $fakePath = 'barang/test.jpg';
        Storage::disk('public')->put($fakePath, 'fake content');
        $barang->foto = $fakePath;
        $barang->save();

        $response = $this->actingAs($this->user)->delete("/admin/sarpras/barang/{$barang->id}");

        $response->assertRedirect('/admin/sarpras/barang');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('barang', [
            'id' => $barang->id
        ]);

        // Check if file was deleted
        $this->assertFalse(Storage::disk('public')->exists($fakePath));
    }

    /** @test */
    public function input_is_sanitized()
    {
        $kategoriData = [
            'nama_kategori' => '<script>alert("xss")</script>Test Kategori',
            'kode_kategori' => 'TEST-SANITIZE',
            'deskripsi' => '<b>Bold text</b> and <script>alert("xss")</script>',
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/kategori', $kategoriData);

        $response->assertRedirect('/admin/sarpras/kategori');

        // Get the created kategori to check sanitized content
        $kategori = KategoriSarpras::where('kode_kategori', 'TEST-SANITIZE')->first();
        $this->assertNotNull($kategori);
        // Check that script tags are removed but content remains
        $this->assertStringNotContainsString('<script>', $kategori->nama_kategori);
        $this->assertStringNotContainsString('<script>', $kategori->deskripsi);
        $this->assertStringNotContainsString('<b>', $kategori->deskripsi);
    }

    /** @test */
    public function file_upload_validation_works()
    {
        $barangData = [
            'kode_barang' => 'TEST-FILE-001',
            'nama_barang' => 'Test Barang',
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'foto' => UploadedFile::fake()->create('test.txt', 100) // Wrong file type
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/barang', $barangData);

        $response->assertSessionHasErrors(['foto']);
    }

    /** @test */
    public function file_size_validation_works()
    {
        $barangData = [
            'kode_barang' => 'TEST-SIZE-001',
            'nama_barang' => 'Test Barang',
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'baik',
            'status' => 'tersedia',
            'foto' => UploadedFile::fake()->image('test.jpg', 100, 100)->size(3000) // Too large
        ];

        $response = $this->actingAs($this->user)->post('/admin/sarpras/barang', $barangData);

        $response->assertSessionHasErrors(['foto']);
    }

    /** @test */
    public function unauthorized_user_cannot_access_sarpras()
    {
        $unauthorizedUser = User::factory()->create([
            'user_type' => 'siswa'
        ]);

        $response = $this->actingAs($unauthorizedUser)->get('/admin/sarpras');

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_sarpras()
    {
        $response = $this->get('/admin/sarpras');

        $response->assertRedirect('/login');
    }
}
