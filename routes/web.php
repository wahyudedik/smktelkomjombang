<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\OSISController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\SaranaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\LetterInController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\LetterOutController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\LetterFormatController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ZKTecoIClockController;
use App\Http\Controllers\SaranaReportController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\InstagramSettingController;
use App\Http\Controllers\InstagramAnalyticsController;

// ========================================
// PUBLIC ROUTES (Landing Page & Public Features)
// ========================================

Route::get('/', function () {
    // Get menu data for header and footer
    $headerMenus = \App\Models\Page::where('is_menu', true)
        ->where('menu_position', 'header')
        ->whereNull('parent_id')
        ->orderBy('menu_sort_order')
        ->with('children')
        ->get();

    $footerMenus = \App\Models\Page::where('is_menu', true)
        ->where('menu_position', 'footer')
        ->whereNull('parent_id')
        ->orderBy('menu_sort_order')
        ->with('children')
        ->get();

    // Get Instagram posts for gallery section
    $instagramPosts = app(\App\Services\InstagramService::class)->getCachedPosts(6);

    return view('welcome', compact('headerMenus', 'footerMenus', 'instagramPosts')); // Landing page - fully customizable
})->name('landing');

// Public graduation check
Route::get('/check-graduation', [KelulusanController::class, 'checkStatus'])->name('public.graduation.check');
Route::post('/check-graduation', [KelulusanController::class, 'processCheck'])->name('public.graduation.check.process');

// Public kegiatan page (Instagram feed integration)
Route::get('/kegiatan', [InstagramController::class, 'index'])->name('public.kegiatan');
Route::get('/kegiatan/refresh', [InstagramController::class, 'refresh'])->name('public.kegiatan.refresh');
Route::get('/kegiatan/posts', [InstagramController::class, 'getPosts'])->name('public.kegiatan.posts');

// Instagram OAuth Callback (for receiving access token from Meta)
Route::get('/instagram/callback', [InstagramController::class, 'handleOAuthCallback'])->name('instagram.callback');

// Instagram Webhook Endpoints (for Meta verification & notifications)
Route::get('/instagram/webhook', [InstagramController::class, 'verifyWebhook'])->name('instagram.webhook.verify');
Route::post('/instagram/webhook', [InstagramController::class, 'handleWebhook'])->name('instagram.webhook.handle');

Route::match(['GET', 'POST'], '/iclock/cdata', [ZKTecoIClockController::class, 'cdata'])->name('zkteco.iclock.cdata');
Route::match(['GET', 'POST'], '/iclock/getrequest', [ZKTecoIClockController::class, 'getrequest'])->name('zkteco.iclock.getrequest');
Route::match(['GET', 'POST'], '/iclock/devicecmd', [ZKTecoIClockController::class, 'devicecmd'])->name('zkteco.iclock.devicecmd');

// Custom pages example
Route::get('/custom-example', function () {
    return view('pages.custom-example');
})->name('public.custom.example');

// Locale & Internationalization Routes
Route::get('/locale/{locale}', [LocaleController::class, 'switchLocale'])->name('locale.switch');
Route::post('/currency/{currency}', [LocaleController::class, 'switchCurrency'])->name('currency.switch');
Route::post('/timezone', [LocaleController::class, 'switchTimezone'])->name('timezone.switch');

// ========================================
// ADMIN PANEL (All authenticated users)
// ========================================

// Single admin dashboard - redirects based on user role
Route::get('/admin', [DashboardController::class, 'index'])->middleware(['auth', 'verified.email'])->name('admin.dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified.email'])->name('admin.dashboard.redirect');

// Admin profile management
Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

// E-Surat Routes
Route::prefix('admin/surat')->name('admin.letters.')->middleware(['auth', 'verified'])->group(function () {
    // Surat Keluar
    Route::prefix('out')->name('out.')->group(function () {
        Route::get('/', [LetterOutController::class, 'index'])->name('index');
        Route::get('/create', [LetterOutController::class, 'create'])->name('create');
        Route::post('/', [LetterOutController::class, 'store'])->name('store');
        Route::get('/{letter}', [LetterOutController::class, 'show'])->name('show');
        Route::get('/{letter}/print', [LetterOutController::class, 'print'])->name('print');
        Route::get('/{letter}/upload', [LetterOutController::class, 'upload'])->name('upload');
        Route::post('/{letter}/upload', [LetterOutController::class, 'processUpload'])->name('upload.process');
    });

    // Surat Masuk
    Route::prefix('in')->name('in.')->group(function () {
        Route::get('/', [LetterInController::class, 'index'])->name('index');
        Route::get('/create', [LetterInController::class, 'create'])->name('create');
        Route::post('/', [LetterInController::class, 'store'])->name('store');
        Route::get('/{letter}', [LetterInController::class, 'show'])->name('show');
    });

    // Format Surat (Admin Only)
    Route::resource('formats', LetterFormatController::class);
});

Route::middleware(['auth', 'verified', 'role:guru|admin|superadmin'])->prefix('admin/absensi')->name('admin.absensi.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/logs', [AttendanceController::class, 'logs'])->name('logs');
    Route::get('/devices', [AttendanceController::class, 'devices'])->name('devices.index');
    Route::put('/devices/{device}', [AttendanceController::class, 'updateDevice'])->name('devices.update');
    Route::get('/mapping', [AttendanceController::class, 'mapping'])->name('mapping.index');
    Route::post('/mapping', [AttendanceController::class, 'storeMapping'])->name('mapping.store');
});

// ========================================
// SUPERADMIN ROUTES (Superadmin only)
// ========================================

Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin/superadmin')->name('admin.superadmin.')->group(function () {
    Route::get('/', [SuperadminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [SuperadminController::class, 'users'])->name('users');
    Route::get('/users/create', [SuperadminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperadminController::class, 'storeUser'])->name('users.store');

    // User Import/Export (must be before {user} routes to avoid conflicts)
    Route::get('/users/import', [SuperadminController::class, 'importUsers'])->name('users.import');
    Route::get('/users/import/template', [SuperadminController::class, 'downloadUserTemplate'])->name('users.downloadTemplate');
    Route::post('/users/import', [SuperadminController::class, 'processUserImport'])->name('users.processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/users/export', [SuperadminController::class, 'exportUsers'])->name('users.export');

    // User CRUD with model binding (must be after specific routes)
    Route::get('/users/{user}', [SuperadminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [SuperadminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [SuperadminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperadminController::class, 'destroyUser'])->name('users.destroy');

    // Module Access Management (must be after other {user} routes)
    Route::get('/users/{user}/module-access', [SuperadminController::class, 'moduleAccess'])->name('users.module-access');
    Route::put('/users/{user}/module-access', [SuperadminController::class, 'updateModuleAccess'])->name('users.module-access.update');

    // Instagram Settings Management
    Route::get('/instagram-settings', [InstagramSettingController::class, 'index'])->name('instagram-settings');
    Route::post('/instagram-settings', [InstagramSettingController::class, 'store'])->name('instagram-settings.store');
    Route::post('/instagram-settings/test-connection', [InstagramSettingController::class, 'testConnection'])->name('instagram-settings.test-connection');
    Route::post('/instagram-settings/sync', [InstagramSettingController::class, 'syncData'])->name('instagram-settings.sync');
    Route::post('/instagram-settings/deactivate', [InstagramSettingController::class, 'deactivate'])->name('instagram-settings.deactivate');
    Route::get('/instagram-settings/current', [InstagramSettingController::class, 'getSettings'])->name('instagram-settings.current');

    // Bulk Import Management
    Route::prefix('bulk-import')->name('bulk-import.')->group(function () {
        Route::get('/', [App\Http\Controllers\BulkImportController::class, 'index'])->name('index');
        Route::post('/process', [App\Http\Controllers\BulkImportController::class, 'processBulkImport'])->name('process');
        Route::get('/template/{module}', [App\Http\Controllers\BulkImportController::class, 'downloadTemplate'])->name('template');
    });
});

// Permission Management Routes
Route::middleware(['auth', 'verified', 'role:superadmin|admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/bulk-create', [App\Http\Controllers\PermissionController::class, 'bulkCreate'])->name('permissions.bulk-create');
    Route::post('permissions/bulk-create', [App\Http\Controllers\PermissionController::class, 'bulkStore'])->name('permissions.bulk-store');
});

// Audit Logs (Access: superadmin only)
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin/audit-logs')->name('admin.audit-logs.')->group(function () {
    Route::get('/', [App\Http\Controllers\AuditLogController::class, 'index'])->name('index');
    Route::get('/export', [App\Http\Controllers\AuditLogController::class, 'export'])->name('export');
    Route::get('/{auditLog}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('show');
});

// Role Management (Access: superadmin only)
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin/roles')->name('admin.roles.')->group(function () {
    Route::get('/', [App\Http\Controllers\RoleManagementController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\RoleManagementController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\RoleManagementController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [App\Http\Controllers\RoleManagementController::class, 'edit'])->name('edit');
    Route::put('/{role}', [App\Http\Controllers\RoleManagementController::class, 'update'])->name('update');
    Route::delete('/{role}', [App\Http\Controllers\RoleManagementController::class, 'destroy'])->name('destroy');
    Route::get('/{role}/assign-users', [App\Http\Controllers\RoleManagementController::class, 'assignUsers'])->name('assign-users');
    Route::post('/{role}/sync-users', [App\Http\Controllers\RoleManagementController::class, 'syncUsers'])->name('sync-users');
});

// Page Management (Access: admin, superadmin)
Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin/pages')->name('admin.pages.')->group(function () {
    // Page CRUD Routes
    Route::get('/', [PageController::class, 'admin'])->name('index');
    Route::get('/create', [PageController::class, 'create'])->name('create');
    Route::post('/', [PageController::class, 'store'])->name('store');
    Route::get('/{page}', [PageController::class, 'show'])->name('show');
    Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit');
    Route::put('/{page}', [PageController::class, 'update'])->name('update');
    Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');

    // Page Additional Actions
    Route::post('/{page}/publish', [PageController::class, 'publish'])->name('publish');
    Route::post('/{page}/unpublish', [PageController::class, 'unpublish'])->name('unpublish');
    Route::post('/{page}/duplicate', [PageController::class, 'duplicate'])->name('duplicate');

    // Page Versioning Routes
    Route::get('/{page}/versions', [PageController::class, 'versions'])->name('versions');
    Route::post('/{page}/versions/{version}/restore', [PageController::class, 'restoreVersion'])->name('versions.restore');
    Route::get('/{page}/versions/{version1}/compare/{version2}', [PageController::class, 'compareVersions'])->name('versions.compare');
});

// ========================================
// MODULE MANAGEMENT ROUTES (Role-based access)
// ========================================

// Guru Management (Access: guru, admin, superadmin)
Route::middleware(['auth', 'verified', 'role:guru|admin|superadmin'])->prefix('admin/guru')->name('admin.guru.')->group(function () {
    // Import/Export routes (must be before resource routes)
    Route::get('/import', [GuruController::class, 'import'])->name('import');
    Route::get('/import/template', [GuruController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::post('/import', [GuruController::class, 'processImport'])->name('processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/export', [GuruController::class, 'export'])->name('export');
    Route::get('/export/pdf', [GuruController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/json', [GuruController::class, 'exportJson'])->name('export.json');
    Route::get('/export/xml', [GuruController::class, 'exportXml'])->name('export.xml');

    // Subject management routes
    Route::post('/add-subject', [GuruController::class, 'addSubject'])->name('addSubject');

    // CRUD routes
    Route::get('/', [GuruController::class, 'index'])->name('index');
    Route::get('/create', [GuruController::class, 'create'])->name('create');
    Route::post('/', [GuruController::class, 'store'])->name('store');
    Route::get('/{guru}', [GuruController::class, 'show'])->name('show');
    Route::get('/{guru}/edit', [GuruController::class, 'edit'])->name('edit');
    Route::put('/{guru}', [GuruController::class, 'update'])->name('update');
    Route::delete('/{guru}', [GuruController::class, 'destroy'])->name('destroy');
});

// Siswa Management (Access: guru, admin, superadmin)
Route::middleware(['auth', 'verified', 'role:guru|admin|superadmin'])->prefix('admin/siswa')->name('admin.siswa.')->group(function () {
    // Import/Export routes (must be before resource routes)
    Route::get('/import', [SiswaController::class, 'import'])->name('import');
    Route::get('/import/template', [SiswaController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::post('/import', [SiswaController::class, 'processImport'])->name('processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/export', [SiswaController::class, 'export'])->name('export');
    Route::get('/export/pdf', [SiswaController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/json', [SiswaController::class, 'exportJson'])->name('export.json');
    Route::get('/export/xml', [SiswaController::class, 'exportXml'])->name('export.xml');

    // CRUD routes
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::get('/create', [SiswaController::class, 'create'])->name('create');
    Route::post('/', [SiswaController::class, 'store'])->name('store');
    Route::get('/{siswa}', [SiswaController::class, 'show'])->name('show');
    Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update');
    Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
});

// OSIS Management (Access: admin, superadmin, osis)
Route::middleware(['auth', 'verified', 'role:admin|superadmin|osis'])->prefix('admin/osis')->name('admin.osis.')->group(function () {
    Route::get('/', [OSISController::class, 'index'])->name('index');

    // Calon Import/Export routes
    Route::get('/calon/import', [OSISController::class, 'importCalon'])->name('calon.import');
    Route::get('/calon/import/template', [OSISController::class, 'downloadCalonTemplate'])->name('calon.downloadTemplate');
    Route::post('/calon/import', [OSISController::class, 'processCalonImport'])->name('calon.processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/calon/export', [OSISController::class, 'exportCalon'])->name('calon.export');

    Route::get('/calon', [OSISController::class, 'calonIndex'])->name('calon.index');
    Route::get('/calon/create', [OSISController::class, 'createCalon'])->name('calon.create');
    Route::post('/calon', [OSISController::class, 'storeCalon'])->name('calon.store');
    Route::get('/calon/{calon}', [OSISController::class, 'showCalon'])->name('calon.show');
    Route::get('/calon/{calon}/edit', [OSISController::class, 'editCalon'])->name('calon.edit');
    Route::put('/calon/{calon}', [OSISController::class, 'updateCalon'])->name('calon.update');
    Route::delete('/calon/{calon}', [OSISController::class, 'destroyCalon'])->name('calon.destroy');

    // Pemilih Import/Export routes (must be before {pemilih} routes)
    Route::get('/pemilih/import', [OSISController::class, 'importPemilih'])->name('pemilih.import');
    Route::get('/pemilih/import/template', [OSISController::class, 'downloadPemilihTemplate'])->name('pemilih.downloadTemplate');
    Route::post('/pemilih/import', [OSISController::class, 'processPemilihImport'])->name('pemilih.processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/pemilih/export', [OSISController::class, 'exportPemilih'])->name('pemilih.export');

    Route::get('/pemilih', [OSISController::class, 'pemilihIndex'])->name('pemilih.index');
    Route::get('/pemilih/create', [OSISController::class, 'createPemilih'])->name('pemilih.create');
    Route::post('/pemilih', [OSISController::class, 'storePemilih'])->name('pemilih.store');
    Route::post('/pemilih/generate-from-users', [OSISController::class, 'generatePemilihFromUsers'])->name('pemilih.generate-from-users');

    // Pemilih CRUD with model binding (must be after specific routes)
    Route::get('/pemilih/{pemilih}', [OSISController::class, 'showPemilih'])->name('pemilih.show');
    Route::get('/pemilih/{pemilih}/edit', [OSISController::class, 'editPemilih'])->name('pemilih.edit');
    Route::put('/pemilih/{pemilih}', [OSISController::class, 'updatePemilih'])->name('pemilih.update');
    Route::delete('/pemilih/{pemilih}', [OSISController::class, 'destroyPemilih'])->name('pemilih.destroy');

    Route::get('/voting', [OSISController::class, 'voting'])->name('voting');
    Route::post('/vote', [OSISController::class, 'processVote'])->name('vote');
    Route::get('/results', [OSISController::class, 'results'])->name('results');
    Route::get('/results/export/pdf', [OSISController::class, 'exportVotingResultsPdf'])->name('results.export.pdf');
    Route::get('/results/export/json', [OSISController::class, 'exportVotingResultsJson'])->name('results.export.json');
    Route::get('/results/export/xml', [OSISController::class, 'exportVotingResultsXml'])->name('results.export.xml');
    Route::get('/analytics', [OSISController::class, 'analytics'])->name('analytics');
    Route::get('/teacher-view', [OSISController::class, 'teacherView'])->name('teacher-view');
});

// OSIS Student Routes (Access: siswa) - Voting and Results
Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('admin/osis')->name('admin.osis.')->group(function () {
    Route::get('/voting', [OSISController::class, 'voting'])->name('voting');
    Route::post('/vote', [OSISController::class, 'processVote'])->name('vote');
    Route::get('/results', [OSISController::class, 'results'])->name('results');
});

// E-Lulus Management (Access: admin, superadmin, guru)
Route::middleware(['auth', 'verified', 'role:admin|superadmin|guru'])->prefix('admin/lulus')->name('admin.lulus.')->group(function () {
    // Import/Export routes (must be before resource routes)
    Route::get('/import', [KelulusanController::class, 'import'])->name('import');
    Route::get('/import/template', [KelulusanController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::post('/import', [KelulusanController::class, 'processImport'])->name('processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/export', [KelulusanController::class, 'export'])->name('export');
    Route::get('/export/pdf', [KelulusanController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/json', [KelulusanController::class, 'exportJson'])->name('export.json');
    Route::get('/export/xml', [KelulusanController::class, 'exportXml'])->name('export.xml');
    Route::get('/check', [KelulusanController::class, 'checkStatus'])->name('check');
    Route::post('/check', [KelulusanController::class, 'processCheck'])->name('check.process');

    // CRUD routes
    Route::get('/', [KelulusanController::class, 'index'])->name('index');
    Route::get('/create', [KelulusanController::class, 'create'])->name('create');
    Route::post('/', [KelulusanController::class, 'store'])->name('store');
    Route::get('/{kelulusan}', [KelulusanController::class, 'show'])->name('show');
    Route::get('/{kelulusan}/edit', [KelulusanController::class, 'edit'])->name('edit');
    Route::put('/{kelulusan}', [KelulusanController::class, 'update'])->name('update');
    Route::delete('/{kelulusan}', [KelulusanController::class, 'destroy'])->name('destroy');
    Route::get('/{kelulusan}/certificate', [KelulusanController::class, 'generateCertificate'])->name('certificate');
    Route::get('/{kelulusan}/certificate/download', [KelulusanController::class, 'downloadCertificate'])->name('certificate.download');
});

// E-Lulus Student Routes (Access: siswa) - View only
Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('admin/lulus')->name('admin.lulus.')->group(function () {
    Route::get('/', [KelulusanController::class, 'index'])->name('index');
    Route::get('/check', [KelulusanController::class, 'checkStatus'])->name('check');
    Route::post('/check', [KelulusanController::class, 'processCheck'])->name('check.process');
});

// Jadwal Pelajaran Management (Access: guru, admin, superadmin)
Route::middleware(['auth', 'verified', 'role:guru|admin|superadmin'])->prefix('admin/jadwal-pelajaran')->name('admin.jadwal-pelajaran.')->group(function () {
    // Import/Export routes (must be before resource routes)
    Route::get('/import', [App\Http\Controllers\JadwalPelajaranController::class, 'import'])->name('import');
    Route::post('/import', [App\Http\Controllers\JadwalPelajaranController::class, 'import'])->name('processImport')->middleware('throttle:10,1');
    Route::get('/export', [App\Http\Controllers\JadwalPelajaranController::class, 'export'])->name('export');
    Route::get('/export/pdf', [App\Http\Controllers\JadwalPelajaranController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/json', [App\Http\Controllers\JadwalPelajaranController::class, 'exportJson'])->name('export.json');
    Route::get('/export/xml', [App\Http\Controllers\JadwalPelajaranController::class, 'exportXml'])->name('export.xml');

    // Calendar view
    Route::get('/calendar', [App\Http\Controllers\JadwalPelajaranController::class, 'calendar'])->name('calendar');

    // Check conflict endpoint
    Route::post('/check-conflict', [App\Http\Controllers\JadwalPelajaranController::class, 'checkConflict'])->name('check-conflict');

    // CRUD routes
    Route::get('/', [App\Http\Controllers\JadwalPelajaranController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\JadwalPelajaranController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\JadwalPelajaranController::class, 'store'])->name('store');
    Route::get('/{jadwalPelajaran}', [App\Http\Controllers\JadwalPelajaranController::class, 'show'])->name('show');
    Route::get('/{jadwalPelajaran}/edit', [App\Http\Controllers\JadwalPelajaranController::class, 'edit'])->name('edit');
    Route::put('/{jadwalPelajaran}', [App\Http\Controllers\JadwalPelajaranController::class, 'update'])->name('update');
    Route::delete('/{jadwalPelajaran}', [App\Http\Controllers\JadwalPelajaranController::class, 'destroy'])->name('destroy');
});

// Jadwal Pelajaran Student Routes (Access: siswa) - View only
Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('admin/jadwal-pelajaran')->name('admin.jadwal-pelajaran.')->group(function () {
    Route::get('/', [App\Http\Controllers\JadwalPelajaranController::class, 'index'])->name('index');
    Route::get('/calendar', [App\Http\Controllers\JadwalPelajaranController::class, 'calendar'])->name('calendar');
});

// Sarpras Management (Access: sarpras, admin, superadmin)
Route::middleware(['auth', 'verified', 'role:sarpras|admin|superadmin'])->prefix('admin/sarpras')->name('admin.sarpras.')->group(function () {
    Route::get('/', [SarprasController::class, 'index'])->name('index');
    Route::get('/reports', [SarprasController::class, 'reports'])->name('reports');

    // Kategori Management
    Route::get('/kategori', [SarprasController::class, 'kategoriIndex'])->name('kategori.index');
    Route::get('/kategori/create', [SarprasController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [SarprasController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [SarprasController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [SarprasController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [SarprasController::class, 'destroyKategori'])->name('kategori.destroy');

    // Barang Management
    Route::get('/barang', [SarprasController::class, 'barangIndex'])->name('barang.index');
    Route::get('/barang/create', [SarprasController::class, 'createBarang'])->name('barang.create');
    Route::post('/barang', [SarprasController::class, 'storeBarang'])->name('barang.store');

    // Barang Import/Export (must be before {barang} routes to avoid conflicts)
    Route::get('/barang/import', [SarprasController::class, 'importBarang'])->name('barang.import');
    Route::get('/barang/import/template', [SarprasController::class, 'downloadBarangTemplate'])->name('barang.downloadTemplate');
    Route::post('/barang/import', [SarprasController::class, 'processBarangImport'])->name('barang.processImport')->middleware('throttle:10,1'); // Max 10 imports per minute
    Route::get('/barang/export', [SarprasController::class, 'exportBarang'])->name('barang.export');
    Route::get('/barang/export/pdf', [SarprasController::class, 'exportBarangPdf'])->name('barang.export.pdf');
    Route::get('/barang/export/json', [SarprasController::class, 'exportBarangJson'])->name('barang.export.json');
    Route::get('/barang/export/xml', [SarprasController::class, 'exportBarangXml'])->name('barang.export.xml');

    // Barang CRUD with model binding (must be after specific routes)
    Route::get('/barang/{barang}', [SarprasController::class, 'showBarang'])->name('barang.show');
    Route::get('/barang/{barang}/edit', [SarprasController::class, 'editBarang'])->name('barang.edit');
    Route::put('/barang/{barang}', [SarprasController::class, 'updateBarang'])->name('barang.update');
    Route::delete('/barang/{barang}', [SarprasController::class, 'destroyBarang'])->name('barang.destroy');

    // Ruang Management
    Route::get('/ruang', [SarprasController::class, 'ruangIndex'])->name('ruang.index');
    Route::get('/ruang/create', [SarprasController::class, 'createRuang'])->name('ruang.create');
    Route::post('/ruang', [SarprasController::class, 'storeRuang'])->name('ruang.store');
    Route::get('/ruang/{ruang}', [SarprasController::class, 'showRuang'])->name('ruang.show');
    Route::get('/ruang/{ruang}/edit', [SarprasController::class, 'editRuang'])->name('ruang.edit');
    Route::put('/ruang/{ruang}', [SarprasController::class, 'updateRuang'])->name('ruang.update');
    Route::delete('/ruang/{ruang}', [SarprasController::class, 'destroyRuang'])->name('ruang.destroy');

    // Maintenance Management
    Route::get('/maintenance', [SarprasController::class, 'maintenanceIndex'])->name('maintenance.index');
    Route::get('/maintenance/create', [SarprasController::class, 'createMaintenance'])->name('maintenance.create');
    Route::post('/maintenance', [SarprasController::class, 'storeMaintenance'])->name('maintenance.store');
    Route::get('/maintenance/{maintenance}', [SarprasController::class, 'showMaintenance'])->name('maintenance.show');
    Route::get('/maintenance/{maintenance}/edit', [SarprasController::class, 'editMaintenance'])->name('maintenance.edit');
    Route::put('/maintenance/{maintenance}', [SarprasController::class, 'updateMaintenance'])->name('maintenance.update');
    Route::delete('/maintenance/{maintenance}', [SarprasController::class, 'destroyMaintenance'])->name('maintenance.destroy');

    // Sarana Management
    Route::get('/sarana', [SaranaController::class, 'index'])->name('sarana.index');
    Route::get('/sarana/create', [SaranaController::class, 'create'])->name('sarana.create');
    Route::post('/sarana', [SaranaController::class, 'store'])->name('sarana.store');
    Route::get('/sarana/get-barang-by-ruang', [SaranaController::class, 'getBarangByRuang'])->name('sarana.getBarangByRuang');
    Route::get('/sarana/export-excel', [SaranaController::class, 'exportExcel'])->name('sarana.exportExcel');
    Route::get('/sarana/download-template', [SaranaController::class, 'downloadTemplate'])->name('sarana.downloadTemplate');
    Route::post('/sarana/import-excel', [SaranaController::class, 'importExcel'])->name('sarana.importExcel');
    Route::get('/sarana/{sarana}', [SaranaController::class, 'show'])->name('sarana.show');
    Route::get('/sarana/{sarana}/edit', [SaranaController::class, 'edit'])->name('sarana.edit');
    Route::put('/sarana/{sarana}', [SaranaController::class, 'update'])->name('sarana.update');
    Route::delete('/sarana/{sarana}', [SaranaController::class, 'destroy'])->name('sarana.destroy');
    Route::get('/sarana/{sarana}/print-invoice', [SaranaController::class, 'printInvoice'])->name('sarana.printInvoice');

    // Reports
    Route::get('/reports', [SaranaReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-pdf', [SaranaReportController::class, 'exportPdf'])->name('reports.exportPdf');
});

// Testimonials Management (Access: admin, superadmin)
Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin/testimonials')->name('admin.testimonials.')->group(function () {
    Route::get('/', [App\Http\Controllers\TestimonialController::class, 'index'])->name('index');
    Route::get('/{testimonial}', [App\Http\Controllers\TestimonialController::class, 'show'])->name('show');
    Route::post('/{testimonial}/approve', [App\Http\Controllers\TestimonialController::class, 'approve'])->name('approve');
    Route::post('/{testimonial}/reject', [App\Http\Controllers\TestimonialController::class, 'reject'])->name('reject');
    Route::post('/{testimonial}/toggle-featured', [App\Http\Controllers\TestimonialController::class, 'toggleFeatured'])->name('toggle-featured');
    Route::delete('/{testimonial}', [App\Http\Controllers\TestimonialController::class, 'destroy'])->name('destroy');
});

// Testimonial Links Management (Access: admin, superadmin)
Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin/testimonial-links')->name('admin.testimonial-links.')->group(function () {
    Route::get('/', [App\Http\Controllers\TestimonialLinkController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\TestimonialLinkController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\TestimonialLinkController::class, 'store'])->name('store');
    Route::get('/{testimonialLink}/edit', [App\Http\Controllers\TestimonialLinkController::class, 'edit'])->name('edit');
    Route::put('/{testimonialLink}', [App\Http\Controllers\TestimonialLinkController::class, 'update'])->name('update');
    Route::post('/{testimonialLink}/toggle-active', [App\Http\Controllers\TestimonialLinkController::class, 'toggleActive'])->name('toggle-active');
    Route::delete('/{testimonialLink}', [App\Http\Controllers\TestimonialLinkController::class, 'destroy'])->name('destroy');

    // Instagram Analytics (Admin only)
    Route::get('/analytics', [InstagramAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/data', [InstagramAnalyticsController::class, 'getAnalyticsData'])->name('analytics.data');
    Route::get('/analytics/engagement', [InstagramAnalyticsController::class, 'getEngagementData'])->name('analytics.engagement');
    Route::post('/analytics/refresh', [InstagramAnalyticsController::class, 'refreshAnalytics'])->name('analytics.refresh');
    Route::get('/analytics/top-posts', [InstagramAnalyticsController::class, 'getTopPosts'])->name('analytics.top-posts');

    // Instagram Account Info (Admin only)
    Route::get('/account', [InstagramController::class, 'getAccountInfo'])->name('account');
    Route::get('/validate', [InstagramController::class, 'validateConnection'])->name('validate');
    Route::get('/posts', [InstagramController::class, 'getPosts'])->name('posts');
    Route::get('/refresh', [InstagramController::class, 'refresh'])->name('refresh');
});

// ========================================
// OFFLINE MODE ROUTE (For Service Worker)
// ========================================

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// ========================================
// PUBLIC PAGE ROUTES (Must be last to avoid conflicts)
// ========================================

Route::get('/pages', [PageController::class, 'publicIndex'])->name('pages.public.index');
Route::get('/page/{slug}', [PageController::class, 'publicShow'])->name('pages.public.show');

// Documentation Routes
Route::get('/docs/instagram-setup', function () {
    return view('docs.instagram-setup');
})->name('docs.instagram-setup');

// Barcode Routes
Route::get('/barcode/{code}', [SarprasController::class, 'generateBarcode'])->name('sarpras.barcode');
Route::get('/qrcode/{code}', [SarprasController::class, 'generateQRCode'])->name('sarpras.qrcode');

// Additional Barcode Routes (for authenticated users)
Route::middleware(['auth', 'verified', 'role:sarpras'])->prefix('admin/sarpras')->name('admin.sarpras.')->group(function () {
    Route::post('/barcode/generate-all', [SarprasController::class, 'generateAllBarcodes'])->name('barcode.generate-all');
    Route::get('/barcode/print/{barang}', [SarprasController::class, 'printBarcode'])->name('barcode.print');
    Route::post('/barcode/bulk-print', [SarprasController::class, 'bulkPrintBarcodes'])->name('barcode.bulk-print');
    Route::get('/barcode/scan', [SarprasController::class, 'showScanPage'])->name('barcode.scan');
    Route::post('/barcode/scan', [SarprasController::class, 'processScan'])->name('barcode.scan.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Email Verification Routes - handled in routes/auth.php

Route::get('/email/verify/resend', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resendForGuest'])->name('verification.resend-guest');
Route::post('/email/verify/resend', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resendForGuest'])->name('verification.resend-guest.post');

// Email Verification for Authenticated Users (moved from auth.php)
Route::post('/email/verify/resend-auth', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])->name('verification.resend')->middleware('auth');

// Registration Routes
// Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// ========================================
// SETTINGS & API ROUTES (Admin only)
// ========================================

Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Settings Routes (Admin only)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/data-management', [SettingsController::class, 'dataManagement'])->name('settings.data-management');
    Route::get('/settings/kelas-jurusan', [SettingsController::class, 'kelasJurusan'])->name('settings.kelas-jurusan');

    // Data Management CRUD Routes
    Route::prefix('settings/data-management')->name('settings.data-management.')->group(function () {
        // Kelas routes
        Route::post('/kelas', [DataManagementController::class, 'storeKelas'])->name('kelas.store');
        Route::put('/kelas/{id}', [DataManagementController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/{id}', [DataManagementController::class, 'deleteKelas'])->name('kelas.delete');

        // Jurusan routes
        Route::post('/jurusan', [DataManagementController::class, 'storeJurusan'])->name('jurusan.store');
        Route::put('/jurusan/{id}', [DataManagementController::class, 'updateJurusan'])->name('jurusan.update');
        Route::delete('/jurusan/{id}', [DataManagementController::class, 'deleteJurusan'])->name('jurusan.delete');

        // Ekstrakurikuler routes
        Route::post('/ekstrakurikuler', [DataManagementController::class, 'storeEkstrakurikuler'])->name('ekstrakurikuler.store');
        Route::put('/ekstrakurikuler/{id}', [DataManagementController::class, 'updateEkstrakurikuler'])->name('ekstrakurikuler.update');
        Route::delete('/ekstrakurikuler/{id}', [DataManagementController::class, 'deleteEkstrakurikuler'])->name('ekstrakurikuler.delete');

        // Mata Pelajaran routes
        Route::post('/mata-pelajaran', [DataManagementController::class, 'storeMataPelajaran'])->name('mata-pelajaran.store');
        Route::put('/mata-pelajaran/{id}', [DataManagementController::class, 'updateMataPelajaran'])->name('mata-pelajaran.update');
        Route::delete('/mata-pelajaran/{id}', [DataManagementController::class, 'deleteMataPelajaran'])->name('mata-pelajaran.delete');
    });

    // Landing Page Management Routes
    Route::get('/settings/landing-page', [SettingsController::class, 'landingPage'])->name('settings.landing-page');
    Route::post('/settings/landing-page', [SettingsController::class, 'updateLandingPage'])->name('settings.landing-page.update');
    Route::post('/settings/landing-page/reset', [SettingsController::class, 'resetLandingPage'])->name('settings.landing-page.reset');
    Route::get('/settings/seo', [SettingsController::class, 'seoSettings'])->name('settings.seo');
    Route::post('/settings/seo', [SettingsController::class, 'updateSeoSettings'])->name('settings.seo.update');


    // Analytics Dashboard
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/data', [App\Http\Controllers\AnalyticsController::class, 'getData'])->name('analytics.data');
    Route::get('/analytics/export', [App\Http\Controllers\AnalyticsController::class, 'export'])->name('analytics.export');

    // System Health Dashboard
    Route::get('/system/health', [App\Http\Controllers\SystemHealthController::class, 'index'])->name('system.health');

    // Notification Center
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
});

// Push Notifications (Access: All authenticated users)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/push/subscribe', [App\Http\Controllers\PushNotificationController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [App\Http\Controllers\PushNotificationController::class, 'unsubscribe'])->name('push.unsubscribe');
    Route::get('/push/vapid-key', [App\Http\Controllers\PushNotificationController::class, 'vapidPublicKey'])->name('push.vapid-key');
});

Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {


    // User Management (Superadmin only)
    Route::prefix('user-management')->name('user-management.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserManagementController::class, 'index'])->name('index');
        Route::post('/invite', [App\Http\Controllers\UserManagementController::class, 'inviteUser'])->name('invite');
        Route::post('/create', [App\Http\Controllers\UserManagementController::class, 'createUser'])->name('create');
        Route::get('/users/{user}/edit', [App\Http\Controllers\UserManagementController::class, 'editUser'])->name('edit');
        Route::put('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'updateUser'])->name('update');
        Route::delete('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'deleteUser'])->name('delete');
        Route::post('/users/{user}/toggle-status', [App\Http\Controllers\UserManagementController::class, 'toggleUserStatus'])->name('toggle-status');
        Route::get('/roles', [App\Http\Controllers\UserManagementController::class, 'getUserRoles'])->name('roles');
    });

    // Role & Permission Management (Superadmin only - moved outside admin|superadmin group)
});

// Role & Permission Management (Superadmin ONLY - separate group for strict security)
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin/role-permissions')->name('admin.role-permissions.')->group(function () {
    Route::get('/', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('index');
    Route::post('/roles', [App\Http\Controllers\RolePermissionController::class, 'createRole'])->name('store');
    Route::put('/roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'updateRole'])->name('update');
    Route::delete('/roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'deleteRole'])->name('destroy');
    Route::post('/assign-role', [App\Http\Controllers\RolePermissionController::class, 'assignRoleToUser'])->name('assign-role');
    Route::post('/remove-role', [App\Http\Controllers\RolePermissionController::class, 'removeRoleFromUser'])->name('remove-role');
    Route::get('/roles/{role}/permissions', [App\Http\Controllers\RolePermissionController::class, 'getRolePermissions'])->name('role-permissions');
    Route::get('/users', [App\Http\Controllers\RolePermissionController::class, 'getUsersWithRoles'])->name('users');
});

// Testimonials (Public - No Login Required)
Route::get('/testimonial', [App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
Route::post('/testimonial', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');

// Testimonial Links (Public with Token)
Route::get('/testimonial-link/{token}', [App\Http\Controllers\TestimonialLinkController::class, 'showPublic'])->name('testimonials.public');
Route::post('/testimonial-link/{token}', [App\Http\Controllers\TestimonialLinkController::class, 'storePublic'])->name('testimonials.public.store');

require __DIR__ . '/auth.php';
