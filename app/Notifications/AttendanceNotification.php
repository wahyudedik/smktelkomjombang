<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi untuk kejadian absensi:
 * - Keterlambatan
 * - Alpha (tidak hadir tanpa izin)
 * - Rekap harian
 * - Izin/sakit perlu persetujuan
 */
class AttendanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;

    /**
     * Tipe notifikasi
     */
    public const TYPE_LATE = 'late';
    public const TYPE_ALPHA = 'alpha';
    public const TYPE_DAILY_SUMMARY = 'daily_summary';
    public const TYPE_EXCUSE_PENDING = 'excuse_pending';

    /**
     * Buat instance notifikasi
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Channel notifikasi
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Representasi mail
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting("Halo {$notifiable->name}!");

        switch ($this->data['type'] ?? 'info') {
            case self::TYPE_LATE:
                $mail->line("👤 **{$this->data['nama']}** terlambat hari ini.")
                     ->line("⏰ Jam masuk: **{$this->data['jam_masuk']}** (batas: {$this->data['threshold']})")
                     ->line("📅 Tanggal: {$this->data['date']}");
                break;

            case self::TYPE_ALPHA:
                $mail->line("👤 **{$this->data['nama']}** tidak hadir hari ini tanpa izin.")
                     ->line("📅 Tanggal: {$this->data['date']}")
                     ->line("⚠️ Status akan ditandai sebagai **Alpha**.");
                break;

            case self::TYPE_DAILY_SUMMARY:
                $mail->line("📊 **Rekap Absensi Hari Ini** ({$this->data['date']})")
                     ->line("✅ Hadir: **{$this->data['hadir']}** orang")
                     ->line("❌ Tidak Hadir: **{$this->data['alpha']}** orang")
                     ->line("⏰ Terlambat: **{$this->data['late']}** orang")
                     ->line("📝 Izin/Sakit: **{$this->data['excused']}** orang")
                     ->line("👥 Total: **{$this->data['total']}** orang")
                     ->action('Lihat Detail', route('admin.absensi.report.daily', ['date' => now()->format('Y-m-d')]));
                break;

            case self::TYPE_EXCUSE_PENDING:
                $mail->line("📋 Ada pengajuan izin/sakit baru yang perlu persetujuan.")
                     ->line("👤 **{$this->data['nama']}**")
                     ->line("📝 Jenis: **{$this->data['type_label']}** — {$this->data['date']}")
                     ->line("💬 Alasan: {$this->data['reason']}")
                     ->action('Lihat & Setujui', route('admin.absensi.excuses.show', $this->data['excuse_id']));
                break;

            default:
                $mail->line($this->data['message'] ?? 'Ada update terbaru pada sistem absensi.');
        }

        return $mail->action('Dashboard Absensi', route('admin.absensi.index'));
    }

    /**
     * Representasi database (untuk notifikasi di panel admin)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => $this->data['type'] ?? 'info',
            'title'   => $this->getSubject(),
            'message' => $this->getDatabaseMessage(),
            'data'    => $this->data,
            'url'     => $this->getActionUrl(),
        ];
    }

    /**
     * Subject email
     */
    private function getSubject(): string
    {
        return match ($this->data['type'] ?? 'info') {
            self::TYPE_LATE           => "⏰ Keterlambatan — {$this->data['nama']}",
            self::TYPE_ALPHA          => "❌ Alpha — {$this->data['nama']}",
            self::TYPE_DAILY_SUMMARY  => "📊 Rekap Absensi Harian",
            self::TYPE_EXCUSE_PENDING => "📋 Pengajuan Izin Baru",
            default                   => "ℹ️ Update Absensi",
        };
    }

    /**
     * Pesan untuk database notification
     */
    private function getDatabaseMessage(): string
    {
        return match ($this->data['type'] ?? 'info') {
            self::TYPE_LATE           => "{$this->data['nama']} terlambat (jam {$this->data['jam_masuk']})",
            self::TYPE_ALPHA          => "{$this->data['nama']} tidak hadir tanpa izin",
            self::TYPE_DAILY_SUMMARY  => "Rekap: {$this->data['hadir']} hadir, {$this->data['alpha']} alpha, {$this->data['late']} terlambat",
            self::TYPE_EXCUSE_PENDING => "{$this->data['nama']} mengajukan {$this->data['type_label']}",
            default                   => "Update sistem absensi",
        };
    }

    /**
     * URL aksi
     */
    private function getActionUrl(): string
    {
        return match ($this->data['type'] ?? 'info') {
            self::TYPE_EXCUSE_PENDING => route('admin.absensi.excuses.show', $this->data['excuse_id']),
            self::TYPE_DAILY_SUMMARY  => route('admin.absensi.report.daily', ['date' => now()->format('Y-m-d')]),
            default                   => route('admin.absensi.index'),
        };
    }
}
