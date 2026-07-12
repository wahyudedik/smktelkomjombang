<?php

namespace Database\Seeders;

use App\Models\ThemeSetting;
use Illuminate\Database\Seeder;

class ThemeSettingsSeeder extends Seeder
{
    /**
     * Seed theme settings from config files into database.
     * Safe to run multiple times — only inserts missing keys.
     */
    public function run(): void
    {
        $themes = ['telkom', 'maudu'];

        foreach ($themes as $theme) {
            $count = ThemeSetting::seedDefaults($theme);
            $this->command?->info("Theme [{$theme}]: {$count} settings imported from config file.");
        }

        ThemeSetting::clearAllCache();
    }
}
