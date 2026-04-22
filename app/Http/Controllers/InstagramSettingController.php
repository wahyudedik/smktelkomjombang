<?php

namespace App\Http\Controllers;

use App\Models\InstagramSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramSettingController extends Controller
{
    /**
     * Display Instagram settings management page
     */
    public function index(Request $request)
    {
        // Get latest settings from database (if any)
        $settings = InstagramSetting::latest()->first();

        // Capture OAuth data from session flash (OAuth redirect)
        // Fallback to query parameters if session is empty (for manual testing or external redirects)
        $urlAccessToken = session('oauth_access_token') ?? $request->query('access_token');
        $urlUserId = session('oauth_user_id') ?? $request->query('user_id');
        $urlPermissions = session('oauth_permissions') ?? $request->query('permissions');
        $urlExpiresIn = session('oauth_expires_in') ?? $request->query('expires_in');

        // Generate Instagram Business Login authorization URL
        $authorizationUrl = app(\App\Services\InstagramService::class)->getAuthorizationUrl();

        return view('superadmin.instagram-settings', compact(
            'settings',
            'urlAccessToken',
            'urlUserId',
            'urlPermissions',
            'urlExpiresIn',
            'authorizationUrl'
        ));
    }

    /**
     * Generate Instagram Business Login authorization URL
     * Returns URL for OAuth flow
     */
    public function getAuthorizationUrl()
    {
        try {
            $instagramService = app(\App\Services\InstagramService::class);
            $authUrl = $instagramService->getAuthorizationUrl();

            if (!$authUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate authorization URL. Please configure App ID first.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'authorization_url' => $authUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Generate authorization URL error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store or update Instagram settings
     * Updated for Instagram Platform API
     * 
     * Supports two modes:
     * 1. OAuth Setup Mode: Only App ID & App Secret (for OAuth flow)
     * 2. Full Manual Mode: All credentials including Access Token & User ID
     */
    public function store(Request $request)
    {
        Log::info('📥 Instagram Settings Store - Request received', [
            'has_access_token' => $request->filled('access_token'),
            'has_user_id' => $request->filled('user_id'),
            'sync_frequency' => $request->sync_frequency,
            'cache_duration' => $request->cache_duration,
            'auto_sync_enabled' => $request->has('auto_sync_enabled'),
            'all_inputs' => $request->except(['access_token']) // Log all except token
        ]);

        // Simplified: Only save sync settings and optionally tokens from manual setup
        $rules = [
            'access_token' => 'nullable|string',
            'user_id' => 'nullable|string',
            'sync_frequency' => 'required|integer|min:5|max:1440',
            'auto_sync_enabled' => 'nullable|in:on,off,1,0,true,false', // Accept checkbox values
            'cache_duration' => 'required|integer|min:300|max:86400',
        ];

        // If access_token provided, require user_id (manual setup)
        if ($request->filled('access_token')) {
            $rules['access_token'] = 'required|string';
            $rules['user_id'] = 'required|string';
            Log::info('📝 Manual token setup detected - requiring access_token and user_id');
        } else {
            Log::info('⚙️ Sync settings only - no tokens provided');
        }

        $request->validate($rules);
        Log::info('✅ Validation passed');

        try {
            // If manual token setup, test connection first
            if ($request->filled('access_token') && $request->filled('user_id')) {
                Log::info('🔍 Testing Instagram connection...');
                $accountInfo = $this->testInstagramConnectionWithInfo($request->access_token, $request->user_id);

                if (!$accountInfo) {
                    Log::error('❌ Instagram connection test failed');
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Instagram credentials. Please check your access token and user ID.'
                    ], 400);
                }

                Log::info('✅ Connection test passed', ['username' => $accountInfo['username'] ?? 'N/A']);

                // Calculate token expiry (Instagram User tokens are typically long-lived: 60 days)
                $tokenExpiresAt = now()->addDays(60);

                Log::info('💾 Saving settings WITH tokens to database...');

                // Create or update settings WITH tokens
                $settings = InstagramSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'access_token' => $request->access_token,
                        'user_id' => $request->user_id,
                        'username' => $accountInfo['username'] ?? null,
                        'account_type' => $accountInfo['account_type'] ?? null,
                        'is_active' => true,
                        'token_expires_at' => $tokenExpiresAt,
                        'sync_frequency' => $request->sync_frequency,
                        'auto_sync_enabled' => $request->boolean('auto_sync_enabled'),
                        'cache_duration' => $request->cache_duration,
                        'updated_by' => Auth::id(),
                    ]
                );

                Log::info('✅ Settings saved to database', ['id' => $settings->id, 'is_active' => $settings->is_active]);

                // Clear existing cache
                Cache::forget('instagram_posts');
                Cache::forget('instagram_analytics');
                Log::info('🗑️ Cache cleared');

                Log::info('🎉 Save complete - returning success response');
                return response()->json([
                    'success' => true,
                    'message' => 'Instagram settings saved successfully! Token will expire on ' . $tokenExpiresAt->format('M d, Y'),
                    'data' => $settings
                ]);
            } else {
                Log::info('💾 Saving sync settings only (no tokens)...');

                // Just update sync settings (no tokens)
                $settings = InstagramSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'sync_frequency' => $request->sync_frequency,
                        'auto_sync_enabled' => $request->boolean('auto_sync_enabled'),
                        'cache_duration' => $request->cache_duration,
                        'updated_by' => Auth::id(),
                    ]
                );

                Log::info('✅ Sync settings saved', ['id' => $settings->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Sync settings saved successfully!',
                    'data' => $settings
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Instagram settings save EXCEPTION', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save Instagram settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test Instagram connection
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'user_id' => 'required|string',
        ]);

        try {
            $isValid = $this->testInstagramConnection($request->access_token, $request->user_id);

            if ($isValid) {
                $accountInfo = $this->getAccountInfo($request->access_token, $request->user_id);

                return response()->json([
                    'success' => true,
                    'message' => 'Instagram connection successful!',
                    'account_info' => $accountInfo
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Instagram connection failed. Please check your credentials.'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Instagram connection test error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync Instagram data manually
     */
    public function syncData()
    {
        try {
            $settings = InstagramSetting::active()->first();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active Instagram settings found.'
                ], 400);
            }

            Log::info('🔄 Manual sync triggered via button');

            // Run sync command with force flag
            Artisan::call('instagram:sync', ['--force' => true]);
            $output = Artisan::output();

            // Get updated settings
            $settings = $settings->fresh();

            // Extract posts count from output
            preg_match('/Fetched \{(\d+)\} posts/', $output, $matches);
            $postsCount = $matches[1] ?? 0;

            Log::info('✅ Manual sync completed', ['posts_count' => $postsCount]);

            return response()->json([
                'success' => true,
                'message' => 'Instagram data synced successfully! Fetched ' . $postsCount . ' posts.',
                'last_sync' => $settings->last_sync,
                'posts_count' => $postsCount
            ]);
        } catch (\Exception $e) {
            Log::error('Instagram sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current settings
     */
    public function getSettings()
    {
        $settings = InstagramSetting::latest()->first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'No settings found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'sync_frequency' => $settings->sync_frequency,
            'cache_duration' => $settings->cache_duration,
            'auto_sync_enabled' => $settings->auto_sync_enabled,
            'is_active' => $settings->is_active,
            'last_sync' => $settings->last_sync,
            'token_expires_at' => $settings->token_expires_at,
            'username' => $settings->username
        ]);
    }

    /**
     * Deactivate Instagram settings
     */
    public function deactivate()
    {
        try {
            $settings = InstagramSetting::active()->first();

            if ($settings) {
                $settings->update([
                    'is_active' => false,
                    'updated_by' => Auth::id()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Instagram settings deactivated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Instagram deactivation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate Instagram settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test Instagram connection with provided credentials
     * Updated for Instagram Platform API v20.0
     */
    private function testInstagramConnection($accessToken, $userId)
    {
        try {
            $response = Http::timeout(10)->get("https://graph.instagram.com/v20.0/{$userId}", [
                'fields' => 'id,username,account_type',
                'access_token' => $accessToken
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Instagram API test error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test connection and get account info in one call
     * NEW: Combined method for Instagram Platform API
     */
    private function testInstagramConnectionWithInfo($accessToken, $userId)
    {
        try {
            $response = Http::timeout(15)->get("https://graph.instagram.com/v20.0/{$userId}", [
                'fields' => 'id,username,name,account_type,media_count,profile_picture_url',
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Instagram API connection test failed', [
                'status' => $response->status(),
                'error' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram API test error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get Instagram account information
     * Updated for Instagram Platform API v20.0
     */
    private function getAccountInfo($accessToken, $userId)
    {
        try {
            $response = Http::timeout(10)->get("https://graph.instagram.com/v20.0/{$userId}", [
                'fields' => 'id,username,name,account_type,media_count,profile_picture_url',
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram account info error: ' . $e->getMessage());
            return null;
        }
    }
}
