<?php

namespace App\Providers;

use App\Models\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * Shares $headerMenus and $footerMenus with all views.
     * Menu is filtered by current_theme() — null (global) menus are included for all themes.
     * Falls back to empty collection if no DB menus exist (views should handle config fallback).
     */
    public function boot(): void
    {
        // Share menu data with all views (with per-theme caching for performance)
        View::composer(['*'], function ($view) {
            try {
                $theme = current_theme();

                // Cache per theme to avoid stale data when switching themes
                $headerMenus = cache()->remember(
                    "header_menus_{$theme}",
                    3600,
                    fn() => $this->getHeaderMenus($theme)
                );

                $footerMenus = cache()->remember(
                    "footer_menus_{$theme}",
                    3600,
                    fn() => $this->getFooterMenus($theme)
                );

                $view->with([
                    'headerMenus' => $headerMenus,
                    'footerMenus' => $footerMenus,
                ]);
            } catch (\Exception $e) {
                // If database is not ready or table doesn't exist, provide empty collections
                $view->with([
                    'headerMenus' => collect(),
                    'footerMenus' => collect(),
                ]);
            }
        });
    }

    /**
     * Get header menus for a specific theme from database.
     * Returns pages that are menu items, positioned in header, with no parent (main items).
     * Includes both theme-specific and global (null theme) menus.
     */
    private function getHeaderMenus(string $theme): Collection
    {
        return Page::menu()
            ->where(function ($query) use ($theme) {
                $query->where('theme', $theme)
                      ->orWhereNull('theme')
                      ->orWhere('theme', '');
            })
            ->menuPosition('header')
            ->mainMenu()
            ->orderBy('menu_sort_order')
            ->with('children')
            ->get();
    }

    /**
     * Get footer menus for a specific theme from database.
     * Returns pages that are menu items, positioned in footer, with no parent (main items).
     * Includes both theme-specific and global (null theme) menus.
     */
    private function getFooterMenus(string $theme): Collection
    {
        return Page::menu()
            ->where(function ($query) use ($theme) {
                $query->where('theme', $theme)
                      ->orWhereNull('theme')
                      ->orWhere('theme', '');
            })
            ->menuPosition('footer')
            ->mainMenu()
            ->orderBy('menu_sort_order')
            ->with('children')
            ->get();
    }

    /**
     * Clear menu cache for a specific theme.
     * Call this after creating/updating/deleting menu pages.
     */
    public static function clearMenuCache(?string $theme = null): void
    {
        if ($theme !== null) {
            cache()->forget("header_menus_{$theme}");
            cache()->forget("footer_menus_{$theme}");
        } else {
            // Clear for all registered themes
            foreach (available_themes() as $themeSlug) {
                cache()->forget("header_menus_{$themeSlug}");
                cache()->forget("footer_menus_{$themeSlug}");
            }
        }
    }
}
