<?php

namespace App\Http\Controllers;

use App\Models\ThemeSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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

        // Get active theme
        $activeTheme = config('app.default_theme', 'telkom');

        return view('settings.themes.index', compact('themes', 'themeStats', 'activeTheme'));
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
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

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
    public function resetDefaults(string $theme): RedirectResponse
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

    // ─── P3-6.1: Theme Preview ──────────────────────────────

    /**
     * Get the preview URL for a theme.
     * Used by the admin UI to open preview in iframe or new tab.
     */
    public function preview(string $theme): JsonResponse
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return response()->json(['error' => "Tema [{$theme}] tidak ditemukan."], 404);
        }

        // Map theme to its direct route URL
        $previewUrl = match ($theme) {
            'telkom' => route('telkom'),
            'maudu' => route('maudu'),
            default => url("/{$theme}"),
        };

        return response()->json([
            'success' => true,
            'url' => $previewUrl,
            'theme' => $theme,
            'theme_name' => $themes[$theme]['name'],
        ]);
    }

    // ─── P3-6.2: Theme Clone ────────────────────────────────

    /**
     * Clone settings from one theme to another.
     */
    public function cloneTheme(Request $request, string $theme): JsonResponse
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return response()->json(['error' => "Tema [{$theme}] tidak ditemukan."], 404);
        }

        $targetTheme = $request->input('target_theme');

        if (!$targetTheme || $targetTheme === $theme) {
            return response()->json(['error' => 'Target tema tidak valid.'], 422);
        }

        // Check if target theme exists in registered themes
        // Allow cloning to any theme name (even unregistered ones for future use)

        // Get all settings from source theme
        $sourceSettings = ThemeSetting::byTheme($theme)->get();

        if ($sourceSettings->isEmpty()) {
            return response()->json(['error' => 'Tema sumber tidak memiliki settings untuk di-clone.'], 422);
        }

        DB::beginTransaction();

        try {
            $clonedCount = 0;

            foreach ($sourceSettings as $setting) {
                ThemeSetting::updateOrCreate(
                    ['theme' => $targetTheme, 'key' => $setting->key],
                    [
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'group_name' => $setting->group_name,
                        'sort_order' => $setting->sort_order,
                    ]
                );
                $clonedCount++;
            }

            ThemeSetting::clearCache($targetTheme);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$clonedCount} settings berhasil di-clone dari [{$theme}] ke [{$targetTheme}].",
                'cloned_count' => $clonedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal clone tema: ' . $e->getMessage()], 500);
        }
    }

    // ─── P3-6.3: Import/Export Theme Settings ────────────────

    /**
     * Export theme settings as JSON file download.
     */
    public function exportTheme(string $theme): Response
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        $settings = ThemeSetting::byTheme($theme)->ordered()->get();

        $exportData = [
            'version' => '1.0',
            'theme' => $theme,
            'theme_name' => $themes[$theme]['name'],
            'exported_at' => now()->toISOString(),
            'exported_by' => auth()->user()->name ?? 'system',
            'settings_count' => $settings->count(),
            'settings' => $settings->map(fn($s) => [
                'key' => $s->key,
                'value' => $s->value,
                'type' => $s->type,
                'group_name' => $s->group_name,
                'sort_order' => $s->sort_order,
            ])->toArray(),
        ];

        $json = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $filename = "theme-export-{$theme}-" . now()->format('Y-m-d-His') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Show import form for theme settings.
     */
    public function importForm(string $theme)
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        return view('settings.themes.import', compact('theme', 'themes'));
    }

    /**
     * Process imported theme settings from JSON file.
     */
    public function importTheme(Request $request, string $theme): \Illuminate\Http\RedirectResponse
    {
        $themes = ThemeSetting::getRegisteredThemes();

        if (!isset($themes[$theme])) {
            return redirect()->route('admin.themes.index')
                ->with('error', "Tema [{$theme}] tidak ditemukan.");
        }

        $request->validate([
            'import_file' => 'required|file|json|max:5120', // 5MB max
            'import_mode' => 'required|in:merge,replace',
        ]);

        $file = $request->file('import_file');
        $json = file_get_contents($file->getRealPath());
        $importData = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'File JSON tidak valid: ' . json_last_error_msg());
        }

        if (!isset($importData['settings']) || !is_array($importData['settings'])) {
            return redirect()->back()->with('error', 'Format file tidak sesuai. Harus berisi array "settings".');
        }

        $importMode = $request->input('import_mode', 'merge');

        DB::beginTransaction();

        try {
            $importedCount = 0;

            // If replace mode, delete all existing settings first
            if ($importMode === 'replace') {
                ThemeSetting::where('theme', $theme)->delete();
            }

            foreach ($importData['settings'] as $setting) {
                ThemeSetting::updateOrCreate(
                    ['theme' => $theme, 'key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'type' => $setting['type'] ?? 'text',
                        'group_name' => $setting['group_name'] ?? 'general',
                        'sort_order' => $setting['sort_order'] ?? 0,
                    ]
                );
                $importedCount++;
            }

            ThemeSetting::clearCache($theme);

            DB::commit();

            $modeLabel = $importMode === 'replace' ? 'replace (timpa semua)' : 'merge (gabungkan)';

            return redirect()->route('admin.themes.edit', $theme)
                ->with('success', "{$importedCount} settings berhasil di-import ke [{$themes[$theme]['name']}] (mode: {$modeLabel}).");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ─── P3-6.4: Theme Comparison ────────────────────────────

    /**
     * Compare settings between two themes side-by-side.
     */
    public function compare(Request $request)
    {
        $themes = ThemeSetting::getRegisteredThemes();
        $themeKeys = array_keys($themes);

        $theme1 = $request->input('theme1', $themeKeys[0] ?? 'telkom');
        $theme2 = $request->input('theme2', $themeKeys[1] ?? 'maudu');

        // Get settings for both themes
        $settings1 = ThemeSetting::byTheme($theme1)->ordered()->get()->keyBy('key');
        $settings2 = ThemeSetting::byTheme($theme2)->ordered()->get()->keyBy('key');

        // Merge all unique keys
        $allKeys = array_unique(array_merge($settings1->keys()->toArray(), $settings2->keys()->toArray()));
        sort($allKeys);

        // Build comparison data
        $comparison = [];
        foreach ($allKeys as $key) {
            $s1 = $settings1->get($key);
            $s2 = $settings2->get($key);

            $val1 = $s1?->value;
            $val2 = $s2?->value;

            // For JSON values, compare decoded
            if ($s1?->type === 'json') {
                $val1 = $s1->value;
            }
            if ($s2?->type === 'json') {
                $val2 = $s2->value;
            }

            $comparison[] = [
                'key' => $key,
                'group' => $s1?->group_name ?? $s2?->group_name ?? 'general',
                'type' => $s1?->type ?? $s2?->type ?? 'text',
                'theme1_value' => $val1,
                'theme2_value' => $val2,
                'theme1_exists' => $s1 !== null,
                'theme2_exists' => $s2 !== null,
                'is_different' => $val1 !== $val2,
            ];
        }

        // Group by group_name
        $groupedComparison = collect($comparison)->groupBy('group');

        return view('settings.themes.compare', compact(
            'themes',
            'theme1',
            'theme2',
            'groupedComparison',
            'comparison'
        ));
    }

    // ─── P3-6.5: Theme Analytics ─────────────────────────────

    /**
     * Theme analytics dashboard.
     */
    public function analytics()
    {
        $themes = ThemeSetting::getRegisteredThemes();

        // Get page view data per theme
        $analyticsData = $this->getAnalyticsData();

        return view('settings.themes.analytics', compact('themes', 'analyticsData'));
    }

    /**
     * Get theme analytics data.
     */
    private function getAnalyticsData(): array
    {
        // Check if analytics table exists
        if (!DB::getSchemaBuilder()->hasTable('theme_analytics')) {
            return [
                'has_data' => false,
                'themes' => [],
                'daily_views' => [],
                'total_views' => [],
            ];
        }

        $themes = ThemeSetting::getRegisteredThemes();
        $themeAnalytics = [];

        foreach (array_keys($themes) as $theme) {
            $totalViews = DB::table('theme_analytics')
                ->where('theme', $theme)
                ->sum('page_views');

            $uniqueVisitors = DB::table('theme_analytics')
                ->where('theme', $theme)
                ->sum('unique_visitors');

            $avgTimeOnPage = DB::table('theme_analytics')
                ->where('theme', $theme)
                ->avg('avg_time_on_page');

            $bounceRate = DB::table('theme_analytics')
                ->where('theme', $theme)
                ->avg('bounce_rate');

            $themeAnalytics[$theme] = [
                'total_views' => (int) $totalViews,
                'unique_visitors' => (int) $uniqueVisitors,
                'avg_time_on_page' => round($avgTimeOnPage ?? 0, 1),
                'bounce_rate' => round($bounceRate ?? 0, 1),
            ];
        }

        // Daily views for last 30 days
        $dailyViews = DB::table('theme_analytics')
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->select('date', 'theme', 'page_views', 'unique_visitors')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($dayData) {
                $result = ['date' => $dayData->first()->date];
                foreach ($dayData as $row) {
                    $result[$row->theme . '_views'] = $row->page_views;
                    $result[$row->theme . '_visitors'] = $row->unique_visitors;
                }
                return $result;
            })
            ->values()
            ->toArray();

        return [
            'has_data' => true,
            'themes' => $themeAnalytics,
            'daily_views' => $dailyViews,
        ];
    }

    /**
     * API endpoint for theme analytics data (for Chart.js).
     */
    public function analyticsData(): JsonResponse
    {
        $data = $this->getAnalyticsData();
        return response()->json($data);
    }

    // ─── Helpers ─────────────────────────────────────────────

    /**
     * Handle file uploads for theme settings.
     */
    private function handleFileUploads(Request $request, string $theme): array
    {
        $uploadedFiles = [];

        $imageFields = [
            'logo',
            'logo_light',
            'favicon',
            'headmaster_photo',
            'video_thumbnail',
            'campus_life_headmaster_photo',
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
