<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\Industry;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::active()->get();
        $settings = Setting::pluck('value', 'key');
        $categories = ProductCategory::active()->ordered()->get();
        $featuredProducts = Product::with('category')->active()->featured()->ordered()->limit(8)->get();
        
        $industries = Industry::active()->ordered()->get();

        $recentPosts = BlogPost::published()->latest('published_at')->limit(3)->get();
        
        $stats = [
            'years' => $settings['stat_years'] ?? '7',
            'products' => $settings['stat_products'] ?? '80',
            'industries' => $settings['stat_industries'] ?? '6',
            'reach' => $settings['stat_reach'] ?? 'Pan-India',
        ];

        $sections = \App\Models\PageSection::forPage('home')->get();

        return view('home', compact('heroSlides', 'settings', 'categories', 'featuredProducts', 'industries', 'recentPosts', 'stats', 'sections'));
    }
}
