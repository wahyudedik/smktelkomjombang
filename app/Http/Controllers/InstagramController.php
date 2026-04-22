<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Services\InstagramService;
use App\Models\InstagramSetting;

class InstagramController extends Controller
{
    protected $instagramService;

    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    /**
     * Display the Instagram activities page
     */
    public function index()
    {
        // Get Instagram posts from service
        $posts = $this->instagramService->getCachedPosts();

        return view('instagram.activities', compact('posts'));
    }

    /**
     * Refresh Instagram posts from API
     */
    public function refresh()
    {
        // Refresh posts using service
        $this->instagramService->refreshPosts();

        return redirect()->route('public.kegiatan')->with('success', 'Data Instagram berhasil diperbarui!');
    }

    /**
     * Get Instagram posts via AJAX
     */
    public function getPosts()
    {
        $posts = $this->instagramService->getCachedPosts();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Handle OAuth callback from Instagram Business Login
     * 
     * Instagram Business Login Flow:
     * 1. User clicks authorization URL
     * 2. User authorizes app
     * 3. Meta redirects here with authorization code
     * 4. We exchange code for short-lived token
     * 5. We exchange short-lived for long-lived token (60 days)
     * 6. We save settings and redirect to admin panel
     */
    public function handleOAuthCallback(Request $request)
    {
        Log::info('Instagram OAuth Callback Received', [
            'has_code' => $request->has('code'),
            'has_state' => $request->has('state'),
            'has_error' => $request->has('error'),
            'ip' => $request->ip()
        ]);

        // Get parameters from Meta redirect
        $code = $request->query('code');
        $state = $request->query('state');
        $error = $request->query('error');
        $errorReason = $request->query('error_reason');
        $errorDescription = $request->query('error_description');

        // Handle authorization denial/errors
        if ($error) {
            Log::error('Instagram OAuth Error', [
                'error' => $error,
                'reason' => $errorReason,
                'description' => $errorDescription
            ]);

            $message = 'OAuth Error: ';
            if ($error === 'access_denied' && $errorReason === 'user_denied') {
                $message = 'Authorization dibatalkan. Anda harus memberikan izin untuk melanjutkan.';
            } else {
                $message .= $errorDescription ?? $error;
            }

            return redirect()
                ->route('admin.superadmin.instagram-settings')
                ->with('error', $message);
        }

        // Authorization code must be present
        if (!$code) {
            Log::error('Instagram OAuth callback without authorization code');

            return redirect()
                ->route('admin.superadmin.instagram-settings')
                ->with('error', 'Authorization code tidak ditemukan. Silakan coba lagi.');
        }

        try {
            // STEP 1: Exchange authorization code for short-lived access token
            Log::info('Exchanging authorization code for access token');

            $tokenData = $this->instagramService->exchangeCodeForToken($code);

            if (!$tokenData || !isset($tokenData['access_token'])) {
                Log::error('Failed to exchange code for token', ['token_data' => $tokenData]);

                return redirect()
                    ->route('admin.superadmin.instagram-settings')
                    ->with('error', 'Gagal mendapatkan access token. Silakan coba lagi.');
            }

            $shortLivedToken = $tokenData['access_token'];
            $userId = $tokenData['user_id'];
            $permissions = $tokenData['permissions'] ?? '';

            Log::info('Short-lived token obtained', [
                'user_id' => $userId,
                'permissions' => $permissions
            ]);

            // STEP 2: Exchange short-lived token for long-lived token (60 days)
            Log::info('Exchanging short-lived token for long-lived token');

            $longLivedData = $this->instagramService->exchangeForLongLivedToken($shortLivedToken);

            if (!$longLivedData || !isset($longLivedData['access_token'])) {
                Log::error('Failed to exchange for long-lived token');

                return redirect()
                    ->route('admin.superadmin.instagram-settings')
                    ->with('error', 'Gagal mendapatkan long-lived token. Silakan coba lagi.');
            }

            $longLivedToken = $longLivedData['access_token'];
            $expiresIn = $longLivedData['expires_in'];

            Log::info('✅ Long-lived token obtained', [
                'expires_in' => $expiresIn . ' seconds (~60 days)'
            ]);

            // Auto-save token to database immediately (don't wait for user to click Save)
            try {
                Log::info('💾 Auto-saving OAuth token to database...');

                $tokenExpiresAt = now()->addSeconds($expiresIn);

                // Get account info for username
                $accountInfo = null;
                try {
                    $response = Http::timeout(15)->get("https://graph.instagram.com/v20.0/{$userId}", [
                        'fields' => 'id,username,name,account_type,media_count',
                        'access_token' => $longLivedToken
                    ]);

                    if ($response->successful()) {
                        $accountInfo = $response->json();
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not fetch account info during OAuth', ['error' => $e->getMessage()]);
                }

                $settings = InstagramSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'access_token' => $longLivedToken,
                        'user_id' => $userId,
                        'username' => $accountInfo['username'] ?? null,
                        'account_type' => $accountInfo['account_type'] ?? null,
                        'is_active' => true,
                        'token_expires_at' => $tokenExpiresAt,
                        'updated_by' => Auth::id(),
                    ]
                );

                Log::info('✅ OAuth token auto-saved successfully', [
                    'id' => $settings->id,
                    'user_id' => $userId,
                    'username' => $accountInfo['username'] ?? 'unknown',
                    'expires_at' => $tokenExpiresAt->format('Y-m-d H:i:s')
                ]);

                // Clear caches
                Cache::forget('instagram_posts');
                Cache::forget('instagram_analytics');
            } catch (\Exception $e) {
                Log::error('⚠️ Failed to auto-save OAuth token', [
                    'error' => $e->getMessage()
                ]);
                // Don't fail the whole flow, just log it
            }

            // Redirect to settings page with token data (for display only)
            return redirect()
                ->route('admin.superadmin.instagram-settings')
                ->with('success', 'Authorization berhasil! Access token telah disimpan dan valid selama 60 hari.')
                ->with('oauth_access_token', $longLivedToken)
                ->with('oauth_user_id', $userId)
                ->with('oauth_permissions', $permissions)
                ->with('oauth_expires_in', $expiresIn);
        } catch (\Exception $e) {
            Log::error('Instagram OAuth callback exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('admin.superadmin.instagram-settings')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get Instagram account information
     */
    public function getAccountInfo()
    {
        $accountInfo = $this->instagramService->getAccountInfo();

        return response()->json([
            'success' => true,
            'data' => $accountInfo
        ]);
    }

    /**
     * Validate Instagram connection
     */
    public function validateConnection()
    {
        $isValid = $this->instagramService->validateToken();

        return response()->json([
            'success' => $isValid,
            'message' => $isValid ? 'Koneksi Instagram valid' : 'Koneksi Instagram tidak valid'
        ]);
    }

    /**
     * Verify webhook (GET request from Meta)
     * Called once when you configure webhook in Meta Dashboard
     */
    public function verifyWebhook(Request $request)
    {
        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        // Get verify token from settings or config
        $settings = InstagramSetting::where('is_active', true)->first();
        $verifyToken = $settings->webhook_verify_token ??
            config('services.instagram.webhook_verify_token', 'mySchoolWebhook2025');

        Log::info('Instagram Webhook Verification Attempt', [
            'mode' => $mode,
            'token_received' => $token,
            'token_expected' => $verifyToken,
            'challenge' => $challenge,
            'ip' => $request->ip()
        ]);

        // Meta expects exactly this response for successful verification
        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('✅ Webhook verified successfully');
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::error('❌ Webhook verification failed', [
            'expected_token' => $verifyToken,
            'received_token' => $token
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle webhook notifications (POST request from Meta)
     * 
     * Meta Webhooks Requirements:
     * 1. Respond with 200 OK within 20 seconds
     * 2. Validate X-Hub-Signature-256 header
     * 3. Handle deduplication (retries if failed)
     * 4. Process batch of up to 1000 updates
     * 
     * Called when Instagram events happen (new post, comment, etc)
     */
    public function handleWebhook(Request $request)
    {
        // Get raw payload for signature verification
        $payload = $request->getContent();
        $data = $request->all();

        Log::info('Instagram Webhook Event Received', [
            'has_entry' => isset($data['entry']),
            'entry_count' => isset($data['entry']) ? count($data['entry']) : 0,
            'object' => $data['object'] ?? 'unknown',
            'ip' => $request->ip()
        ]);

        // IMPORTANT: Validate X-Hub-Signature-256 for security
        // This prevents unauthorized requests from fake sources
        $signature = $request->header('X-Hub-Signature-256');

        if ($signature) {
            $settings = InstagramSetting::active()->first();
            $appSecret = $settings->app_secret ?? config('services.instagram.app_secret');

            if ($appSecret) {
                $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

                // Use hash_equals to prevent timing attacks
                if (!hash_equals($expectedSignature, $signature)) {
                    Log::error('❌ Invalid webhook signature - possible security threat!', [
                        'received_signature' => substr($signature, 0, 20) . '...',
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                    return response('Forbidden', 403);
                }

                Log::info('✅ Webhook signature verified');
            } else {
                Log::warning('⚠️ Webhook received but no App Secret configured for validation');
            }
        } else {
            Log::warning('⚠️ Webhook received without X-Hub-Signature-256 header');
        }

        // Process webhook data
        // Meta may batch up to 1000 updates in one request
        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        $this->processWebhookChange($change);
                    }
                }
            }
        }

        // CRITICAL: Meta expects 200 response immediately (within 20 seconds)
        // If we don't respond, Meta will retry and may disable webhook
        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Process individual webhook change
     */
    private function processWebhookChange($change)
    {
        $field = $change['field'] ?? null;
        $value = $change['value'] ?? null;

        Log::info('Processing webhook change', [
            'field' => $field,
            'value_type' => is_array($value) ? 'array' : gettype($value)
        ]);

        try {
            // Handle different webhook events
            switch ($field) {
                case 'comments':
                    // New comment on a post
                    Log::info('New comment webhook', ['comment_data' => $value]);
                    // You can add auto-reply logic here
                    break;

                case 'media':
                    // New media post
                    Log::info('New media webhook', ['media_data' => $value]);
                    // Clear cache to refresh posts
                    Cache::forget('instagram_posts');
                    Cache::forget('instagram_analytics');
                    break;

                case 'mentions':
                    // Brand mentioned
                    Log::info('New mention webhook', ['mention_data' => $value]);
                    break;

                default:
                    Log::info('Unknown webhook field', ['field' => $field]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing webhook change', [
                'error' => $e->getMessage(),
                'field' => $field
            ]);
        }
    }
}
