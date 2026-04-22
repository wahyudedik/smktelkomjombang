<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    /**
     * Subscribe user to push notifications
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url|max:500',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid subscription data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $deviceInfo = $request->userAgent() ?? 'Unknown';

            $subscription = PushSubscription::updateOrCreate(
                [
                    'endpoint' => $request->endpoint,
                ],
                [
                    'user_id' => $user->id,
                    'public_key' => $request->public_key,
                    'auth_token' => $request->auth_token,
                    'device_info' => $deviceInfo,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to push notifications',
                'subscription' => $subscription
            ]);
        } catch (\Exception $e) {
            Log::error('Push subscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to push notifications'
            ], 500);
        }
    }

    /**
     * Unsubscribe user from push notifications
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid endpoint',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            $subscription = PushSubscription::where('endpoint', $request->endpoint)
                ->where('user_id', $user->id)
                ->first();

            if ($subscription) {
                $subscription->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully unsubscribed from push notifications'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Push unsubscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe from push notifications'
            ], 500);
        }
    }

    /**
     * Get VAPID public key for client
     */
    public function vapidPublicKey()
    {
        $publicKey = config('services.vapid.public_key');

        if (!$publicKey) {
            return response()->json([
                'success' => false,
                'message' => 'VAPID keys not configured'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'publicKey' => $publicKey
        ]);
    }
}
