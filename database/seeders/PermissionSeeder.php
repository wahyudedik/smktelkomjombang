<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard permissions
            [
                'name' => 'dashboard.view',
                'display_name' => 'Dashboard - Lihat Data',
                'description' => 'Permission untuk melihat dashboard utama',
                'module' => 'dashboard',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'dashboard.manage',
                'display_name' => 'Dashboard - Kelola Penuh',
                'description' => 'Permission untuk mengelola dashboard sepenuhnya',
                'module' => 'dashboard',
                'action' => 'manage',
                'guard_name' => 'web'
            ],

            // User Management permissions
            [
                'name' => 'users.view',
                'display_name' => 'User Management - Lihat Data',
                'description' => 'Permission untuk melihat data user',
                'module' => 'users',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'users.create',
                'display_name' => 'User Management - Tambah Data',
                'description' => 'Permission untuk menambah user baru',
                'module' => 'users',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'users.edit',
                'display_name' => 'User Management - Edit Data',
                'description' => 'Permission untuk mengedit data user',
                'module' => 'users',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'User Management - Hapus Data',
                'description' => 'Permission untuk menghapus user',
                'module' => 'users',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'users.export',
                'display_name' => 'User Management - Export Data',
                'description' => 'Permission untuk export data user',
                'module' => 'users',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'users.import',
                'display_name' => 'User Management - Import Data',
                'description' => 'Permission untuk import data user',
                'module' => 'users',
                'action' => 'import',
                'guard_name' => 'web'
            ],

            // Guru Management permissions
            [
                'name' => 'guru.view',
                'display_name' => 'Guru Management - Lihat Data',
                'description' => 'Permission untuk melihat data guru',
                'module' => 'guru',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'guru.create',
                'display_name' => 'Guru Management - Tambah Data',
                'description' => 'Permission untuk menambah data guru',
                'module' => 'guru',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'guru.edit',
                'display_name' => 'Guru Management - Edit Data',
                'description' => 'Permission untuk mengedit data guru',
                'module' => 'guru',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'guru.delete',
                'display_name' => 'Guru Management - Hapus Data',
                'description' => 'Permission untuk menghapus data guru',
                'module' => 'guru',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'guru.export',
                'display_name' => 'Guru Management - Export Data',
                'description' => 'Permission untuk export data guru',
                'module' => 'guru',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'guru.import',
                'display_name' => 'Guru Management - Import Data',
                'description' => 'Permission untuk import data guru',
                'module' => 'guru',
                'action' => 'import',
                'guard_name' => 'web'
            ],

            // Siswa Management permissions
            [
                'name' => 'siswa.view',
                'display_name' => 'Siswa Management - Lihat Data',
                'description' => 'Permission untuk melihat data siswa',
                'module' => 'siswa',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'siswa.create',
                'display_name' => 'Siswa Management - Tambah Data',
                'description' => 'Permission untuk menambah data siswa',
                'module' => 'siswa',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'siswa.edit',
                'display_name' => 'Siswa Management - Edit Data',
                'description' => 'Permission untuk mengedit data siswa',
                'module' => 'siswa',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'siswa.delete',
                'display_name' => 'Siswa Management - Hapus Data',
                'description' => 'Permission untuk menghapus data siswa',
                'module' => 'siswa',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'siswa.export',
                'display_name' => 'Siswa Management - Export Data',
                'description' => 'Permission untuk export data siswa',
                'module' => 'siswa',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'siswa.import',
                'display_name' => 'Siswa Management - Import Data',
                'description' => 'Permission untuk import data siswa',
                'module' => 'siswa',
                'action' => 'import',
                'guard_name' => 'web'
            ],

            // OSIS System permissions
            [
                'name' => 'osis.view',
                'display_name' => 'OSIS System - Lihat Data',
                'description' => 'Permission untuk melihat sistem OSIS',
                'module' => 'osis',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'osis.create',
                'display_name' => 'OSIS System - Tambah Data',
                'description' => 'Permission untuk menambah data OSIS',
                'module' => 'osis',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'osis.edit',
                'display_name' => 'OSIS System - Edit Data',
                'description' => 'Permission untuk mengedit data OSIS',
                'module' => 'osis',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'osis.delete',
                'display_name' => 'OSIS System - Hapus Data',
                'description' => 'Permission untuk menghapus data OSIS',
                'module' => 'osis',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'osis.manage',
                'display_name' => 'OSIS System - Kelola Penuh',
                'description' => 'Permission untuk mengelola sistem OSIS sepenuhnya',
                'module' => 'osis',
                'action' => 'manage',
                'guard_name' => 'web'
            ],

            // Sarpras Management permissions
            [
                'name' => 'sarpras.view',
                'display_name' => 'Sarpras Management - Lihat Data',
                'description' => 'Permission untuk melihat data sarpras',
                'module' => 'sarpras',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.create',
                'display_name' => 'Sarpras Management - Tambah Data',
                'description' => 'Permission untuk menambah data sarpras',
                'module' => 'sarpras',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.edit',
                'display_name' => 'Sarpras Management - Edit Data',
                'description' => 'Permission untuk mengedit data sarpras',
                'module' => 'sarpras',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.delete',
                'display_name' => 'Sarpras Management - Hapus Data',
                'description' => 'Permission untuk menghapus data sarpras',
                'module' => 'sarpras',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.export',
                'display_name' => 'Sarpras Management - Export Data',
                'description' => 'Permission untuk export data sarpras',
                'module' => 'sarpras',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.import',
                'display_name' => 'Sarpras Management - Import Data',
                'description' => 'Permission untuk import data sarpras',
                'module' => 'sarpras',
                'action' => 'import',
                'guard_name' => 'web'
            ],

            // Kelulusan (E-Lulus) permissions
            [
                'name' => 'kelulusan.view',
                'display_name' => 'Kelulusan - Lihat Data',
                'description' => 'Permission untuk melihat data kelulusan',
                'module' => 'kelulusan',
                'action' => 'view',
                'guard_name' => 'web'
            ],

            // Testimonials permissions
            [
                'name' => 'testimonials.view',
                'display_name' => 'Testimonials - Lihat Data',
                'description' => 'Permission untuk melihat testimonials',
                'module' => 'testimonials',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonials.create',
                'display_name' => 'Testimonials - Tambah Data',
                'description' => 'Permission untuk menambah testimonial',
                'module' => 'testimonials',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonials.edit',
                'display_name' => 'Testimonials - Edit Data',
                'description' => 'Permission untuk mengedit testimonial',
                'module' => 'testimonials',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonials.delete',
                'display_name' => 'Testimonials - Hapus Data',
                'description' => 'Permission untuk menghapus testimonial',
                'module' => 'testimonials',
                'action' => 'delete',
                'guard_name' => 'web'
            ],

            // Testimonial Links permissions
            [
                'name' => 'testimonial-links.view',
                'display_name' => 'Testimonial Links - Lihat Data',
                'description' => 'Permission untuk melihat testimonial links',
                'module' => 'testimonial-links',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonial-links.create',
                'display_name' => 'Testimonial Links - Buat Link',
                'description' => 'Permission untuk membuat testimonial link',
                'module' => 'testimonial-links',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonial-links.edit',
                'display_name' => 'Testimonial Links - Edit Link',
                'description' => 'Permission untuk mengedit testimonial link',
                'module' => 'testimonial-links',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'testimonial-links.delete',
                'display_name' => 'Testimonial Links - Hapus Link',
                'description' => 'Permission untuk menghapus testimonial link',
                'module' => 'testimonial-links',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.create',
                'display_name' => 'Kelulusan - Tambah Data',
                'description' => 'Permission untuk menambah data kelulusan',
                'module' => 'kelulusan',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.edit',
                'display_name' => 'Kelulusan - Edit Data',
                'description' => 'Permission untuk mengedit data kelulusan',
                'module' => 'kelulusan',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.delete',
                'display_name' => 'Kelulusan - Hapus Data',
                'description' => 'Permission untuk menghapus data kelulusan',
                'module' => 'kelulusan',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.export',
                'display_name' => 'Kelulusan - Export Data',
                'description' => 'Permission untuk export data kelulusan',
                'module' => 'kelulusan',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.import',
                'display_name' => 'Kelulusan - Import Data',
                'description' => 'Permission untuk import data kelulusan',
                'module' => 'kelulusan',
                'action' => 'import',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelulusan.certificate',
                'display_name' => 'Kelulusan - Generate Certificate',
                'description' => 'Permission untuk generate sertifikat kelulusan',
                'module' => 'kelulusan',
                'action' => 'certificate',
                'guard_name' => 'web'
            ],

            // Jadwal Pelajaran Management permissions
            [
                'name' => 'jadwal.view',
                'display_name' => 'Jadwal Pelajaran - Lihat Data',
                'description' => 'Permission untuk melihat jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'jadwal.create',
                'display_name' => 'Jadwal Pelajaran - Tambah Data',
                'description' => 'Permission untuk menambah jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'jadwal.edit',
                'display_name' => 'Jadwal Pelajaran - Edit Data',
                'description' => 'Permission untuk mengedit jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'jadwal.delete',
                'display_name' => 'Jadwal Pelajaran - Hapus Data',
                'description' => 'Permission untuk menghapus jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'jadwal.export',
                'display_name' => 'Jadwal Pelajaran - Export Data',
                'description' => 'Permission untuk export jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'export',
                'guard_name' => 'web'
            ],
            [
                'name' => 'jadwal.import',
                'display_name' => 'Jadwal Pelajaran - Import Data',
                'description' => 'Permission untuk import jadwal pelajaran',
                'module' => 'jadwal',
                'action' => 'import',
                'guard_name' => 'web'
            ],

            // Page Management permissions
            [
                'name' => 'pages.view',
                'display_name' => 'Page Management - Lihat Data',
                'description' => 'Permission untuk melihat halaman',
                'module' => 'pages',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pages.create',
                'display_name' => 'Page Management - Tambah Data',
                'description' => 'Permission untuk membuat halaman baru',
                'module' => 'pages',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pages.edit',
                'display_name' => 'Page Management - Edit Data',
                'description' => 'Permission untuk mengedit halaman',
                'module' => 'pages',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pages.delete',
                'display_name' => 'Page Management - Hapus Data',
                'description' => 'Permission untuk menghapus halaman',
                'module' => 'pages',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pages.publish',
                'display_name' => 'Page Management - Publish',
                'description' => 'Permission untuk publish halaman',
                'module' => 'pages',
                'action' => 'publish',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pages.unpublish',
                'display_name' => 'Page Management - Unpublish',
                'description' => 'Permission untuk unpublish halaman',
                'module' => 'pages',
                'action' => 'unpublish',
                'guard_name' => 'web'
            ],

            // Instagram Integration permissions
            [
                'name' => 'instagram.view',
                'display_name' => 'Instagram Integration - Lihat Data',
                'description' => 'Permission untuk melihat integrasi Instagram',
                'module' => 'instagram',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'instagram.manage',
                'display_name' => 'Instagram Integration - Kelola Penuh',
                'description' => 'Permission untuk mengelola integrasi Instagram',
                'module' => 'instagram',
                'action' => 'manage',
                'guard_name' => 'web'
            ],

            // Settings permissions
            [
                'name' => 'settings.view',
                'display_name' => 'Settings - Lihat Data',
                'description' => 'Permission untuk melihat pengaturan',
                'module' => 'settings',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'settings.manage',
                'display_name' => 'Settings - Kelola Penuh',
                'description' => 'Permission untuk mengelola pengaturan sistem',
                'module' => 'settings',
                'action' => 'manage',
                'guard_name' => 'web'
            ],

            // Permission Management permissions
            [
                'name' => 'permissions.view',
                'display_name' => 'Permission Management - Lihat Data',
                'description' => 'Permission untuk melihat data permission',
                'module' => 'permissions',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'permissions.create',
                'display_name' => 'Permission Management - Tambah Data',
                'description' => 'Permission untuk membuat permission baru',
                'module' => 'permissions',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'permissions.edit',
                'display_name' => 'Permission Management - Edit Data',
                'description' => 'Permission untuk mengedit permission',
                'module' => 'permissions',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'permissions.delete',
                'display_name' => 'Permission Management - Hapus Data',
                'description' => 'Permission untuk menghapus permission',
                'module' => 'permissions',
                'action' => 'delete',
                'guard_name' => 'web'
            ],

            // Role Management permissions
            [
                'name' => 'roles.view',
                'display_name' => 'Role Management - Lihat Data',
                'description' => 'Permission untuk melihat data role',
                'module' => 'roles',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'Role Management - Tambah Data',
                'description' => 'Permission untuk membuat role baru',
                'module' => 'roles',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'Role Management - Edit Data',
                'description' => 'Permission untuk mengedit role',
                'module' => 'roles',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'Role Management - Hapus Data',
                'description' => 'Permission untuk menghapus role',
                'module' => 'roles',
                'action' => 'delete',
                'guard_name' => 'web'
            ],

            // System permissions
            [
                'name' => 'system.analytics',
                'display_name' => 'System - Analytics',
                'description' => 'Permission untuk melihat analytics sistem',
                'module' => 'system',
                'action' => 'analytics',
                'guard_name' => 'web'
            ],
            [
                'name' => 'system.health',
                'display_name' => 'System - Health Monitor',
                'description' => 'Permission untuk melihat health monitor sistem',
                'module' => 'system',
                'action' => 'health',
                'guard_name' => 'web'
            ],
            [
                'name' => 'system.notifications',
                'display_name' => 'System - Notifications',
                'description' => 'Permission untuk mengelola notifikasi sistem',
                'module' => 'system',
                'action' => 'notifications',
                'guard_name' => 'web'
            ],

            // Sarpras Barcode permissions
            [
                'name' => 'sarpras.barcode',
                'display_name' => 'Sarpras - Barcode Management',
                'description' => 'Permission untuk mengelola barcode sarpras',
                'module' => 'sarpras',
                'action' => 'barcode',
                'guard_name' => 'web'
            ],
            [
                'name' => 'sarpras.maintenance',
                'display_name' => 'Sarpras - Maintenance',
                'description' => 'Permission untuk mengelola maintenance sarpras',
                'module' => 'sarpras',
                'action' => 'maintenance',
                'guard_name' => 'web'
            ],

            // OSIS Voting permissions
            [
                'name' => 'osis.vote',
                'display_name' => 'OSIS - Voting',
                'description' => 'Permission untuk voting OSIS',
                'module' => 'osis',
                'action' => 'vote',
                'guard_name' => 'web'
            ],
            [
                'name' => 'osis.results',
                'display_name' => 'OSIS - View Results',
                'description' => 'Permission untuk melihat hasil voting OSIS',
                'module' => 'osis',
                'action' => 'results',
                'guard_name' => 'web'
            ],

            // Profile permissions
            [
                'name' => 'profile.view',
                'display_name' => 'Profile - Lihat Data',
                'description' => 'Permission untuk melihat profil sendiri',
                'module' => 'profile',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'profile.edit',
                'display_name' => 'Profile - Edit Data',
                'description' => 'Permission untuk mengedit profil sendiri',
                'module' => 'profile',
                'action' => 'edit',
                'guard_name' => 'web'
            ],

            [
                'name' => 'attendance.view',
                'display_name' => 'Absensi - Lihat Data',
                'description' => 'Permission untuk melihat data absensi',
                'module' => 'attendance',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.sync',
                'display_name' => 'Absensi - Sinkronisasi',
                'description' => 'Permission untuk sinkronisasi data absensi dari perangkat',
                'module' => 'attendance',
                'action' => 'sync',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.devices.view',
                'display_name' => 'Absensi - Lihat Perangkat',
                'description' => 'Permission untuk melihat daftar perangkat absensi',
                'module' => 'attendance',
                'action' => 'devices.view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.devices.create',
                'display_name' => 'Absensi - Tambah Perangkat',
                'description' => 'Permission untuk menambah perangkat absensi',
                'module' => 'attendance',
                'action' => 'devices.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.devices.edit',
                'display_name' => 'Absensi - Edit Perangkat',
                'description' => 'Permission untuk mengedit perangkat absensi',
                'module' => 'attendance',
                'action' => 'devices.edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.devices.delete',
                'display_name' => 'Absensi - Hapus Perangkat',
                'description' => 'Permission untuk menghapus perangkat absensi',
                'module' => 'attendance',
                'action' => 'devices.delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.mapping.manage',
                'display_name' => 'Absensi - Kelola Mapping PIN',
                'description' => 'Permission untuk mengelola mapping PIN perangkat ke guru/siswa',
                'module' => 'attendance',
                'action' => 'mapping.manage',
                'guard_name' => 'web'
            ],
            [
                'name' => 'attendance.export',
                'display_name' => 'Absensi - Export Data',
                'description' => 'Permission untuk export data absensi',
                'module' => 'attendance',
                'action' => 'export',
                'guard_name' => 'web'
            ],

            // Surat Management permissions
            [
                'name' => 'surat.view',
                'display_name' => 'Surat - Lihat Data',
                'description' => 'Permission untuk melihat surat',
                'module' => 'surat',
                'action' => 'view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.create',
                'display_name' => 'Surat - Buat Surat',
                'description' => 'Permission untuk membuat surat',
                'module' => 'surat',
                'action' => 'create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.edit',
                'display_name' => 'Surat - Edit Surat',
                'description' => 'Permission untuk mengedit surat',
                'module' => 'surat',
                'action' => 'edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.delete',
                'display_name' => 'Surat - Hapus Surat',
                'description' => 'Permission untuk menghapus surat',
                'module' => 'surat',
                'action' => 'delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.settings',
                'display_name' => 'Surat - Pengaturan',
                'description' => 'Permission untuk mengatur format surat',
                'module' => 'surat',
                'action' => 'settings',
                'guard_name' => 'web'
            ],

            // Surat Masuk specific
            [
                'name' => 'surat.in.view',
                'display_name' => 'Surat Masuk - Lihat Data',
                'description' => 'Permission untuk melihat surat masuk',
                'module' => 'surat',
                'action' => 'in.view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.in.create',
                'display_name' => 'Surat Masuk - Buat Surat',
                'description' => 'Permission untuk membuat surat masuk',
                'module' => 'surat',
                'action' => 'in.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.in.edit',
                'display_name' => 'Surat Masuk - Edit Surat',
                'description' => 'Permission untuk mengedit surat masuk',
                'module' => 'surat',
                'action' => 'in.edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.in.delete',
                'display_name' => 'Surat Masuk - Hapus Surat',
                'description' => 'Permission untuk menghapus surat masuk',
                'module' => 'surat',
                'action' => 'in.delete',
                'guard_name' => 'web'
            ],

            // Surat Keluar specific
            [
                'name' => 'surat.out.view',
                'display_name' => 'Surat Keluar - Lihat Data',
                'description' => 'Permission untuk melihat surat keluar',
                'module' => 'surat',
                'action' => 'out.view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.out.create',
                'display_name' => 'Surat Keluar - Buat Surat',
                'description' => 'Permission untuk membuat surat keluar',
                'module' => 'surat',
                'action' => 'out.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.out.edit',
                'display_name' => 'Surat Keluar - Edit Surat',
                'description' => 'Permission untuk mengedit surat keluar',
                'module' => 'surat',
                'action' => 'out.edit',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.out.delete',
                'display_name' => 'Surat Keluar - Hapus Surat',
                'description' => 'Permission untuk menghapus surat keluar',
                'module' => 'surat',
                'action' => 'out.delete',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.out.print',
                'display_name' => 'Surat Keluar - Cetak',
                'description' => 'Permission untuk mencetak surat keluar',
                'module' => 'surat',
                'action' => 'out.print',
                'guard_name' => 'web'
            ],
            [
                'name' => 'surat.out.upload',
                'display_name' => 'Surat Keluar - Upload Scan',
                'description' => 'Permission untuk mengupload scan surat keluar',
                'module' => 'surat',
                'action' => 'out.upload',
                'guard_name' => 'web'
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Create superadmin role if it doesn't exist
        $superadminRole = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['guard_name' => 'web']
        );

        // Assign permissions to roles (only superadmin gets all permissions)
        if ($superadminRole) {
            // Clear permission cache before assigning
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Get all permissions
            $allPermissions = Permission::all();

            // Assign permissions one by one to ensure they're assigned
            foreach ($allPermissions as $permission) {
                $superadminRole->givePermissionTo($permission);
            }

            // Clear cache again after assigning
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $this->command->info('Assigned ' . $allPermissions->count() . ' permissions to superadmin role');
        } else {
            $this->command->error('Superadmin role not found!');
        }

        // Other roles are created dynamically by superadmin
        // No default role assignments - superadmin will create custom roles as needed
    }
}
