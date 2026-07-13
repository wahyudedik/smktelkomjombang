<?php

namespace App\Jobs;

use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * The users to send notification to.
     */
    public array $userIds;

    /**
     * Notification title.
     */
    public string $title;

    /**
     * Notification message.
     */
    public string $message;

    /**
     * Notification type (info, success, warning, error).
     */
    public string $type;

    /**
     * Additional metadata.
     */
    public array $metadata;

    /**
     * Whether to also send email.
     */
    public bool $sendEmail;

    /**
     * Whether to also send push notification.
     */
    public bool $sendPush;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $userIds,
        string $title,
        string $message,
        string $type = 'info',
        array $metadata = [],
        bool $sendEmail = false,
        bool $sendPush = true
    ) {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->metadata = $metadata;
        $this->sendEmail = $sendEmail;
        $this->sendPush = $sendPush;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereIn('id', $this->userIds)->get();

        if ($users->isEmpty()) {
            Log::warning('SendNotificationJob: No users found for IDs: ' . implode(',', $this->userIds));
            return;
        }

        // Filter users based on their notification preferences
        $users = $users->filter(function (User $user) {
            return $this->isNotificationEnabledForUser($user);
        });

        if ($users->isEmpty()) {
            Log::info('SendNotificationJob: All users have disabled this notification type');
            return;
        }

        // Send in-app notifications
        foreach ($users as $user) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\SystemNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                    'metadata' => $this->metadata,
                    'created_at' => now()->toISOString(),
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log to notification history
            $this->logNotificationHistory($user->id, 'in_app', 'sent');
        }

        // Send push notifications
        if ($this->sendPush) {
            $this->sendPushNotifications($users);
        }

        // Send email notifications
        if ($this->sendEmail) {
            $this->sendEmailNotifications($users);
        }

        Log::info("SendNotificationJob completed: '{$this->title}' sent to {$users->count()} users");
    }

    /**
     * Send push notifications to users.
     */
    private function sendPushNotifications($users): void
    {
        try {
            $pushService = new WebPushService();
            $pushOptions = [
                'icon' => asset('assets/img/logo/favicon.png'),
                'badge' => asset('assets/img/logo/favicon.png'),
                'tag' => 'notification-' . Str::slug($this->title),
                'url' => url('/admin/notifications'),
                'data' => array_merge(['type' => $this->type], $this->metadata),
            ];

            if ($this->type === 'error' || ($this->metadata['priority'] ?? '') === 'urgent') {
                $pushOptions['requireInteraction'] = true;
            }

            $pushService->sendToUsers($users, $this->title, $this->message, $pushOptions);

            foreach ($users as $user) {
                $this->logNotificationHistory($user->id, 'push', 'sent');
            }
        } catch (\Exception $e) {
            Log::error('SendNotificationJob: Push notification failed: ' . $e->getMessage());
            foreach ($users as $user) {
                $this->logNotificationHistory($user->id, 'push', 'failed', $e->getMessage());
            }
        }
    }

    /**
     * Send email notifications to users.
     */
    private function sendEmailNotifications($users): void
    {
        foreach ($users as $user) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(
                    new \App\Mail\NotificationMail(
                        $user,
                        $this->title,
                        $this->message,
                        $this->type,
                        $this->metadata
                    )
                );
                $this->logNotificationHistory($user->id, 'email', 'sent');
            } catch (\Exception $e) {
                Log::error("SendNotificationJob: Email failed for user {$user->id}: " . $e->getMessage());
                $this->logNotificationHistory($user->id, 'email', 'failed', $e->getMessage());
            }
        }
    }

    /**
     * Check if notification is enabled for user based on preferences.
     */
    private function isNotificationEnabledForUser(User $user): bool
    {
        $preferences = $user->notification_preferences ?? [];
        $category = $this->metadata['type'] ?? 'general';

        // Default to enabled if no preferences set
        if (empty($preferences)) {
            return true;
        }

        // Check if category is disabled
        if (isset($preferences[$category]) && $preferences[$category] === false) {
            return false;
        }

        // Check if all notifications are disabled
        if (isset($preferences['all']) && $preferences['all'] === false) {
            return false;
        }

        return true;
    }

    /**
     * Log notification to history table.
     */
    private function logNotificationHistory(int $userId, string $channel, string $status, ?string $error = null): void
    {
        try {
            DB::table('notification_history')->insert([
                'user_id' => $userId,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'channel' => $channel,
                'status' => $status,
                'error_message' => $error,
                'metadata' => json_encode($this->metadata),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Don't fail the job if history logging fails
            Log::warning('SendNotificationJob: Failed to log notification history: ' . $e->getMessage());
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendNotificationJob failed: ' . $exception->getMessage(), [
            'user_ids' => $this->userIds,
            'title' => $this->title,
            'exception' => $exception,
        ]);
    }
}
