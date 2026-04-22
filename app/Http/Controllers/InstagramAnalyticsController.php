<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Cache;

class InstagramAnalyticsController extends Controller
{
    protected $instagramService;

    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    /**
     * Display Instagram analytics dashboard
     */
    public function index()
    {
        $analytics = $this->getAnalytics();
        $accountInfo = $this->instagramService->getAccountInfo();

        return view('instagram.analytics', compact('analytics', 'accountInfo'));
    }

    /**
     * Get Instagram analytics data
     */
    public function getAnalytics()
    {
        return Cache::remember('instagram_analytics', 3600, function () {
            $posts = $this->instagramService->getCachedPosts();

            // Calculate analytics
            $totalPosts = count($posts);
            $totalLikes = array_sum(array_column($posts, 'like_count'));
            $totalComments = array_sum(array_column($posts, 'comment_count'));
            $avgLikes = $totalPosts > 0 ? round($totalLikes / $totalPosts, 2) : 0;
            $avgComments = $totalPosts > 0 ? round($totalComments / $totalPosts, 2) : 0;

            // Engagement rate calculation
            $accountInfo = $this->instagramService->getAccountInfo();
            $followers = $accountInfo['followers_count'] ?? 1000;
            $engagementRate = $followers > 0 ? round((($totalLikes + $totalComments) / $followers) * 100, 2) : 0;

            // Top performing posts
            $topPosts = collect($posts)
                ->sortByDesc(function ($post) {
                    return $post['like_count'] + $post['comment_count'];
                })
                ->take(5)
                ->values()
                ->toArray();

            // Posts by day of week
            $postsByDay = $this->getPostsByDay($posts);

            // Recent activity
            $recentActivity = collect($posts)
                ->sortByDesc('timestamp')
                ->take(10)
                ->values()
                ->toArray();

            return [
                'total_posts' => $totalPosts,
                'total_likes' => $totalLikes,
                'total_comments' => $totalComments,
                'avg_likes' => $avgLikes,
                'avg_comments' => $avgComments,
                'engagement_rate' => $engagementRate,
                'followers_count' => $followers,
                'top_posts' => $topPosts,
                'posts_by_day' => $postsByDay,
                'recent_activity' => $recentActivity,
                'last_updated' => now()
            ];
        });
    }

    /**
     * Get posts distribution by day of week
     */
    private function getPostsByDay($posts)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $postsByDay = [];

        foreach ($days as $day) {
            $postsByDay[$day] = 0;
        }

        foreach ($posts as $post) {
            $dayName = $post['timestamp']->format('l');
            if (isset($postsByDay[$dayName])) {
                $postsByDay[$dayName]++;
            }
        }

        return $postsByDay;
    }

    /**
     * Get engagement metrics
     */
    public function getEngagementMetrics()
    {
        $analytics = $this->getAnalytics();

        return response()->json([
            'success' => true,
            'data' => [
                'engagement_rate' => $analytics['engagement_rate'],
                'avg_likes' => $analytics['avg_likes'],
                'avg_comments' => $analytics['avg_comments'],
                'total_interactions' => $analytics['total_likes'] + $analytics['total_comments']
            ]
        ]);
    }

    /**
     * Get top performing posts
     */
    public function getTopPosts()
    {
        $analytics = $this->getAnalytics();

        return response()->json([
            'success' => true,
            'data' => $analytics['top_posts']
        ]);
    }

    /**
     * Refresh analytics data
     */
    public function refreshAnalytics()
    {
        Cache::forget('instagram_analytics');
        $analytics = $this->getAnalytics();

        return response()->json([
            'success' => true,
            'message' => 'Analytics data refreshed successfully',
            'data' => $analytics
        ]);
    }
}
