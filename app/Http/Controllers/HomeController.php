<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Industry;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = Schema::hasTable('hero_slides') ? HeroSlide::active()->get() : collect();

        $settings = cache()->remember('global_settings', 600, function () {
            if (!Schema::hasTable('settings')) {
                return [];
            }
            return Setting::pluck('value', 'key')->toArray();
        });

        $categories = Schema::hasTable('product_categories') ? ProductCategory::active()->ordered()->get() : collect();
        $featuredProducts = Schema::hasTable('products') ? Product::with('category')->active()->featured()->ordered()->limit(8)->get() : collect();

        $industries = Schema::hasTable('industries') ? Industry::active()->ordered()->get() : collect();

        $recentPosts = Schema::hasTable('blog_posts') ? BlogPost::published()->latest('published_at')->limit(3)->get() : collect();

        $stats = [
            'years'      => $settings['stat_years'] ?? '7',
            'products'   => $settings['stat_products'] ?? '80',
            'industries' => $settings['stat_industries'] ?? '6',
            'reach'      => $settings['stat_reach'] ?? 'Pan-India',
        ];

        $sections = collect();
        if (Schema::hasTable('pages')) {
            $page = Page::query()->where('slug', 'home')->active()->first();
            if ($page && $page->content) {
                $sections = collect($page->content);
            }
        } else {
            $page = null;
        }

        $pageSections = $sections->keyBy('type'); // Using type as key for JSON sections

        return view('home', compact(
            'heroSlides', 'settings', 'categories',
            'featuredProducts', 'industries', 'recentPosts',
            'stats', 'pageSections', 'sections'
        ));
    }
}
