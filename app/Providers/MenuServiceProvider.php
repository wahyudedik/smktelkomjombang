<?php

namespace App\Providers;

use App\Models\Page;
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
     */
    public function boot(): void
    {
        // Share menu data with all views (with caching for performance)
        View::composer(['*'], function ($view) {
            try {
                // Cache menu data for 1 hour to reduce database queries
                $headerMenus = cache()->remember('header_menus', 3600, function () {
                    return Page::menu()
                        ->menuPosition('header')
                        ->mainMenu()
                        ->orderBy('menu_sort_order')
                        ->with('children')
                        ->get();
                });

                $footerMenus = cache()->remember('footer_menus', 3600, function () {
                    return Page::menu()
                        ->menuPosition('footer')
                        ->mainMenu()
                        ->orderBy('menu_sort_order')
                        ->with('children')
                        ->get();
                });

                $view->with([
                    'headerMenus' => $headerMenus,
                    'footerMenus' => $footerMenus
                ]);
            } catch (\Exception $e) {
                // If database is not ready or table doesn't exist, provide empty arrays
                $view->with([
                    'headerMenus' => collect(),
                    'footerMenus' => collect()
                ]);
            }
        });
    }
}
