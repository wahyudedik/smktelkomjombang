<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class WebPushService
{
    /**
     * Maximum number of retry attempts for failed push notifications.
     */
    protected int $maxRetries = 2;

    /**
     * Send push notification to user
     */
    public function sendToUser(User $user, string $title, string $body, array $options = [])
    {
        $subscriptions = $user->pushSubscriptions;

        if ($subscriptions->isEmpty()) {
            // Return empty array instead of false to avoid array_merge error
            return [];
        }

        $results = [];
        foreach ($subscriptions as $subscription) {
            $result = $this->sendNotificationWithRetry($subscription, $title, $body, $options);
            $results[] = $result;

            if ($result['success']) {
                $subscription->update(['last_notified_at' => now()]);
            }
        }

        return $results;
    }

    /**
     * Send push notification to multiple users (batch processing)
     */
    public function sendToUsers($users, string $title, string $body, array $options = [])
    {
        if ($users instanceof User) {
            $users = collect([$users]);
        }

        $results = [];
        foreach ($users as $user) {
            $userResults = $this->sendToUser($user, $title, $body, $options);
            // Ensure userResults is always an array before merging
            if (is_array($userResults)) {
                $results = array_merge($results, $userResults);
            }
        }

        return $results;
    }

    /**
     * Send push notification with retry mechanism.
     */
    protected function sendNotificationWithRetry(PushSubscription $subscription, string $title, string $body, array $options = [], int $attempt = 1)
    {
        $result = $this->sendNotification($subscription, $title, $body, $options);

        if (!$result['success'] && $attempt < $this->maxRetries) {
            // Wait before retry (exponential backoff: 5s, 15s, etc.)
            sleep(5 * $attempt);
            Log::info("Push notification retry #{$attempt} for subscription {$subscription->id}");
            return $this->sendNotificationWithRetry($subscription, $title, $body, $options, $attempt + 1);
        }

        return $result;
    }

    /**
     * Send batch push notifications to multiple users.
     * More efficient than sendToUsers for large batches — uses WebPush bulk API.
     */
    public function sendBatch(array $subscriptionsData): array
    {
        if (empty($subscriptionsData)) {
            return [];
        }

        $vapidPublicKey = config('services.vapid.public_key');
        $vapidPrivateKey = config('services.vapid.private_key');
        $vapidSubject = config('services.vapid.subject', config('app.url'));

        if (!$vapidPublicKey || !$vapidPrivateKey) {
            Log::warning('VAPID keys not configured for push notifications');
            return array_map(fn() => ['success' => false, 'error' => 'VAPID keys not configured'], $subscriptionsData);
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $vapidSubject,
                'publicKey' => $vapidPublicKey,
                'privateKey' => $vapidPrivateKey,
            ],
        ]);

        $results = [];

        foreach ($subscriptionsData as $data) {
            $subscription = $data['subscription'];
            $title = $data['title'];
            $body = $data['body'];
            $options = $data['options'] ?? [];

            try {
                $payload = json_encode([
                    'title' => $title,
                    'body' => $body,
                    'icon' => $options['icon'] ?? asset('assets/img/logo/favicon.png'),
                    'badge' => $options['badge'] ?? asset('assets/img/logo/favicon.png'),
                    'tag' => $options['tag'] ?? 'notification',
                    'url' => $options['url'] ?? url('/admin/notifications'),
                    'data' => $options['data'] ?? [],
                    'requireInteraction' => $options['requireInteraction'] ?? false,
                ]);

                $pushSubscription = Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'keys' => [
                        'p256dh' => $subscription->public_key,
                        'auth' => $subscription->auth_token,
                    ],
                ]);

                $report = $webPush->sendOneNotification($pushSubscription, $payload);

                if ($report->isSuccess()) {
                    $subscription->update(['last_notified_at' => now()]);
                    $results[] = ['success' => true, 'subscription_id' => $subscription->id];
                } else {
                    $error = $report->getReason();
                    if (strpos($error, '410') !== false || strpos($error, 'expired') !== false) {
                        $subscription->delete();
                        Log::info("Deleted invalid push subscription in batch: {$subscription->id}");
                    }
                    $results[] = ['success' => false, 'error' => $error, 'subscription_id' => $subscription->id];
                }
            } catch (\Exception $e) {
                Log::error("Batch push error for subscription {$subscription->id}: " . $e->getMessage());
                $results[] = ['success' => false, 'error' => $e->getMessage(), 'subscription_id' => $subscription->id];
            }
        }

        return $results;
    }

    /**
     * Send push notification using subscription
     */
    protected function sendNotification(PushSubscription $subscription, string $title, string $body, array $options = [])
    {
        try {
            $vapidPublicKey = config('services.vapid.public_key');
            $vapidPrivateKey = config('services.vapid.private_key');
            $vapidSubject = config('services.vapid.subject', config('app.url'));

            if (!$vapidPublicKey || !$vapidPrivateKey) {
                Log::warning('VAPID keys not configured for push notifications');
                return ['success' => false, 'error' => 'VAPID keys not configured'];
            }

            // Prepare notification payload
            $payload = json_encode([
                'title' => $title,
                'body' => $body,
                'icon' => $options['icon'] ?? asset('assets/img/logo/favicon.png'),
                'badge' => $options['badge'] ?? asset('assets/img/logo/favicon.png'),
                'tag' => $options['tag'] ?? 'notification',
                'url' => $options['url'] ?? url('/admin/notifications'),
                'data' => $options['data'] ?? [],
                'requireInteraction' => $options['requireInteraction'] ?? false,
            ]);

            // Create WebPush instance with VAPID authentication
            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => $vapidSubject,
                    'publicKey' => $vapidPublicKey,
                    'privateKey' => $vapidPrivateKey,
                ],
            ]);

            // Create subscription object with keys structure
            $pushSubscription = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->public_key,
                    'auth' => $subscription->auth_token,
                ],
            ]);

            // Send notification
            $report = $webPush->sendOneNotification($pushSubscription, $payload);

            if ($report->isSuccess()) {
                return ['success' => true, 'subscription_id' => $subscription->id];
            } else {
                // Handle errors
                $error = $report->getReason();

                // If subscription is invalid (410), delete it
                if (strpos($error, '410') !== false || strpos($error, 'expired') !== false) {
                    $subscription->delete();
                    Log::info("Deleted invalid push subscription: {$subscription->id}");
                }

                return [
                    'success' => false,
                    'error' => $error,
                    'subscription_id' => $subscription->id
                ];
            }
        } catch (\Exception $e) {
            Log::error('Push notification error: ' . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'error' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ];
        }
    }
}
