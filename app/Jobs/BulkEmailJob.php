<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\NotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 120;

    /**
     * Maximum number of emails per batch.
     */
    public int $batchSize = 50;

    /**
     * The user IDs to send email to.
     */
    public array $userIds;

    /**
     * Email subject.
     */
    public string $subject;

    /**
     * Email title (for display in template).
     */
    public string $title;

    /**
     * Email message body.
     */
    public string $message;

    /**
     * Email type (info, success, warning, error).
     */
    public string $type;

    /**
     * Additional metadata.
     */
    public array $metadata;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $userIds,
        string $subject,
        string $title,
        string $message,
        string $type = 'info',
        array $metadata = []
    ) {
        $this->userIds = $userIds;
        $this->subject = $subject;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Chunk users to avoid memory issues with large lists
        $chunks = array_chunk($this->userIds, $this->batchSize);

        $totalSent = 0;
        $totalFailed = 0;

        foreach ($chunks as $chunk) {
            $users = User::whereIn('id', $chunk)->get();

            foreach ($users as $user) {
                // Check email notification preference
                if (!$this->isEmailEnabledForUser($user)) {
                    continue;
                }

                try {
                    Mail::to($user->email)->send(
                        new NotificationMail(
                            $user,
                            $this->title,
                            $this->message,
                            $this->type,
                            $this->metadata
                        )
                    );
                    $totalSent++;
                } catch (\Exception $e) {
                    $totalFailed++;
                    Log::error("BulkEmailJob: Failed to send email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        Log::info("BulkEmailJob completed: {$totalSent} sent, {$totalFailed} failed for subject '{$this->subject}'");
    }

    /**
     * Check if email notification is enabled for user.
     */
    private function isEmailEnabledForUser(User $user): bool
    {
        $preferences = $user->notification_preferences ?? [];

        if (empty($preferences)) {
            return true;
        }

        // Check email channel preference
        if (isset($preferences['email']) && $preferences['email'] === false) {
            return false;
        }

        // Check category preference
        $category = $this->metadata['type'] ?? 'general';
        if (isset($preferences[$category]) && $preferences[$category] === false) {
            return false;
        }

        return true;
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('BulkEmailJob failed: ' . $exception->getMessage(), [
            'user_count' => count($this->userIds),
            'subject' => $this->subject,
            'exception' => $exception,
        ]);
    }
}
