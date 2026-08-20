<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startPush('styles'); ?>
        <!-- Animate.css for SweetAlert animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php $__env->stopPush(); ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                    <i class="fab fa-instagram mr-3"
                        style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    Instagram Integration
                </h1>
                <p class="text-slate-500 mt-1.5 text-sm">Manage your Instagram feed connection</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('docs.instagram-setup')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-book mr-2 text-slate-500"></i>
                    Guide
                </a>
                <a href="<?php echo e(route('public.kegiatan')); ?>"
                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-images mr-2 text-slate-500"></i>
                    Feed
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-4">
                        <?php if($settings && $settings->is_active): ?>
                            <div class="relative">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fab fa-instagram text-2xl text-green-600"></i>
                                </div>
                                <div
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white status-pulse">
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-semibold text-slate-900">Connected</h3>
                                    <?php if($settings->account_type): ?>
                                        <span
                                            class="px-2 py-0.5 bg-purple-50 text-purple-700 text-xs font-medium rounded-md">
                                            <?php echo e($settings->account_type); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if($settings && $settings->username): ?>
                                    <p class="text-sm text-slate-600 font-medium"><?php echo e($settings->username); ?></p>
                                <?php else: ?>
                                    <p class="text-sm text-slate-400 italic">Username not set</p>
                                <?php endif; ?>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Last sync:
                                    <?php echo e($settings->last_sync ? $settings->last_sync->diffForHumans() : 'Never'); ?>

                                </p>
                            </div>
                        <?php else: ?>
                            <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center">
                                <i class="fab fa-instagram text-2xl text-slate-300"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Not Connected</h3>
                                <p class="text-sm text-slate-500">Configure Instagram integration below</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($settings && $settings->is_active): ?>
                        <div class="flex gap-2">
                            <button id="syncBtn"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                title="Clear cache & fetch latest posts from Instagram (useful after deleting posts)">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Sync
                            </button>
                            <button id="deactivateBtn"
                                class="inline-flex items-center px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 text-sm font-medium rounded-lg transition-colors"
                                title="Disconnect Instagram integration">
                                <i class="fas fa-power-off mr-2"></i>
                                Disconnect
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($settings && $settings->is_active && $settings->token_expires_at): ?>
                <div
                    class="px-6 py-3 <?php echo e($settings->isTokenExpired() ? 'bg-red-50' : ($settings->isTokenExpiringSoon() ? 'bg-amber-50' : 'bg-green-50')); ?>">
                    <div class="flex items-center text-sm">
                        <?php if($settings->isTokenExpired()): ?>
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700">Token expired on
                                <?php echo e($settings->token_expires_at->format('M d, Y')); ?> - Please update your access
                                token</span>
                        <?php elseif($settings->isTokenExpiringSoon()): ?>
                            <i class="fas fa-exclamation-circle text-amber-500 mr-2"></i>
                            <span class="text-amber-700">Token expires on
                                <?php echo e($settings->token_expires_at->format('M d, Y')); ?> - Consider refreshing</span>
                        <?php else: ?>
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-green-700">Token valid until
                                <?php echo e($settings->token_expires_at->format('M d, Y')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <form id="instagramSettingsForm" class="space-y-4">
            <?php if($urlAccessToken): ?>
                <!-- Success Alert -->
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="flex gap-3">
                        <i class="fas fa-check-circle text-green-600 text-lg mt-0.5"></i>
                        <div class="text-sm">
                            <p class="font-semibold text-green-900 mb-1">Access Token Retrieved</p>
                            <p class="text-green-700">Now enter your <strong>User ID</strong>, test the connection, then
                                save.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Info Alert -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-600 text-lg mt-0.5"></i>
                    <div class="text-sm">
                        <p class="font-semibold text-blue-900 mb-1">Using Instagram Business Login (Updated Jan 2025)
                        </p>
                        <p class="text-blue-700">Use Instagram Business Login with new scopes or enter credentials
                            manually.
                            <a href="<?php echo e(route('docs.instagram-setup')); ?>"
                                class="underline font-semibold hover:text-blue-900">View setup guide</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- NEW: Quick Setup with OAuth -->
            <?php if($authorizationUrl && !($settings && $settings->is_active)): ?>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border-2 border-purple-200 p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bolt text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-900 mb-2">
                                <i class="fas fa-sparkles text-purple-500 mr-1"></i>
                                Quick Setup (Recommended)
                            </h3>
                            <p class="text-sm text-slate-700 mb-4">
                                Authorize with Instagram Business Login to automatically get your 60-day access token.
                                This is the easiest way to connect your Instagram Professional account.
                            </p>

                            <div class="flex flex-wrap items-center gap-3">
                                <a href="<?php echo e($authorizationUrl); ?>"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fab fa-instagram text-xl mr-2"></i>
                                    Connect with Instagram
                                </a>

                                <button type="button"
                                    onclick="document.getElementById('manualSetup').scrollIntoView({behavior: 'smooth'})"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-keyboard mr-2"></i>
                                    Or enter manually
                                </button>
                            </div>

                            <div class="mt-4 p-3 bg-white/50 rounded-lg border border-purple-200">
                                <p class="text-xs text-slate-600">
                                    <i class="fas fa-shield-alt text-purple-600 mr-1"></i>
                                    <strong>New scopes (Jan 27, 2025 update):</strong> instagram_business_basic,
                                    instagram_business_content_publish, instagram_business_manage_comments,
                                    instagram_business_manage_messages
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($urlPermissions): ?>
                <!-- OAuth Success Info -->
                <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                    <div class="flex gap-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-0.5"></i>
                        <div class="text-sm flex-1">
                            <p class="font-bold text-green-900 mb-2">Authorization Successful!</p>
                            <p class="text-green-700 mb-2">Permissions granted: <code
                                    class="bg-white px-2 py-1 rounded text-xs"><?php echo e(is_array($urlPermissions) ? implode(', ', $urlPermissions) : $urlPermissions); ?></code>
                            </p>
                            <?php if($urlExpiresIn): ?>
                                <p class="text-green-700">Token valid for: <strong><?php echo e(floor($urlExpiresIn / 86400)); ?>

                                        days</strong></p>
                            <?php endif; ?>
                            <p class="text-green-800 font-semibold mt-3">
                                <i class="fas fa-arrow-down mr-1"></i>
                                Now click "Test Connection" below, then "Save Settings"
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="manualSetup"></div>

            <!-- Card 1: Required Credentials -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Access Credentials</h3>
                        <p class="text-xs text-slate-500">
                            <span class="font-semibold text-blue-600">Option A:</span> Use OAuth (skip this, fill Card
                            2
                            instead) |
                            <span class="font-semibold text-slate-600">Option B:</span> Enter manually (fill both
                            fields)
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Access Token <span class="text-slate-400 text-xs">(required for manual setup)</span>
                        </label>
                        <input type="text" name="access_token" id="access_token"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="IGAAW... (leave empty if using OAuth)"
                            value="<?php if(isset($urlAccessToken) && $urlAccessToken): ?> <?php echo e($urlAccessToken); ?><?php elseif(isset($settings) && $settings && $settings->access_token): ?><?php echo e($settings->access_token); ?> <?php endif; ?>">
                        <p class="text-xs text-slate-500 mt-1.5">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Long-lived user access token from Instagram Platform API
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Instagram User ID <span class="text-slate-400 text-xs">(required for manual setup)</span>
                        </label>
                        <input type="text" name="user_id" id="user_id"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                            placeholder="17841428646148329 (leave empty if using OAuth)"
                            value="<?php if(isset($urlUserId) && $urlUserId): ?> <?php echo e($urlUserId); ?><?php elseif(isset($settings) && $settings && $settings->user_id): ?><?php echo e($settings->user_id); ?> <?php endif; ?>">
                        <p class="text-xs text-slate-500 mt-1.5">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Instagram Business/Creator Account ID from Meta Dashboard
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Sync & Cache Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Sync & Cache Settings</h3>
                            <p class="text-xs text-slate-500">Configure automatic synchronization and caching</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                        <span class="text-xs font-medium text-green-700">Customizable</span>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Sync Frequency</label>
                        <select name="sync_frequency" id="sync_frequency"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                            <option value="5" <?php echo e(($settings->sync_frequency ?? 30) == 5 ? 'selected' : ''); ?>>
                                Every 5 minutes</option>
                            <option value="15" <?php echo e(($settings->sync_frequency ?? 30) == 15 ? 'selected' : ''); ?>>
                                Every 15 minutes</option>
                            <option value="30" <?php echo e(($settings->sync_frequency ?? 30) == 30 ? 'selected' : ''); ?>>
                                Every 30 minutes</option>
                            <option value="60" <?php echo e(($settings->sync_frequency ?? 30) == 60 ? 'selected' : ''); ?>>
                                Every hour</option>
                            <option value="120" <?php echo e(($settings->sync_frequency ?? 30) == 120 ? 'selected' : ''); ?>>
                                Every 2 hours</option>
                            <option value="240" <?php echo e(($settings->sync_frequency ?? 30) == 240 ? 'selected' : ''); ?>>
                                Every 4 hours</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1.5">How often to fetch new posts</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Cache Duration</label>
                        <select name="cache_duration" id="cache_duration"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                            <option value="300" <?php echo e(($settings->cache_duration ?? 3600) == 300 ? 'selected' : ''); ?>>
                                5 minutes</option>
                            <option value="900" <?php echo e(($settings->cache_duration ?? 3600) == 900 ? 'selected' : ''); ?>>
                                15 minutes</option>
                            <option value="1800"
                                <?php echo e(($settings->cache_duration ?? 3600) == 1800 ? 'selected' : ''); ?>>30 minutes</option>
                            <option value="3600"
                                <?php echo e(($settings->cache_duration ?? 3600) == 3600 ? 'selected' : ''); ?>>1 hour</option>
                            <option value="7200"
                                <?php echo e(($settings->cache_duration ?? 3600) == 7200 ? 'selected' : ''); ?>>2 hours</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1.5">Cache lifetime for performance</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Auto Sync</label>
                        <div class="h-[42px] flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="auto_sync_enabled" id="auto_sync_enabled"
                                    class="sr-only peer" <?php echo e($settings->auto_sync_enabled ?? true ? 'checked' : ''); ?>>
                                <div
                                    class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-700">Enable</span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">Automatic background sync</p>
                    </div>
                </div>

                <!-- Info: How to Use Custom Sync -->
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-600 text-sm mt-0.5"></i>
                        <div class="flex-1 text-xs text-blue-800">
                            <p class="font-semibold mb-1">💡 How to customize sync settings:</p>
                            <ol class="list-decimal list-inside space-y-0.5 ml-1">
                                <li>Change <strong>Sync Frequency</strong> (how often posts are fetched)</li>
                                <li>Change <strong>Cache Duration</strong> (how long to cache data)</li>
                                <li>Toggle <strong>Auto Sync</strong> ON/OFF</li>
                                <li>Click <strong>"Save Settings"</strong> button below</li>
                                <li>Done! Settings will be applied immediately ✅</li>
                            </ol>
                            <p class="mt-2 text-blue-600 font-medium">
                                <i class="fas fa-arrow-down mr-1"></i>
                                These settings work independently from token configuration
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info: Cache Behavior -->
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-600 text-sm mt-0.5"></i>
                        <div class="flex-1 text-xs text-amber-800">
                            <p class="font-semibold mb-1">⚠️ Deleted Posts on Instagram?</p>
                            <p class="mb-2">If you delete posts on Instagram, they may still appear on the website
                                due to caching.</p>
                            <p class="font-semibold text-amber-900">Solution:</p>
                            <ol class="list-decimal list-inside space-y-0.5 ml-1">
                                <li>Click the <strong class="text-green-700">"Sync"</strong> button above (clears cache
                                    + fetches latest)</li>
                                <li>Or wait for cache to expire (based on your Cache Duration setting)</li>
                                <li>Or reduce <strong>Cache Duration</strong> to 5 minutes for faster updates</li>
                            </ol>
                            <p class="mt-2 text-green-700 font-medium">
                                <i class="fas fa-sync-alt mr-1"></i>
                                Pro tip: Always click "Sync" after deleting/editing posts on Instagram!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <!-- Info: What This Button Does -->
                <div class="mb-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center gap-2 text-sm mb-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <span class="font-semibold text-slate-800">Save Settings button will save:</span>
                    </div>
                    <ul class="text-xs text-slate-700 space-y-1 ml-6 list-disc">
                        <li><strong>Sync Frequency</strong> - How often to auto-sync (5-60 minutes)</li>
                        <li><strong>Cache Duration</strong> - How long to cache posts (5 min - 2 hours)</li>
                        <li><strong>Auto Sync Toggle</strong> - Enable/disable automatic background sync</li>
                        <li class="text-blue-600 font-medium">No token required to change these settings!</li>
                    </ul>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="saveSettingsBtn"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all shadow-lg shadow-blue-500/30">
                        <i class="fas fa-save mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </div>
        </form>

        <!-- Help Section -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-6 mt-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-900 mb-1">Need Help?</h3>
                    <p class="text-sm text-blue-700 mb-4">
                        Follow our comprehensive guide for Instagram API integration setup
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?php echo e(route('docs.instagram-setup')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-white hover:bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-book mr-2"></i>
                            Setup Guide
                        </a>
                        <a href="<?php echo e(route('public.kegiatan')); ?>"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-images mr-2"></i>
                            View Feed
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <style>
            /* Status pulse animation */
            .status-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                    transform: scale(1.05);
                }
            }

            /* Smooth transitions for inputs */
            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
            }

            /* Custom scrollbar for better aesthetics */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Instagram Settings JS Loaded');

                const form = document.getElementById('instagramSettingsForm');
                const saveBtn = document.getElementById('saveSettingsBtn');
                const syncBtn = document.getElementById('syncBtn');
                const deactivateBtn = document.getElementById('deactivateBtn');

                console.log('📋 Form elements:', {
                    form: !!form,
                    saveBtn: !!saveBtn,
                    syncBtn: !!syncBtn,
                    deactivateBtn: !!deactivateBtn
                });

                if (!form) {
                    console.error('❌ Form not found!');
                    return;
                }

                if (!saveBtn) {
                    console.error('❌ Save button not found!');
                    return;
                }

                // Welcome notification using global helpers
                setTimeout(() => {
                    <?php if(session('success')): ?>
                        showSuccess('Berhasil', '<?php echo e(session('success')); ?>');
                    <?php elseif(session('error')): ?>
                        showError('Error', '<?php echo e(session('error')); ?>');
                    <?php elseif(session('warning')): ?>
                        showAlert('Peringatan', '<?php echo e(session('warning')); ?>', 'warning');
                    <?php elseif(session('info')): ?>
                        showAlert('Info', '<?php echo e(session('info')); ?>', 'info');
                    <?php endif; ?>
                }, 500);

                // Save Settings - USE CAPTURE PHASE for priority
                form.addEventListener('submit', function(e) {
                    console.log('📝 Form submit event triggered');

                    // CRITICAL: Prevent default IMMEDIATELY
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    console.log('✅ Default prevented - processing form');
                    console.log('Save Settings form submitted');

                    const accessToken = document.getElementById('access_token').value.trim();
                    const userId = document.getElementById('user_id').value.trim();

                    // Simplified validation
                    // If access_token is provided, user_id must also be provided (manual token setup)
                    if (accessToken && !userId) {
                        showError('Incomplete Manual Setup',
                            'If you provide an Access Token, you must also provide the User ID.'
                        );
                        return;
                    }

                    if (userId && !accessToken) {
                        showError('Incomplete Manual Setup',
                            'If you provide a User ID, you must also provide the Access Token.'
                        );
                        return;
                    }

                    console.log('✅ Validation passed, proceeding to save');

                    saveBtn.disabled = true;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                    showLoading('Menyimpan pengaturan...', 'Mohon tunggu');

                    const formData = new FormData(form);

                    // Log form data for debugging
                    console.log('Form data:', {
                        has_access_token: !!accessToken,
                        has_user_id: !!userId,
                        sync_frequency: formData.get('sync_frequency'),
                        cache_duration: formData.get('cache_duration'),
                        auto_sync_enabled: formData.get('auto_sync_enabled')
                    });

                    fetch('<?php echo e(route('admin.superadmin.instagram-settings.store')); ?>', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            console.log('Save response status:', response.status);
                            console.log('Save response headers:', response.headers.get('content-type'));

                            // Check if response is JSON
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(data => ({
                                    status: response.status,
                                    ok: response.ok,
                                    data: data
                                }));
                            } else {
                                // Not JSON, probably redirected to login or error page
                                throw new Error('Response is not JSON. Status: ' + response.status +
                                    '. You may have been logged out.');
                            }
                        })
                        .then(result => {
                            closeLoading();
                            console.log('Save response data:', result.data);

                            // Handle validation errors (422)
                            if (result.status === 422) {
                                const errors = result.data.errors || {};
                                let errorList = '<ul class="text-left">';
                                for (let field in errors) {
                                    errorList += `<li><strong>${field}:</strong> ${errors[field][0]}</li>`;
                                }
                                errorList += '</ul>';

                                showError('❌ Validation Error',
                                    result.data.message + '<br><br>' + errorList);
                                return;
                            }

                            // Handle success response
                            if (result.data.success) {
                                showSuccess('✅ Pengaturan Tersimpan!', result.data.message).then(() => {
                                    // Reload to show updated settings
                                    setTimeout(() => {
                                        window.location.href =
                                            '<?php echo e(route('admin.superadmin.instagram-settings')); ?>';
                                    }, 500);
                                });
                            } else {
                                showError('❌ Gagal Menyimpan', result.data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Save error:', error);

                            // More helpful error message
                            if (error.message.includes('logged out')) {
                                showError('🔒 Session Expired',
                                    'Anda mungkin ter-logout. Silakan refresh halaman dan login kembali.<br><br>' +
                                    '<button onclick="window.location.reload()" class="btn btn-primary mt-2">Refresh Halaman</button>'
                                );
                            } else {
                                showError('❌ Gagal Menyimpan',
                                    'Terjadi kesalahan: ' + error.message +
                                    '<br>Cek console (F12) untuk detail.'
                                );
                            }
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Settings';
                        });
                }, true); // USE CAPTURE PHASE!

                // Sync Data
                if (syncBtn) {
                    syncBtn.addEventListener('click', function() {
                        syncBtn.disabled = true;
                        syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Syncing...';

                        showLoading('Syncing Instagram Data', 'Fetching latest posts...');

                        fetch('<?php echo e(route('admin.superadmin.instagram-settings.sync')); ?>', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                closeLoading();
                                if (data.success) {
                                    showSuccess('✅ Sinkronisasi Berhasil!', data.message).then(() => {
                                        setTimeout(() => location.reload(), 500);
                                    });
                                } else {
                                    showError('❌ Sinkronisasi Gagal', data.message);
                                }
                            })
                            .catch(error => {
                                closeLoading();
                                console.error('Error:', error);
                                showError('❌ Sync Failed',
                                    'Terjadi kesalahan saat sinkronisasi. Cek console untuk detail.');
                            })
                            .finally(() => {
                                syncBtn.disabled = false;
                                syncBtn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Sync Now';
                            });
                    });
                }

                // Deactivate
                if (deactivateBtn) {
                    deactivateBtn.addEventListener('click', function() {
                        showConfirm(
                            'Konfirmasi',
                            'Apakah Anda yakin ingin menonaktifkan integrasi Instagram?',
                            'Ya, Nonaktifkan',
                            'Batal'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                deactivateBtn.disabled = true;
                                deactivateBtn.innerHTML =
                                    '<i class="fas fa-spinner fa-spin mr-2"></i>Disconnecting...';

                                fetch('<?php echo e(route('admin.superadmin.instagram-settings.deactivate')); ?>', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            showSuccess('✅ Instagram Disconnected!', data.message)
                                                .then(() => {
                                                    setTimeout(() => location.reload(), 500);
                                                });
                                        } else {
                                            showError('❌ Disconnect Failed', data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        showError('❌ Disconnect Failed',
                                            'Terjadi kesalahan saat memutus koneksi');
                                    })
                                    .finally(() => {
                                        deactivateBtn.disabled = false;
                                        deactivateBtn.innerHTML =
                                            '<i class="fas fa-power-off mr-2"></i>Disconnect';
                                    });
                            }
                        });
                    });
                }

                // ============================================
                // SWEETALERT HELPER FUNCTIONS
                // ============================================
                // All helper functions (showSuccess, showError, showAlert, showConfirm, showLoading, closeLoading)
                // are now globally available from app.js - removed local definitions for consistency

                // Add confetti script
                const confettiScript = document.createElement('script');
                confettiScript.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js';
                document.head.appendChild(confettiScript);
            });
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\superadmin\instagram-settings.blade.php ENDPATH**/ ?>