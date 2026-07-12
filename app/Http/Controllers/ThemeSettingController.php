<?php

namespace App\Http\Controllers;

use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeSettingController extends Controller
{
    /**
     * List all registered themes.
     */
    public function index()
    {
        $themes = ThemeSetting::getRegisteredThemes();

        // Get settings count per theme
        $themeStats = [];
        foreach (array_keys($themes) as $theme) {
            $themeStats[$theme] = ThemeSetting::byTheme($theme)->count();
        }

        return view('settings.themes.index', compact('themes', 'themeStats'));
    }

    /**
     * Edit settings for a specific theme.
     */
    public function edit(string $theme)
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        $settings = ThemeSetting::byTheme($theme)->ordered()->get();

        // Group settings by group_name
        $grouped = $settings->groupBy('group_name');

        // Define available groups with labels
        $groups = [
            'general' => ['label' => 'Umum', 'icon' => 'fas fa-cog'],
            'hero' => ['label' => 'Hero Slider', 'icon' => 'fas fa-images'],
            'features' => ['label' => 'Fitur Unggulan', 'icon' => 'fas fa-star'],
            'programs' => ['label' => 'Program Peminatan', 'icon' => 'fas fa-graduation-cap'],
            'about' => ['label' => 'Kepala Sekolah', 'icon' => 'fas fa-user-tie'],
            'video' => ['label' => 'Video', 'icon' => 'fas fa-video'],
            'counter' => ['label' => 'Counter', 'icon' => 'fas fa-chart-bar'],
            'cta' => ['label' => 'CTA / Pendaftaran', 'icon' => 'fas fa-bullhorn'],
            'contact' => ['label' => 'Kontak & Jam Kerja', 'icon' => 'fas fa-phone'],
            'social' => ['label' => 'Social Media', 'icon' => 'fas fa-share-alt'],
            'menu' => ['label' => 'Navigasi Menu', 'icon' => 'fas fa-bars'],
        ];

        return view('settings.themes.edit', compact('theme', 'themes', 'settings', 'grouped', 'groups'));
    }

    /**
     * Update settings for a specific theme.
     */
    public function update(Request $request, string $theme)
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        $data = $request->all();

        // Remove CSRF token and method
        unset($data['_token'], $data['_method']);

        // Handle file uploads
        $uploadedFiles = $this->handleFileUploads($request, $theme);

        // Merge uploaded file paths with data
        foreach ($uploadedFiles as $key => $path) {
            $data[$key] = $path;
        }

        // Clean empty values
        $data = array_filter($data, fn ($value) => $value !== null && $value !== '');

        // Save to database
        ThemeSetting::saveThemeConfig($theme, $data);

        // Clear cache
        ThemeSetting::clearCache($theme);

        return redirect()->route('admin.themes.edit', $theme)
            ->with('success', "Settings tema [{$themes[$theme]['name']}] berhasil disimpan.");
    }

    /**
     * Seed default values from config file into database.
     */
    public function seedDefaults(string $theme)
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        $count = ThemeSetting::seedDefaults($theme);
        ThemeSetting::clearCache($theme);

        return redirect()->route('admin.themes.edit', $theme)
            ->with('success', "{$count} default settings berhasil di-import untuk tema [{$themes[$theme]['name']}].");
    }

    /**
     * Reset theme settings to config file defaults.
     * Deletes all DB settings and re-seeds from config.
     */
    public function resetDefaults(string $theme)
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        // Delete all existing settings for this theme
        ThemeSetting::where('theme', $theme)->delete();

        // Re-seed from config file
        $count = ThemeSetting::seedDefaults($theme);
        ThemeSetting::clearCache($theme);

        return redirect()->route('admin.themes.edit', $theme)
            ->with('success', "Settings tema [{$themes[$theme]['name']}] di-reset ke default ({$count} settings).");
    }

    /**
     * Handle file uploads for theme settings.
     */
    private function handleFileUploads(Request $request, string $theme): array
    {
        $uploadedFiles = [];

        $imageFields = [
            'logo', 'logo_light', 'favicon', 'headmaster_photo',
            'video_thumbnail', 'campus_life_headmaster_photo',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = "theme-settings/{$theme}/{$field}";

                // Delete old file if exists
                $oldValue = ThemeSetting::where('theme', $theme)->where('key', $field)->value('value');
                if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                    Storage::disk('public')->delete($oldValue);
                }

                $uploadedFiles[$field] = $file->store($path, 'public');
            }
        }

        // Handle hero_images (multiple)
        if ($request->hasFile('hero_images')) {
            $paths = [];
            foreach ($request->file('hero_images') as $index => $file) {
                $path = "theme-settings/{$theme}/hero";
                $paths[] = $file->store($path, 'public');
            }
            $uploadedFiles['hero_images'] = $paths;
        }

        return $uploadedFiles;
    }
}
