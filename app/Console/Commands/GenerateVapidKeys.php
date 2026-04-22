<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:vapid-keys
                            {--show : Show existing keys}
                            {--generate : Generate new keys}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate or display VAPID keys for Web Push Notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('show')) {
            $this->showKeys();
            return 0;
        }

        if ($this->option('generate')) {
            $this->generateKeys();
            return 0;
        }

        // Default: show keys and instructions
        $this->info('Web Push VAPID Keys Manager');
        $this->newLine();
        $this->showKeys();
        $this->newLine();

        $this->warn('Note: For production use, install minishlink/web-push library:');
        $this->line('  composer require minishlink/web-push');
        $this->newLine();
        $this->line('Then use:');
        $this->line('  php artisan push:vapid-keys --generate');

        // Check if keys exist in .env
        $publicKey = env('VAPID_PUBLIC_KEY');
        $privateKey = env('VAPID_PRIVATE_KEY');

        if (!$publicKey || !$privateKey) {
            $this->newLine();
            $this->warn('VAPID keys not found in .env file.');
            $this->line('You can generate keys manually or use the --generate option.');
            $this->newLine();
            $this->line('To generate keys manually using OpenSSL:');
            $this->line('  1. openssl ecparam -genkey -name prime256v1 -out private_key.pem');
            $this->line('  2. openssl ec -in private_key.pem -pubout -outform DER | tail -c +8 | head -c 32 | base64');
            $this->line('  3. openssl ec -in private_key.pem -outform DER | tail -c +8 | head -c 32 | base64');
            $this->newLine();
            $this->line('Or use online tool: https://vapidkeys.com/');
        }

        return 0;
    }

    /**
     * Show existing VAPID keys
     */
    protected function showKeys()
    {
        $publicKey = env('VAPID_PUBLIC_KEY');
        $privateKey = env('VAPID_PRIVATE_KEY');

        $this->info('Current VAPID Keys Configuration:');
        $this->newLine();

        if ($publicKey) {
            $this->line('Public Key: ' . substr($publicKey, 0, 50) . '...');
        } else {
            $this->warn('Public Key: Not set');
        }

        if ($privateKey) {
            $this->line('Private Key: ' . substr($privateKey, 0, 20) . '... (hidden)');
        } else {
            $this->warn('Private Key: Not set');
        }

        if (!$publicKey || !$privateKey) {
            $this->newLine();
            $this->warn('⚠️  Push notifications will not work without VAPID keys!');
        }
    }

    /**
     * Generate new VAPID keys
     */
    protected function generateKeys()
    {
        $this->info('Generating VAPID keys...');
        $this->newLine();

        // Check if web-push library is available
        if (!class_exists(\Minishlink\WebPush\VAPID::class)) {
            $this->error('❌ minishlink/web-push library not found!');
            $this->newLine();
            $this->line('Please install the library first:');
            $this->line('  composer require minishlink/web-push');
            $this->newLine();
            $this->line('Alternative: Use online tool');
            $this->line('  1. Visit: https://vapidkeys.com/ (alternative tool)');
            $this->line('  2. Generate keys and copy to .env file');
            return;
        }

        try {
            // Generate VAPID keys using the library
            $keys = \Minishlink\WebPush\VAPID::createVapidKeys();

            $this->info('✅ Successfully generated VAPID keys!');
            $this->newLine();

            $this->line('Generated Keys:');
            $this->newLine();
            $this->line('📋 Public Key:');
            $this->line($keys['publicKey']);
            $this->newLine();
            $this->line('🔐 Private Key:');
            $this->line($keys['privateKey']);
            $this->newLine();

            $this->info('📝 Add these to your .env file:');
            $this->newLine();
            $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
            $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
            $this->line('VAPID_SUBJECT=' . config('app.url'));
            $this->newLine();

            $this->warn('⚠️  Important Steps:');
            $this->line('  1. Copy the keys above');
            $this->line('  2. Add them to your .env file');
            $this->line('  3. Run: php artisan config:clear');
            $this->line('  4. Refresh your browser');
            $this->newLine();

            $this->info('✅ Keys are ready to use for push notifications!');
        } catch (\Exception $e) {
            $this->error('❌ Failed to generate keys: ' . $e->getMessage());
            $this->newLine();
            $this->warn('The library method failed. Here are working alternatives:');
            $this->newLine();

            $this->info('✅ Option 1: Online Tool (Easiest - Recommended)');
            $this->line('  1. Open: https://vapidkeys.com/');
            $this->line('  2. Click "Generate" button');
            $this->line('  3. Copy Public Key and Private Key');
            $this->line('  4. Add to your .env file');
            $this->newLine();

            $this->info('✅ Option 2: Node.js (If you have Node.js installed)');
            $this->line('  Run: npx web-push generate-vapid-keys');
            $this->newLine();

            $this->info('✅ Option 3: Python Script');
            $this->line('  Run: python -m pip install pywebpush');
            $this->line('  Then use: python -c "from pywebpush import webpush; print(webpush.generate_vapid_keys())"');
            $this->newLine();

            $this->comment('After adding keys to .env, run: php artisan config:clear');
        }
    }

    /**
     * Fallback key generation (for development only)
     */
    protected function fallbackKeyGeneration()
    {
        $this->warn('⚠️  Using fallback method (NOT SECURE - Development only!)');
        $this->newLine();
        $this->line('For production, please install: composer require minishlink/web-push');
        $this->newLine();

        // This will NOT work with browsers, but provides instructions
        $this->line('To generate proper keys manually:');
        $this->line('  1. Visit: https://vapidkeys.com/');
        $this->line('  2. Generate VAPID keys');
        $this->line('  3. Copy the keys to your .env file');
    }
}
