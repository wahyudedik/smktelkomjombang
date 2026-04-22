<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Helpers\NotificationHelper;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (admin/superadmin)
        $admin = User::first();

        if (!$admin) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        $this->command->info('Creating sample notifications...');

        // 1. Welcome notification
        NotificationHelper::sendWelcome($admin);

        // 2. Announcement
        NotificationHelper::sendAnnouncement(
            '📢 Pengumuman Penting',
            'Sistem telah diperbarui dengan fitur-fitur baru. Silakan jelajahi dashboard untuk melihat peningkatan fungsionalitas.',
            'info'
        );

        // 3. OSIS Voting (send to all users for now)
        NotificationHelper::sendAnnouncement(
            '🗳️ Pemilihan OSIS Dimulai!',
            'Periode pemilihan ketua OSIS telah dibuka. Gunakan hak suara Anda sekarang!',
            'success'
        );

        // 4. Maintenance Alert
        NotificationHelper::sendMaintenanceAlert('23:00', '01:00');

        // 5. Data Change
        NotificationHelper::sendDataChange($admin, 'profil', 'diperbarui');

        // 6. Sarpras Alert (send to all users for now)
        NotificationHelper::sendAnnouncement(
            '⚠️ Peringatan Barang Rusak',
            'Terdapat 3 barang dengan kondisi rusak yang memerlukan perhatian segera.',
            'warning'
        );

        // 7. Password Changed
        NotificationHelper::sendPasswordChanged($admin);

        // 8. Reminder
        NotificationHelper::sendReminder(
            $admin,
            'Reminder Backup Data',
            'Jangan lupa untuk melakukan backup data sistem secara berkala.'
        );

        $this->command->info('Sample notifications created successfully!');
    }
}
