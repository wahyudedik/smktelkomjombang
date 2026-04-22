<?php

/**
 * Standalone VAPID Key Generator
 * 
 * This script generates VAPID keys for Web Push Notifications
 * Run: php generate-vapid-keys.php
 */

require __DIR__ . '/vendor/autoload.php';

try {
    $keys = \Minishlink\WebPush\VAPID::createVapidKeys();

    echo "=====================================\n";
    echo "  VAPID Keys Generated Successfully!\n";
    echo "=====================================\n\n";

    echo "Public Key:\n";
    echo $keys['publicKey'] . "\n\n";

    echo "Private Key:\n";
    echo $keys['privateKey'] . "\n\n";

    echo "=====================================\n";
    echo "Add these to your .env file:\n";
    echo "=====================================\n";
    echo "VAPID_PUBLIC_KEY=" . $keys['publicKey'] . "\n";
    echo "VAPID_PRIVATE_KEY=" . $keys['privateKey'] . "\n";
    echo "VAPID_SUBJECT=" . (getenv('APP_URL') ?: 'https://your-domain.com') . "\n\n";

    echo "Then run: php artisan config:clear\n";
} catch (\Exception $e) {
    echo "Error generating keys: " . $e->getMessage() . "\n\n";
    echo "Alternative: Visit https://vapidkeys.com/ to generate keys online.\n";
}
