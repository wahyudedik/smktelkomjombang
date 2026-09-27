<?php

namespace App\Console\Commands;

use App\Models\ThemeSetting;
use Illuminate\Console\Command;

class SyncThemeSettings extends Command
{
    protected $signature = 'theme:sync-settings
                            {--dry-run : Preview perubahan tanpa mengubah database}
                            {--theme= : Sync hanya tema tertentu (default: semua tema)}';

    protected $description = 'Sync theme_settings dari key lama (social_*) ke key baru (facebook_url, dll)';

    /**
     * Mapping key lama → key baru.
     */
    private const KEY_MAPPING = [
        'social_facebook'  => 'facebook_url',
        'social_instagram' => 'instagram_url',
        'social_youtube'   => 'youtube_url',
        'social_whatsapp'  => 'whatsapp_url',
    ];

    /**
     * Key baru yang belum ada di database (opsional, untuk insert jika kosong).
     */
    private const NEW_KEYS_DEFAULTS = [
        'twitter_url'      => '',
        'tiktok_url'       => '',
        'pinterest_url'    => '',
        'google_maps_url'  => '',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $themeFilter = $this->option('theme');

        if ($dryRun) {
            $this->warn('🔍 DRY-RUN MODE — Tidak ada perubahan yang disimpan ke database.');
            $this->newLine();
        }

        // Ambil semua tema unik dari database
        $themesQuery = ThemeSetting::select('theme')->distinct();

        if ($themeFilter) {
            $themesQuery->where('theme', $themeFilter);
        }

        $themes = $themesQuery->pluck('theme')->toArray();

        if (empty($themes)) {
            $this->error('Tidak ada theme_settings yang ditemukan di database.');

            return Command::FAILURE;
        }

        $this->info('Themes ditemukan: ' . implode(', ', $themes));
        $this->newLine();

        $totalSynced = 0;
        $totalSkipped = 0;
        $totalCreated = 0;

        foreach ($themes as $theme) {
            $result = $this->syncTheme($theme, $dryRun);
            $totalSynced += $result['synced'];
            $totalSkipped += $result['skipped'];
            $totalCreated += $result['created'];
        }

        // Summary
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('📊 Ringkasan Sync:');
        $this->info("   Themes diproses  : " . count($themes));
        $this->info("   Data di-sync     : {$totalSynced}");
        $this->info("   Data dilewati    : {$totalSkipped}");
        $this->info("   Key baru dibuat  : {$totalCreated}");
        $this->info('═══════════════════════════════════════');

        if ($dryRun) {
            $this->warn('⚠️  Ini adalah dry-run. Tidak ada perubahan yang disimpan.');
        } else {
            // Clear cache setelah sync
            ThemeSetting::clearAllCache();
            $this->info('🗑️  Cache theme settings dibersihkan.');
        }

        return Command::SUCCESS;
    }

    /**
     * Sync settings untuk satu tema.
     *
     * @return array{synced: int, skipped: int, created: int}
     */
    private function syncTheme(string $theme, bool $dryRun): array
    {
        $this->info("━━━ Theme: {$theme} ━━━");

        $settings = ThemeSetting::where('theme', $theme)->get();
        $settingsByKey = $settings->keyBy('key');

        $synced = 0;
        $skipped = 0;
        $created = 0;

        // 1. Sync key lama → key baru
        foreach (self::KEY_MAPPING as $oldKey => $newKey) {
            if (!isset($settingsByKey[$oldKey])) {
                // Key lama tidak ada, skip
                continue;
            }

            $oldValue = $settingsByKey[$oldKey]->value;
            $newValue = isset($settingsByKey[$newKey]) ? $settingsByKey[$newKey]->value : null;

            if ($oldValue === null || $oldValue === '') {
                // Key lama kosong, tidak perlu sync
                $this->line("  ⏭️  {$oldKey} kosong, dilewati.", 'comment');
                $skipped++;

                continue;
            }

            if ($newValue !== null && $newValue !== '') {
                // Key baru sudah ada isi, skip
                $this->line("  ⏭️  {$newKey} sudah ada nilai, dilewati.", 'comment');
                $skipped++;

                continue;
            }

            // Sync: copy old → new
            if ($dryRun) {
                $this->line("  📋 [DRY-RUN] {$oldKey} → {$newKey}: \"{$oldValue}\"", 'info');
            } else {
                // Ambil metadata dari key lama
                $oldSetting = $settingsByKey[$oldKey];

                if (isset($settingsByKey[$newKey])) {
                    // Update existing record
                    $settingsByKey[$newKey]->update(['value' => $oldValue]);
                } else {
                    // Create new record
                    ThemeSetting::create([
                        'theme'      => $theme,
                        'key'        => $newKey,
                        'value'      => $oldValue,
                        'type'       => $oldSetting->type,
                        'group_name' => $oldSetting->group_name,
                        'sort_order' => $oldSetting->sort_order,
                    ]);
                    $created++;
                }

                $this->line("  ✅ {$oldKey} → {$newKey}: \"{$oldValue}\"", 'info');
            }

            $synced++;
        }

        // 2. Buat key baru yang belum ada (dengan nilai kosong)
        foreach (self::NEW_KEYS_DEFAULTS as $newKey => $default) {
            if (!isset($settingsByKey[$newKey])) {
                if ($dryRun) {
                    $this->line("  📋 [DRY-RUN] Buat key baru: {$newKey} (kosong)", 'info');
                } else {
                    ThemeSetting::create([
                        'theme'      => $theme,
                        'key'        => $newKey,
                        'value'      => $default,
                        'type'       => 'text',
                        'group_name' => 'social',
                        'sort_order' => 50,
                    ]);

                    $this->line("  ✅ Key baru dibuat: {$newKey}", 'info');
                }

                $created++;
            }
        }

        $this->line("  📊 Synced: {$synced} | Skipped: {$skipped} | Created: {$created}");
        $this->newLine();

        return compact('synced', 'skipped', 'created');
    }
}
