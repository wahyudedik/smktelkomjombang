# 🔍 Audit & Perbaikan Modul — SMK Telekomunikasi

> **Tanggal**: 2026-09-21
> **Status**: Perencanaan
> **Scope**: Audit keamanan, konsistensi kode, dan rate limiting di semua modul

---

## 📊 Ringkasan Temuan Audit

| Kategori | Ditemukan | Severity |
|----------|-----------|----------|
| XSS — Missing ContentSanitizer | 5 controllers | 🔴 CRITICAL |
| XSS — Inconsistent sanitization | 1 controller | 🟡 MODERATE |
| Rate Limiting — Missing on exports | 20+ routes | 🟡 MODERATE |
| Rate Limiting — Missing on write ops | 2 routes | 🟡 MODERATE |
| Route Architecture — Misplaced routes | 6 routes | 🟡 MODERATE |
| N+1 Queries — Missing eager loading | 4 locations | 🟡 MODERATE |
| View XSS — Unsafe {!! !!} usage | 0 confirmed | ✅ SAFE |

---

## 🔴 Temuan #1: Missing ContentSanitizer (5 Controllers)

### Problem
5 controller tidak menggunakan `ContentSanitizer` untuk input HTML. Meskipun field-field ini bukan rich-text editor, mereka menerima input string yang bisa mengandung HTML tags berbahaya (stored XSS).

### Controllers yang terdampak

#### 1. [`LetterInController`](app/Http/Controllers/LetterInController.php)
- **Fields**: `perihal`, `deskripsi`
- **Risk**: Surat masuk bisa menyimpan XSS di field perihal/deskripsi
- **Fix**: Tambahkan `use App\Services\ContentSanitizer;` + sanitize di `store()`

#### 2. [`LetterOutController`](app/Http/Controllers/LetterOutController.php)
- **Fields**: `perihal`, `lampiran`, `keterangan`, `description`
- **Risk**: Surat keluar bisa menyimpan XSS
- **Fix**: Tambahkan `use App\Services\ContentSanitizer;` + sanitize di `store()` dan `processUpload()`

#### 3. [`GuruController`](app/Http/Controllers/GuruController.php)
- **Fields**: `alamat`, `sertifikasi`, `prestasi`, `catatan`
- **Risk**: Data guru bisa menyimpan XSS di field teks bebas
- **Fix**: Tambahkan `use App\Services\ContentSanitizer;` + sanitize di `store()` dan `update()`

#### 4. [`SiswaController`](app/Http/Controllers/SiswaController.php)
- **Fields**: `alamat`, `prestasi`, `catatan`, `alamat_ortu`
- **Risk**: Data siswa bisa menyimpan XSS
- **Fix**: Tambahkan `use App\Services\ContentSanitizer;` + sanitize di `store()` dan `update()`

#### 5. [`KelulusanController`](app/Http/Controllers/KelulusanController.php)
- **Fields**: `alamat`, `prestasi`, `catatan`, `tempat_kuliah`, `tempat_kerja`, `jurusan_kuliah`, `jabatan_kerja`
- **Risk**: Data kelulusan bisa menyimpan XSS
- **Fix**: Tambahkan `use App\Services\ContentSanitizer;` + sanitize di `store()` dan `update()`

### Pattern yang konsisten
```php
// Di bagian use statements
use App\Services\ContentSanitizer;

// Di dalam method store/update, SETELAH $data = $request->all()
$sanitizer = app(ContentSanitizer::class);
$textFields = ['alamat', 'catatan', 'prestasi']; // sesuai field masing-masing
foreach ($textFields as $field) {
    if (!empty($data[$field])) {
        $data[$field] = $sanitizer->sanitizeSimple($data[$field]);
    }
}
```

---

## 🟡 Temuan #2: Inconsistent Sanitization — SarprasController

### Problem
[`SarprasController`](app/Http/Controllers/SarprasController.php) menggunakan `strip_tags()` alih-alih `ContentSanitizer`. Meskipun `strip_tags()` efektif untuk menghapus semua HTML, ini tidak konsisten dengan pendekatan project yang menggunakan `ContentSanitizer` (yang mempertahankan safe tags seperti `<b>`, `<i>`).

### Lokasi yang perlu diubah

#### storeKategori() — Line 144-145
```php
// SEBELUM
$data['nama_kategori'] = strip_tags($data['nama_kategori']);
$data['deskripsi'] = strip_tags($data['deskripsi'] ?? '');

// SESUDAH
$sanitizer = app(ContentSanitizer::class);
$data['nama_kategori'] = $sanitizer->sanitizeText($data['nama_kategori']);
$data['deskripsi'] = $sanitizer->sanitizeSimple($data['deskripsi'] ?? '');
```

#### updateKategori() — Line 179-180
Sama seperti di atas.

#### storeBarang() — Lines 314-318
```php
// SEBELUM
$data['nama_barang'] = strip_tags($data['nama_barang']);
$data['deskripsi'] = strip_tags($data['deskripsi'] ?? '');
$data['merk'] = strip_tags($data['merk'] ?? '');
$data['model'] = strip_tags($data['model'] ?? '');
$data['catatan'] = strip_tags($data['catatan'] ?? '');

// SESUDAH
$sanitizer = app(ContentSanitizer::class);
$data['nama_barang'] = $sanitizer->sanitizeText($data['nama_barang']);
$data['deskripsi'] = $sanitizer->sanitizeSimple($data['deskripsi'] ?? '');
$data['merk'] = $sanitizer->sanitizeText($data['merk'] ?? '');
$data['model'] = $sanitizer->sanitizeText($sanitizer->sanitizeText($data['model'] ?? ''));
$data['catatan'] = $sanitizer->sanitizeSimple($data['catatan'] ?? '');
```

#### updateBarang() — Lines 381-385
Sama seperti storeBarang().

> **Catatan**: Untuk field nama/kode yang seharusnya plain text, gunakan `sanitizeText()` (hilangkan semua HTML). Untuk field deskripsi/catatan yang mungkin perlu formatting, gunakan `sanitizeSimple()`.

---

## 🟡 Temuan #3: Missing Rate Limiting on Export Routes

### Problem
Export routes (GET) untuk beberapa modul tidak memiliki `throttle` middleware. Meskipun ini authenticated routes, rate limiting tetap penting untuk mencegah resource exhaustion (export menghasilkan PDF/Excel/JSON yang resource-intensive).

### Routes yang perlu ditambah throttle

#### Guru Export Routes (line 445-448)
```php
// SEBELUM
Route::get('/export', [GuruController::class, 'export'])->name('export');
Route::get('/export/pdf', [GuruController::class, 'exportPdf'])->name('export.pdf');
Route::get('/export/json', [GuruController::class, 'exportJson'])->name('export.json');
Route::get('/export/xml', [GuruController::class, 'exportXml'])->name('export.xml');

// SESUDAH — tambah throttle:10,1
Route::get('/export', [GuruController::class, 'export'])->middleware('throttle:10,1')->name('export');
Route::get('/export/pdf', [GuruController::class, 'exportPdf'])->middleware('throttle:10,1')->name('export.pdf');
Route::get('/export/json', [GuruController::class, 'exportJson'])->middleware('throttle:10,1')->name('export.json');
Route::get('/export/xml', [GuruController::class, 'exportXml'])->middleware('throttle:10,1')->name('export.xml');
```

#### Siswa Export Routes (line 469-472) — Sama
#### Kelulusan Export Routes (line 548-551) — Sama
#### Sarpras Barang Export Routes (line 628-631) — Sama
#### Sarana Export Route (line 662) — Sama
#### OSIS Calon Export (line 492) — Sama
#### OSIS Pemilih Export (line 506) — Sama

**Total**: ~20 routes perlu ditambah throttle:10,1

---

## 🟡 Temuan #4: Route Architecture — Instagram Analytics Misplaced

### Problem
Instagram Analytics routes (lines 698-703) dan Instagram Account Info routes (lines 706-709) ditempatkan di dalam route group `admin/testimonial-links`:

```php
// routes/web.php line 688-710
Route::middleware([...])->prefix('admin/testimonial-links')->name('admin.testimonial-links.')->group(function () {
    // ... testimonial link routes ...
    
    // Instagram Analytics — ❌ INI DI TEMPAT YANG SALAH
    Route::get('/analytics', [InstagramAnalyticsController::class, 'index'])->name('analytics');
    // ... more instagram routes ...
    
    // Instagram Account Info — ❌ JUGA DI TEMPAT YANG SALAH
    Route::get('/account', [InstagramController::class, 'getAccountInfo'])->name('account');
    // ... more instagram routes ...
});
```

### Impact
- URL jadi: `/admin/testimonial-links/analytics` (salah secara semantik)
- Route name jadi: `admin.testimonial-links.analytics` (salah secara semantik)
- Mudah salah paham saat maintenance

### Fix
Pindahkan Instagram Analytics dan Account routes ke group sendiri atau ke existing Instagram Settings group di bawah superadmin.

---

## 🟡 Temuan #5: Missing Rate Limiting on Letter Store Routes

### Problem
LetterIn dan LetterOut store routes tidak memiliki throttle middleware.

### Fix
```php
// LetterOut store — tambah throttle
Route::post('/', [LetterOutController::class, 'store'])
    ->middleware('throttle:20,1') // Max 20 letter creates per minute
    ->name('store');

// LetterIn store — tambah throttle
Route::post('/', [LetterInController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('store');
```

---

## 🟡 Temuan #6: N+1 Query — Missing Eager Loading

### Problem
4 location memiliki N+1 query problem — Blade view mengakses relationship yang belum di-eager-load di controller.

### Location yang terdampak

#### 1. [`LetterInController::show()`](app/Http/Controllers/LetterInController.php:76) → [`admin/letters/in/show.blade.php`](resources/views/admin/letters/in/show.blade.php:88)
- **View code**: `@foreach ($letter->activityLogs()->latest()->get() as $log)`
- **Problem**: Query `activityLogs` dieksekusi langsung di Blade view — bukan eager loading
- **Fix**: Tambahkan eager load di controller:
```php
public function show(Letter $letter)
{
    if ($letter->type !== 'incoming') abort(404);
    $letter->load('activityLogs');
    return view('admin.letters.in.show', compact('letter'));
}
```
Dan ubah view dari `$letter->activityLogs()->latest()->get()` menjadi `$letter->activityLogs->sortByDesc('created_at')` (atau tambahkan `latest` di eager load).

#### 2. [`LetterOutController::show()`](app/Http/Controllers/LetterOutController.php:187) → [`admin/letters/out/show.blade.php`](resources/views/admin/letters/out/show.blade.php:69)
- **View code**: `@foreach ($letter->activityLogs()->latest()->get() as $log)`
- **Problem**: Sama — query langsung di Blade
- **Fix**: Sama dengan #1:
```php
$letter->load('activityLogs');
```

#### 3. [`GuruController::show()`](app/Http/Controllers/GuruController.php:141) → [`guru/show.blade.php`](resources/views/guru/show.blade.php:216)
- **View code**: `@foreach($guru->user->roles as $role)`
- **Problem**: Controller hanya `$guru->load('user')`, tapi view mengakses `$guru->user->roles`. Setiap role trigger query terpisah.
- **Fix**:
```php
$guru->load('user.roles');
```

#### 4. [`SiswaController::show()`](app/Http/Controllers/SiswaController.php:137) → [`siswa/show.blade.php`](resources/views/siswa/show.blade.php:226)
- **View code**: `@foreach($siswa->user->roles as $role)`
- **Problem**: Sama — controller hanya `$siswa->load('user')`, view akses `$siswa->user->roles`
- **Fix**:
```php
$siswa->load('user.roles');
```

### Sudah Benar (Tidak Perlu Perubahan)
- [`SuperadminController::index()`](app/Http/Controllers/SuperadminController.php:53) — `User::with('roles')` ✅
- [`SuperadminController::showUser()`](app/Http/Controllers/SuperadminController.php:99) — `$user->load('roles', 'auditLogs')` ✅
- [`RolePermissionController::index()`](app/Http/Controllers/RolePermissionController.php:20) — `Role::with('permissions')->withCount('users')` ✅
- [`SarprasController::showBarang()`](app/Http/Controllers/SarprasController.php:338) — `$barang->load(['kategori', 'ruang', 'maintenance.user', 'sarana.ruang', ...])` ✅
- [`SarprasController::showRuang()`](app/Http/Controllers/SarprasController.php:534) — `$ruang->load(['barang.kategori', 'maintenance.user', 'sarana.barang.kategori'])` ✅
- [`SarprasController::showMaintenance()`](app/Http/Controllers/SarprasController.php:724) — `$maintenance->load(['user', 'barang', 'ruang'])` ✅

---

## ✅ Yang Sudah Aman (Tidak Perlu Perubahan)

### View XSS Analysis
Semua penggunaan `{!! !!}` di views sudah aman:
- **Berita/Page content**: `ContentSanitizer` digunakan di controller ✅
- **Event/OSIS descriptions**: Menggunakan `{!! nl2br(e(...)) !!}` (escaped) ✅
- **Admin dashboard charts**: `{!! json_encode(...) !!}` (safe encoding) ✅
- **Hero slider/site settings**: `SettingsController` menggunakan `ContentSanitizer` ✅
- **Barang condition_badge**: Model accessor dengan hardcoded values ✅
- **Pagination vendor views**: Laravel framework (safe) ✅

### Rate Limiting yang Sudah Benar
- Login, user invite, user create: `throttle:5,1` ✅
- Import routes: `throttle:10,1` ✅
- Attendance export: `throttle:10,1` ✅
- Image upload: `throttle:30,1` ✅
- OSIS voting: `throttle:voting` ✅
- Instagram webhook: `throttle:webhook` ✅
- ZKTeco endpoints: `throttle:device-api` ✅

---

## 📋 Rencana Eksekusi — ✅ SEMUA TEREKSEKUSI

### Step 1: ✅ Tambahkan ContentSanitizer ke 5 Controllers
**File**: LetterInController, LetterOutController, GuruController, SiswaController, KelulusanController

### Step 2: ✅ Standardisasi SarprasController
**File**: SarprasController.php — `strip_tags()` → `ContentSanitizer`

### Step 3: ✅ Tambahkan Rate Limiting ke Export Routes
**File**: routes/web.php — `throttle:10,1` ke 24 export routes + `throttle:20,1` ke 2 letter store routes

### Step 4: ✅ Pindahkan Instagram Analytics Routes
**File**: routes/web.php — Dipindah ke group `admin/instagram` (prefix + name baru)

### Step 5: ✅ Perbaiki N+1 Query Issues
**Files**: 4 controllers + 2 Blade views

### Step 6: ✅ Verifikasi
1. `php -l` syntax check — **10/10 pass**
2. Tidak ada import yang missing

### Step 7: ✅ Fix Hardcoded URL (Bonus)
**File**: `resources/views/instagram/analytics.blade.php` — `/instagram/analytics/refresh` → `route('admin.instagram.analytics.refresh')`

---

## ✅ Status: SEMUA PERBAIKAN SELESAI (2026-08-21)

### Files Modified (10 files):
1. `app/Http/Controllers/LetterInController.php` — ContentSanitizer + N+1 eager loading
2. `app/Http/Controllers/LetterOutController.php` — ContentSanitizer + N+1 eager loading
3. `app/Http/Controllers/GuruController.php` — ContentSanitizer + N+1 `load('user.roles')`
4. `app/Http/Controllers/SiswaController.php` — ContentSanitizer + N+1 `load('user.roles')`
5. `app/Http/Controllers/KelulusanController.php` — ContentSanitizer (7 fields)
6. `app/Http/Controllers/SarprasController.php` — `strip_tags()` → `ContentSanitizer`
7. `routes/web.php` — Throttle middleware (24 export + 2 store routes) + Instagram routes reorganization
8. `resources/views/admin/letters/in/show.blade.php` — N+1 fix (query → collection)
9. `resources/views/admin/letters/out/show.blade.php` — N+1 fix (query → collection)
10. `resources/views/instagram/analytics.blade.php` — Hardcoded URL → `route()`

---

## ⚠️ Catatan Penting

- **Tidak ada XSS di views** — Semua `{!! !!}` usage sudah aman
- **ContentSanitizer sudah tersedia** — Tinggal di-import dan dipanggil
- **Tidak mengubah behavior** — Perubahan hanya menambahkan sanitasi layer, tidak mengubah fitur yang ada
- **N+1 fixes minimal** — Hanya 4 location yang perlu diperbaiki, sisanya sudah benar
- **Instagram routes dipisah** — Dari testimonial-links group ke group `admin/instagram` sendiri
