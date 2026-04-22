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
            $result = $this->sendNotification($subscription, $title, $body, $options);
            $results[] = $result;

            if ($result['success']) {
                $subscription->update(['last_notified_at' => now()]);
            }
        }

        return $results;
    }

    /**
     * Send push notification to multiple users
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
