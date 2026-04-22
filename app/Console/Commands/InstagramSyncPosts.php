<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstagramSetting;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InstagramSyncPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:sync
                            {--force : Force sync even if not time yet}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Instagram posts automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting Instagram sync...');

        // Get settings
        $settings = InstagramSetting::where('is_active', true)->first();

        if (!$settings) {
            $this->error('❌ No active Instagram settings found.');
            Log::warning('Instagram sync skipped: No active settings');
            return 1;
        }

        // Check if auto_sync is enabled
        if (!$settings->auto_sync_enabled && !$this->option('force')) {
            $this->warn('⚠️ Auto sync is disabled. Use --force to sync anyway.');
            return 0;
        }

        // Check if it's time to sync
        if (!$this->option('force') && $settings->last_sync) {
            $minutesSinceLastSync = $settings->last_sync->diffInMinutes(now());
            $syncFrequency = $settings->sync_frequency ?? 30;

            if ($minutesSinceLastSync < $syncFrequency) {
                $this->info("⏰ Not time yet. Last sync: {$minutesSinceLastSync} min ago, frequency: {$syncFrequency} min");
                return 0;
            }
        }

        try {
            $this->info('📥 Fetching posts from Instagram API...');

            // Clear cache
            Cache::forget('instagram_posts');
            Cache::forget('instagram_analytics');

            // Fetch posts
            $instagramService = app(InstagramService::class);
            $posts = $instagramService->fetchPosts(20);

            $this->info("✅ Fetched {" . count($posts) . "} posts");

            // Update last sync time
            $settings->updateLastSync();

            $this->info("🎉 Sync completed successfully!");
            $this->info("   Last sync: " . $settings->fresh()->last_sync->format('Y-m-d H:i:s'));

            // Show latest posts
            if (count($posts) > 0) {
                $this->newLine();
                $this->info('📸 Latest posts:');
                foreach (array_slice($posts, 0, 3) as $i => $post) {
                    $caption = isset($post['caption']) ? Str::limit($post['caption'], 50) : 'No caption';
                    $time = $post['timestamp']->diffForHumans();
                    $this->line("   " . ($i + 1) . ". {$caption} - {$time}");
                }
            }

            Log::info('Instagram sync completed', [
                'posts_count' => count($posts),
                'last_sync' => $settings->fresh()->last_sync
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Sync failed: " . $e->getMessage());
            Log::error('Instagram sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
