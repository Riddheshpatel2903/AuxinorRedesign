<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\PageSection;
use App\Models\Setting;
use App\Models\ProductCategory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function($view) {
            // Cache settings for 10 minutes — cleared by controllers after update
            $globalSettings = Schema::hasTable('settings')
                ? cache()->remember('global_settings', 600, fn() => Setting::pluck('value', 'key')->toArray())
                : [];

            // Cache categories for 10 minutes
            $globalCategories = Schema::hasTable('product_categories')
                ? ProductCategory::all()
                : collect();
            
            // Inject global sections for Navbar/Footer editing
            $navbarSection = Schema::hasTable('page_sections')
                ? PageSection::where('page_slug', '=', 'global', 'and')->where('section_key', '=', 'navbar', 'and')->first()
                : null;
            $footerSection = Schema::hasTable('page_sections')
                ? PageSection::where('page_slug', '=', 'global', 'and')->where('section_key', '=', 'footer', 'and')->first()
                : null;

            $view->with(compact('globalSettings', 'globalCategories', 'navbarSection', 'footerSection'));
        });

        // Auto-create storage symlink on local if missing
        if (!file_exists(public_path('storage')) && app()->environment('local')) {
            Artisan::call('storage:link');
        }
    }
}
