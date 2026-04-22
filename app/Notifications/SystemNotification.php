<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $notificationData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->notificationData['title'])
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->notificationData['message']);

        // Add priority indicator
        switch ($this->notificationData['priority'] ?? 'normal') {
            case 'urgent':
                $mailMessage->priority(1);
                $mailMessage->line('⚠️ **URGENT NOTIFICATION**');
                break;
            case 'high':
                $mailMessage->priority(2);
                $mailMessage->line('🔔 **HIGH PRIORITY**');
                break;
            case 'low':
                $mailMessage->priority(4);
                break;
        }

        // Add type-specific styling
        switch ($this->notificationData['type']) {
            case 'success':
                $mailMessage->line('✅ Success Notification');
                break;
            case 'warning':
                $mailMessage->line('⚠️ Warning Notification');
                break;
            case 'error':
                $mailMessage->line('❌ Error Notification');
                break;
            case 'info':
            default:
                $mailMessage->line('ℹ️ Information Notification');
                break;
        }

        $mailMessage->action('View Dashboard', route('admin.dashboard'))
            ->line('Thank you for using our system!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->notificationData['title'],
            'message' => $this->notificationData['message'],
            'type' => $this->notificationData['type'],
            'priority' => $this->notificationData['priority'] ?? 'normal',
            'created_at' => now()->toISOString(),
            'icon' => $this->getIconForType($this->notificationData['type']),
            'color' => $this->getColorForType($this->notificationData['type'])
        ];
    }

    /**
     * Get icon for notification type
     */
    private function getIconForType(string $type): string
    {
        return match ($type) {
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'times-circle',
            'info' => 'info-circle',
            default => 'bell'
        };
    }

    /**
     * Get color for notification type
     */
    private function getColorForType(string $type): string
    {
        return match ($type) {
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'info' => 'blue',
            default => 'gray'
        };
    }
}
