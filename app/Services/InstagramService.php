<?php

namespace App\Services;

use App\Models\InstagramSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InstagramService
{
    protected $accessToken;
    protected $userId;
    protected $baseUrl = 'https://graph.instagram.com/v20.0'; // Updated to latest version

    public function __construct()
    {
        // Get settings from database first, fallback to config
        $settings = InstagramSetting::active()->first();

        if ($settings && $settings->isComplete()) {
            $this->accessToken = $settings->access_token;
            $this->userId = $settings->user_id;
        } else {
            // Fallback to environment variables
            $this->accessToken = config('services.instagram.access_token');
            $this->userId = config('services.instagram.user_id');
        }
    }

    /**
     * Fetch Instagram posts from API
     * Using Instagram Platform API with Instagram Login
     */
    public function fetchPosts($limit = 20)
    {
        try {
            // Check if credentials are available
            if (empty($this->accessToken) || empty($this->userId)) {
                Log::warning('Instagram credentials not configured');
                return $this->getMockPosts();
            }

            // Real Instagram Platform API call
            $response = Http::timeout(30)->get($this->baseUrl . "/{$this->userId}/media", [
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp,like_count,comments_count,children{media_url,media_type}',
                'access_token' => $this->accessToken,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Transform API response to match expected format
                $posts = collect($data['data'] ?? [])->map(function ($post) {
                    return [
                        'id' => $post['id'],
                        'caption' => $post['caption'] ?? '',
                        'media_type' => $post['media_type'] ?? 'IMAGE',
                        'media_url' => $post['media_url'] ?? null,
                        'thumbnail_url' => $post['thumbnail_url'] ?? null,
                        'permalink' => $post['permalink'] ?? '#',
                        'timestamp' => isset($post['timestamp']) ? Carbon::parse($post['timestamp']) : now(),
                        'like_count' => $post['like_count'] ?? 0,
                        'comment_count' => $post['comments_count'] ?? 0, // Transform comments_count -> comment_count
                        'children' => $post['children'] ?? null,
                    ];
                })->toArray();

                // Log success
                Log::info('Instagram API: Successfully fetched ' . count($posts) . ' posts');

                return $posts;
            }

            // Log error with details
            $error = $response->json();
            Log::error('Instagram API error', [
                'status' => $response->status(),
                'error' => $error['error']['message'] ?? 'Unknown error',
                'code' => $error['error']['code'] ?? null
            ]);

            // Return mock data as fallback
            return $this->getMockPosts();
        } catch (\Exception $e) {
            Log::error('Instagram service error: ' . $e->getMessage());
            return $this->getMockPosts();
        }
    }

    /**
     * Get cached Instagram posts
     */
    public function getCachedPosts($limit = 20)
    {
        return Cache::remember('instagram_posts', 3600, function () use ($limit) {
            return $this->fetchPosts($limit);
        });
    }

    /**
     * Clear Instagram posts cache
     */
    public function clearCache()
    {
        Cache::forget('instagram_posts');
    }

    /**
     * Refresh Instagram posts
     */
    public function refreshPosts($limit = 20)
    {
        $this->clearCache();
        return $this->getCachedPosts($limit);
    }

    /**
     * Mock Instagram posts for demo purposes
     */
    private function getMockPosts()
    {
        return [
            [
                'id' => 1,
                'caption' => 'Kegiatan belajar mengajar di kelas 10A hari ini sangat seru! Siswa-siswi antusias mengikuti pelajaran Matematika dengan metode pembelajaran yang interaktif.',
                'media_url' => 'https://images.unsplash.com/photo-1523240798132-8757214e76ba?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example1/',
                'timestamp' => now()->subHours(2),
                'like_count' => 45,
                'comment_count' => 12
            ],
            [
                'id' => 2,
                'caption' => 'Kegiatan ekstrakurikuler basket berjalan dengan baik. Tim basket sekolah siap menghadapi turnamen antar sekolah yang akan diselenggarakan bulan depan!',
                'media_url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example2/',
                'timestamp' => now()->subHours(5),
                'like_count' => 78,
                'comment_count' => 23
            ],
            [
                'id' => 3,
                'caption' => 'Kunjungan ke museum sejarah dalam rangka pembelajaran sejarah Indonesia. Siswa-siswi sangat tertarik dengan koleksi museum dan mendapatkan wawasan baru.',
                'media_url' => 'https://images.unsplash.com/photo-1568667256549-094345857637?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example3/',
                'timestamp' => now()->subDay(),
                'like_count' => 92,
                'comment_count' => 34
            ],
            [
                'id' => 4,
                'caption' => 'Kegiatan upacara bendera hari Senin berjalan dengan khidmat. Semoga semangat nasionalisme selalu tertanam di hati siswa-siswi untuk membangun bangsa yang lebih baik.',
                'media_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example4/',
                'timestamp' => now()->subDays(2),
                'like_count' => 156,
                'comment_count' => 28
            ],
            [
                'id' => 5,
                'caption' => 'Kegiatan praktikum laboratorium kimia. Siswa-siswi melakukan eksperimen dengan penuh semangat dan kehati-hatian. Praktikum ini membantu memahami konsep kimia secara langsung.',
                'media_url' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example5/',
                'timestamp' => now()->subDays(3),
                'like_count' => 203,
                'comment_count' => 45
            ],
            [
                'id' => 6,
                'caption' => 'Kegiatan seni budaya. Siswa-siswi menampilkan tarian tradisional dengan kostum yang indah dan gerakan yang gemulai. Melestarikan budaya Indonesia melalui seni.',
                'media_url' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example6/',
                'timestamp' => now()->subDays(4),
                'like_count' => 187,
                'comment_count' => 56
            ],
            [
                'id' => 7,
                'caption' => 'Kegiatan olahraga pagi bersama. Siswa-siswi melakukan senam pagi untuk menjaga kesehatan dan kebugaran tubuh. Olahraga rutin penting untuk perkembangan fisik.',
                'media_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example7/',
                'timestamp' => now()->subDays(5),
                'like_count' => 134,
                'comment_count' => 31
            ],
            [
                'id' => 8,
                'caption' => 'Kegiatan perpustakaan. Siswa-siswi antusias membaca buku di perpustakaan sekolah. Membaca adalah jendela dunia yang membuka wawasan dan pengetahuan.',
                'media_url' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=500&h=500&fit=crop',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/example8/',
                'timestamp' => now()->subDays(6),
                'like_count' => 98,
                'comment_count' => 22
            ]
        ];
    }

    /**
     * Get Instagram account information
     * Using Instagram Platform API
     */
    public function getAccountInfo()
    {
        try {
            if (empty($this->accessToken) || empty($this->userId)) {
                return null;
            }

            // Real Instagram Platform API call
            $response = Http::timeout(15)->get($this->baseUrl . "/{$this->userId}", [
                'fields' => 'id,username,name,account_type,media_count,profile_picture_url',
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Instagram account info error', [
                'status' => $response->status(),
                'error' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram account info error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate Instagram access token
     * Test connection by fetching account info
     */
    public function validateToken()
    {
        try {
            // Check if we have valid access token and user ID
            if (empty($this->accessToken) || empty($this->userId)) {
                return false;
            }

            // Validate by trying to get account info
            $accountInfo = $this->getAccountInfo();

            if ($accountInfo && isset($accountInfo['id'])) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Instagram token validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get media insights
     * NEW: Instagram Platform API feature
     */
    public function getMediaInsights($mediaId, $metrics = ['engagement', 'impressions', 'reach'])
    {
        try {
            if (empty($this->accessToken)) {
                return null;
            }

            $response = Http::timeout(15)->get($this->baseUrl . "/{$mediaId}/insights", [
                'metric' => implode(',', $metrics),
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram media insights error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get account insights
     * NEW: Instagram Platform API feature
     */
    public function getAccountInsights($period = 'day', $metrics = ['impressions', 'reach', 'profile_views'])
    {
        try {
            if (empty($this->accessToken) || empty($this->userId)) {
                return null;
            }

            $response = Http::timeout(15)->get($this->baseUrl . "/{$this->userId}/insights", [
                'metric' => implode(',', $metrics),
                'period' => $period, // day, week, days_28, lifetime
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram account insights error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Refresh long-lived access token
     * Instagram Platform API: Tokens expire in 60 days and can be refreshed
     * 
     * @see https://developers.facebook.com/docs/instagram-platform/instagram-api-with-facebook-login/get-started#step-5--get-a-long-lived-token
     */
    public function refreshLongLivedToken()
    {
        try {
            $settings = InstagramSetting::active()->first();

            if (!$settings || !$settings->access_token) {
                Log::error('Cannot refresh token: No active Instagram settings found');
                return false;
            }

            // Exchange short-lived token for long-lived token
            // For Instagram Platform API with Instagram Login
            $response = Http::timeout(30)->get($this->baseUrl . '/refresh_access_token', [
                'grant_type' => 'ig_refresh_token',
                'access_token' => $settings->access_token
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Update token and expiry
                $settings->update([
                    'access_token' => $data['access_token'],
                    'token_expires_at' => now()->addSeconds($data['expires_in'] ?? 5184000) // 60 days default
                ]);

                Log::info('Instagram token refreshed successfully', [
                    'expires_in' => $data['expires_in'] ?? 5184000,
                    'new_expiry' => $settings->token_expires_at->format('Y-m-d H:i:s')
                ]);

                return true;
            }

            Log::error('Failed to refresh Instagram token', [
                'status' => $response->status(),
                'error' => $response->json()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Instagram token refresh error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * STEP 1: Generate Instagram Business Login Authorization URL
     * 
     * IMPORTANT: New scopes required by January 27, 2025
     * Old scopes (business_basic, etc.) will be deprecated!
     * 
     * New scopes:
     * - instagram_business_basic (required)
     * - instagram_business_content_publish
     * - instagram_business_manage_messages
     * - instagram_business_manage_comments
     * 
     * @param array $scopes List of permissions to request
     * @param string|null $state Optional CSRF protection state
     * @return string|false Authorization URL or false on failure
     */
    public function getAuthorizationUrl($scopes = null, $state = null)
    {
        try {
            // Get App ID from config (.env)
            $appId = config('services.instagram.app_id');
            $redirectUri = config('services.instagram.redirect_uri');

            if (!$appId) {
                Log::error('Cannot generate authorization URL: App ID not configured in .env');
                return false;
            }

            // Default scopes using NEW Instagram Business Login scopes
            if (empty($scopes)) {
                $scopes = [
                    'instagram_business_basic',
                    'instagram_business_content_publish',
                    'instagram_business_manage_comments',
                    'instagram_business_manage_messages'
                ];
            }

            $params = [
                'client_id' => $appId,
                'redirect_uri' => $redirectUri,
                'response_type' => 'code',
                'scope' => implode(',', $scopes)
            ];

            // Add state for CSRF protection
            if ($state) {
                $params['state'] = $state;
            }

            $authUrl = 'https://www.instagram.com/oauth/authorize?' . http_build_query($params);

            Log::info('Generated Instagram Business Login authorization URL', [
                'scopes' => $scopes,
                'has_state' => !empty($state)
            ]);

            return $authUrl;
        } catch (\Exception $e) {
            Log::error('Error generating Instagram authorization URL: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * STEP 2: Exchange authorization code for short-lived access token
     * 
     * Called after user authorizes and Meta redirects with code
     * Authorization code is valid for 1 hour and can only be used once
     * 
     * @param string $code Authorization code from redirect
     * @return array|false Token data or false on failure
     */
    public function exchangeCodeForToken($code)
    {
        try {
            // Get credentials from config (.env)
            $appId = config('services.instagram.app_id');
            $appSecret = config('services.instagram.app_secret');
            $redirectUri = config('services.instagram.redirect_uri');

            if (!$appId || !$appSecret) {
                Log::error('Cannot exchange code: App credentials not configured in .env');
                return false;
            }

            // POST to Instagram OAuth endpoint
            $response = Http::asForm()->timeout(30)->post('https://api.instagram.com/oauth/access_token', [
                'client_id' => $appId,
                'client_secret' => $appSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
                'code' => $code
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Response format: { "data": [{ "access_token": "...", "user_id": "...", "permissions": "..." }] }
                $tokenData = $data['data'][0] ?? $data;

                Log::info('✅ Instagram authorization code exchanged successfully', [
                    'has_access_token' => !empty($tokenData['access_token']),
                    'has_user_id' => !empty($tokenData['user_id']),
                    'permissions' => $tokenData['permissions'] ?? 'none'
                ]);

                return [
                    'access_token' => $tokenData['access_token'],
                    'user_id' => $tokenData['user_id'],
                    'permissions' => $tokenData['permissions'] ?? '',
                    'token_type' => 'bearer'
                ];
            }

            Log::error('❌ Failed to exchange Instagram authorization code', [
                'status' => $response->status(),
                'error' => $response->json()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Instagram code exchange error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * STEP 3: Exchange short-lived token for long-lived token
     * 
     * Short-lived token: Valid for 1 hour
     * Long-lived token: Valid for 60 days
     * Initial token exchange (1 hour -> 60 days)
     * 
     * @param string $shortLivedToken Short-lived access token
     * @return array|null Token data or null on failure
     */
    public function exchangeForLongLivedToken($shortLivedToken)
    {
        try {
            // Get credentials from config (.env)
            $appSecret = config('services.instagram.app_secret');

            if (!$appSecret) {
                Log::error('Cannot exchange token: App secret not configured in .env');
                return null;
            }

            $response = Http::timeout(30)->get('https://graph.instagram.com/access_token', [
                'grant_type' => 'ig_exchange_token',
                'client_secret' => $appSecret,
                'access_token' => $shortLivedToken
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('✅ Instagram token exchanged for long-lived token', [
                    'expires_in' => $data['expires_in'] ?? 0,
                    'token_type' => $data['token_type'] ?? 'bearer'
                ]);

                return [
                    'access_token' => $data['access_token'],
                    'token_type' => $data['token_type'] ?? 'bearer',
                    'expires_in' => $data['expires_in'] ?? 5184000 // 60 days
                ];
            }

            Log::error('❌ Failed to exchange for long-lived token', [
                'status' => $response->status(),
                'error' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Token exchange error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Publish single photo to Instagram
     * Requires instagram_content_publish permission
     * 
     * @param string $imageUrl Public image URL
     * @param string $caption Post caption
     * @return array|null Media creation result
     */
    public function publishPhoto($imageUrl, $caption = '')
    {
        try {
            if (empty($this->accessToken) || empty($this->userId)) {
                return null;
            }

            // Step 1: Create media container
            $response = Http::timeout(30)->post($this->baseUrl . "/{$this->userId}/media", [
                'image_url' => $imageUrl,
                'caption' => $caption,
                'access_token' => $this->accessToken
            ]);

            if (!$response->successful()) {
                Log::error('Failed to create media container', [
                    'status' => $response->status(),
                    'error' => $response->json()
                ]);
                return null;
            }

            $containerId = $response->json()['id'];

            // Step 2: Publish media container
            $publishResponse = Http::timeout(30)->post($this->baseUrl . "/{$this->userId}/media_publish", [
                'creation_id' => $containerId,
                'access_token' => $this->accessToken
            ]);

            if ($publishResponse->successful()) {
                Log::info('Instagram photo published successfully', [
                    'media_id' => $publishResponse->json()['id']
                ]);
                return $publishResponse->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram publish photo error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get comments on a media post
     * Requires instagram_manage_comments permission
     */
    public function getMediaComments($mediaId)
    {
        try {
            if (empty($this->accessToken)) {
                return null;
            }

            $response = Http::timeout(15)->get($this->baseUrl . "/{$mediaId}/comments", [
                'fields' => 'id,text,username,timestamp,like_count,replies{id,text,username}',
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram get comments error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Reply to a comment
     * Requires instagram_manage_comments permission
     */
    public function replyToComment($commentId, $message)
    {
        try {
            if (empty($this->accessToken)) {
                return null;
            }

            $response = Http::timeout(15)->post($this->baseUrl . "/{$commentId}/replies", [
                'message' => $message,
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Instagram reply comment error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a comment
     * Requires instagram_manage_comments permission
     */
    public function deleteComment($commentId)
    {
        try {
            if (empty($this->accessToken)) {
                return false;
            }

            $response = Http::timeout(15)->delete($this->baseUrl . "/{$commentId}", [
                'access_token' => $this->accessToken
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Instagram delete comment error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hide/Unhide a comment
     * Requires instagram_manage_comments permission
     */
    public function toggleCommentVisibility($commentId, $hide = true)
    {
        try {
            if (empty($this->accessToken)) {
                return false;
            }

            $response = Http::timeout(15)->post($this->baseUrl . "/{$commentId}", [
                'hide' => $hide,
                'access_token' => $this->accessToken
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Instagram toggle comment visibility error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check rate limit status
     * Instagram Platform: Calls within 24 hours = 4800 * Number of Impressions
     * 
     * @return array Rate limit info
     */
    public function getRateLimitStatus()
    {
        try {
            // This would typically come from response headers: X-App-Usage, X-Business-Use-Case-Usage
            // For now, return cached value
            return Cache::get('instagram_rate_limit', [
                'calls_made' => 0,
                'calls_remaining' => 4800,
                'reset_time' => now()->addHours(24)
            ]);
        } catch (\Exception $e) {
            Log::error('Instagram rate limit check error: ' . $e->getMessage());
            return [
                'calls_made' => 0,
                'calls_remaining' => 4800,
                'reset_time' => now()->addHours(24)
            ];
        }
    }

    /**
     * Check if we're approaching rate limit
     */
    public function isApproachingRateLimit()
    {
        $status = $this->getRateLimitStatus();

        if (!$status) {
            return false;
        }

        $percentageUsed = ($status['calls_made'] / ($status['calls_made'] + $status['calls_remaining'])) * 100;

        return $percentageUsed > 80; // 80% threshold
    }
}
