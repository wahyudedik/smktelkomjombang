<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Providers\MenuServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedThemeMenus extends Command
{
    protected $signature = 'theme:seed-menus
                            {--theme= : Seed hanya tema tertentu (default: semua tema)}
                            {--dry-run : Preview perubahan tanpa mengubah database}
                            {--force : Timpa menu yang sudah ada untuk tema yang sama}';

    protected $description = 'Seed menu items dari config theme ke database (pages table)';

    /**
     * Map config URL format ke resolve_theme_url() compatible format.
     */
    private function resolveUrl(string $url): string
    {
        // URL sudah benar, return as-is
        return $url;
    }

    /**
     * Generate slug dari label.
     */
    private function makeSlug(string $label, string $theme, int $index): string
    {
        $base = Str::slug($label);
        return "menu-{$theme}-{$base}-{$index}";
    }

    /**
     * Seed header menus for a theme.
     */
    private function seedHeaderMenus(string $theme, array $menus, bool $dryRun, bool $force): int
    {
        $created = 0;
        $skipped = 0;

        foreach ($menus as $sortOrder => $item) {
            $label = $item['label'] ?? '';
            $url = $this->resolveUrl($item['url'] ?? '#');
            $targetBlank = ($item['target'] ?? '') === '_blank';
            $slug = $this->makeSlug($label, $theme, $sortOrder);

            // Cek apakah sudah ada menu dengan label + theme yang sama
            $existing = Page::where('menu_title', $label)
                ->where('theme', $theme)
                ->where('menu_position', 'header')
                ->first();

            if ($existing && !$force) {
                $this->line("  ⏭️  SKIP (sudah ada): \"{$label}\" [{$theme}/header]");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  📝 CREATE: \"{$label}\" → {$url} [header, order: {$sortOrder}]");
                $created++;

                // Dry run children
                if (!empty($item['children'])) {
                    foreach ($item['children'] as $childIdx => $child) {
                        $childLabel = $child['label'] ?? '';
                        $this->line("    └─ 📝 CREATE: \"{$childLabel}\" [child of \"{$label}\"]");
                    }
                }
                continue;
            }

            // Create or update parent menu item
            $pageData = [
                'title'             => $label,
                'slug'              => $slug,
                'content'           => "<p>Menu item: {$label}</p>",
                'status'            => 'published',
                'is_menu'           => true,
                'menu_title'        => $label,
                'menu_position'     => 'header',
                'theme'             => $theme,
                'menu_url'          => $url,
                'menu_target_blank' => $targetBlank,
                'menu_sort_order'   => $sortOrder,
                'user_id'           => 1,
                'published_at'      => now(),
            ];

            if ($existing && $force) {
                $existing->update($pageData);
                $parentPage = $existing;
                $this->line("  🔄 UPDATE: \"{$label}\" [{$theme}/header]");
            } else {
                $parentPage = Page::create($pageData);
                $this->line("  ✅ CREATED: \"{$label}\" [{$theme}/header, order: {$sortOrder}]");
                $created++;
            }

            // Seed children
            if (!empty($item['children'])) {
                foreach ($item['children'] as $childIdx => $child) {
                    $childLabel = $child['label'] ?? '';
                    $childUrl = $this->resolveUrl($child['url'] ?? '#');
                    $childTargetBlank = ($child['target'] ?? '') === '_blank';
                    $childSlug = $this->makeSlug($childLabel, $theme, $sortOrder * 100 + $childIdx);

                    $childExisting = Page::where('menu_title', $childLabel)
                        ->where('theme', $theme)
                        ->where('menu_position', 'header')
                        ->where('parent_id', $parentPage->id)
                        ->first();

                    if ($childExisting && !$force) {
                        $this->line("    └─ ⏭️  SKIP (sudah ada): \"{$childLabel}\"");
                        $skipped++;
                        continue;
                    }

                    $childData = [
                        'title'             => $childLabel,
                        'slug'              => $childSlug,
                        'content'           => "<p>Menu item: {$childLabel}</p>",
                        'status'            => 'published',
                        'is_menu'           => true,
                        'menu_title'        => $childLabel,
                        'menu_position'     => 'header',
                        'theme'             => $theme,
                        'parent_id'         => $parentPage->id,
                        'menu_url'          => $childUrl,
                        'menu_target_blank' => $childTargetBlank,
                        'menu_sort_order'   => $sortOrder * 100 + $childIdx,
                        'user_id'           => 1,
                        'published_at'      => now(),
                    ];

                    if ($childExisting && $force) {
                        $childExisting->update($childData);
                        $this->line("    └─ 🔄 UPDATE: \"{$childLabel}\"");
                    } else {
                        Page::create($childData);
                        $this->line("    └─ ✅ CREATED: \"{$childLabel}\" [child of \"{$label}\"]");
                        $created++;
                    }
                }
            }
        }

        return $created;
    }

    /**
     * Seed footer menus (related_links) for a theme.
     */
    private function seedFooterMenus(string $theme, array $links, bool $dryRun, bool $force): int
    {
        $created = 0;
        $skipped = 0;

        foreach ($links as $sortOrder => $link) {
            $label = $link['label'] ?? '';
            $url = $this->resolveUrl($link['url'] ?? '#');
            $slug = $this->makeSlug("footer-{$label}", $theme, $sortOrder);

            $existing = Page::where('menu_title', $label)
                ->where('theme', $theme)
                ->where('menu_position', 'footer')
                ->first();

            if ($existing && !$force) {
                $this->line("  ⏭️  SKIP (sudah ada): \"{$label}\" [{$theme}/footer]");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  📝 CREATE: \"{$label}\" → {$url} [footer, order: {$sortOrder}]");
                $created++;
                continue;
            }

            $pageData = [
                'title'             => $label,
                'slug'              => $slug,
                'content'           => "<p>Footer link: {$label}</p>",
                'status'            => 'published',
                'is_menu'           => true,
                'menu_title'        => $label,
                'menu_position'     => 'footer',
                'theme'             => $theme,
                'menu_url'          => $url,
                'menu_target_blank' => false,
                'menu_sort_order'   => $sortOrder,
                'user_id'           => 1,
                'published_at'      => now(),
            ];

            if ($existing && $force) {
                $existing->update($pageData);
                $this->line("  🔄 UPDATE: \"{$label}\" [{$theme}/footer]");
            } else {
                Page::create($pageData);
                $this->line("  ✅ CREATED: \"{$label}\" [{$theme}/footer, order: {$sortOrder}]");
                $created++;
            }
        }

        return $created;
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $themeFilter = $this->option('theme');
        $force = $this->option('force');

        if ($dryRun) {
            $this->warn('🔍 DRY-RUN MODE — Tidak ada perubahan yang disimpan ke database.');
            $this->newLine();
        }

        // Get available themes from config
        $themes = $themeFilter
            ? [$themeFilter => config("themes.available.{$themeFilter}")]
            : config('themes.available', []);

        if (empty($themes)) {
            $this->error("❌ Tidak ada tema ditemukan" . ($themeFilter ? " untuk '{$themeFilter}'" : '') . ".");
            return 1;
        }

        $totalCreated = 0;

        foreach ($themes as $themeSlug => $themeData) {
            $themeName = $themeData['name'] ?? $themeSlug;
            $this->info("🎨 Theme: {$themeName}");
            $this->line(str_repeat('-', 50));

            // Load theme config
            $themeConfig = config("themes.{$themeSlug}", []);

            if (empty($themeConfig)) {
                $this->warn("  ⚠️  Config tidak ditemukan untuk theme '{$themeSlug}', skip.");
                continue;
            }

            // Seed header menus
            $headerMenus = $themeConfig['menu'] ?? [];
            if (!empty($headerMenus)) {
                $this->line("📌 Header Menus:");
                $created = $this->seedHeaderMenus($themeSlug, $headerMenus, $dryRun, $force);
                $totalCreated += $created;
            } else {
                $this->line("📌 Header Menus: (kosong di config)");
            }

            // Seed footer menus (related_links)
            $footerLinks = $themeConfig['related_links'] ?? [];
            if (!empty($footerLinks)) {
                $this->line("📌 Footer Menus (Related Links):");
                $created = $this->seedFooterMenus($themeSlug, $footerLinks, $dryRun, $force);
                $totalCreated += $created;
            } else {
                $this->line("📌 Footer Menus: (kosong di config)");
            }

            $this->newLine();
        }

        // Clear menu cache
        if (!$dryRun && $totalCreated > 0) {
            MenuServiceProvider::clearMenuCache();
            $this->info('🗑️  Menu cache dibersihkan.');
        }

        // Summary
        $this->line(str_repeat('=', 50));
        if ($dryRun) {
            $this->info("📋 Ringkasan: {$totalCreated} item menu akan dibuat.");
        } else {
            $this->info("✅ Selesai! {$totalCreated} item menu berhasil di-seed ke database.");
        }

        return 0;
    }
}
