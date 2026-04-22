<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->verificationUrl = $user->getEmailVerificationUrl();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Email - ' . config('app.name'))
            ->greeting('Halo ' . $this->user->name . '!')
            ->line('Terima kasih telah mendaftar di ' . config('app.name') . '.')
            ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:')
            ->action('Verifikasi Email', $this->verificationUrl)
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.')
            ->line('Link verifikasi ini akan berlaku selama 24 jam.')
            ->salutation('Salam, Tim ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'verification_url' => $this->verificationUrl,
        ];
    }
}
