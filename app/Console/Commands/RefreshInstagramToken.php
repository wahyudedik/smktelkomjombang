<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InstagramService;
use App\Models\InstagramSetting;

class RefreshInstagramToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:refresh-token 
                            {--force : Force refresh even if not expiring soon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Instagram long-lived access token (60 days validity)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Checking Instagram token status...');

        $settings = InstagramSetting::active()->first();

        if (!$settings) {
            $this->error('❌ No active Instagram settings found');
            return 1;
        }

        // Check if token needs refresh
        if (!$this->option('force')) {
            if (!$settings->isTokenExpiringSoon() && !$settings->isTokenExpired()) {
                $this->info('✅ Token is still valid');
                $this->info('   Expires: ' . $settings->token_expires_at->format('Y-m-d H:i:s'));
                $this->info('   Days remaining: ' . $settings->token_expires_at->diffInDays(now()));
                return 0;
            }
        }

        if ($settings->isTokenExpired()) {
            $this->warn('⚠️  Token has expired!');
        } else {
            $this->warn('⚠️  Token expiring soon (' . $settings->token_expires_at->diffInDays(now()) . ' days)');
        }

        $this->info('🔄 Refreshing token...');

        $instagramService = new InstagramService();
        $success = $instagramService->refreshLongLivedToken();

        if ($success) {
            $settings->refresh();
            $this->info('✅ Token refreshed successfully!');
            $this->info('   New expiry: ' . $settings->token_expires_at->format('Y-m-d H:i:s'));
            $this->info('   Valid for: ' . $settings->token_expires_at->diffInDays(now()) . ' days');
            return 0;
        } else {
            $this->error('❌ Failed to refresh token');
            $this->error('   Check logs for details: storage/logs/laravel.log');
            return 1;
        }
    }
}
