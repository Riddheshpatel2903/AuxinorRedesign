<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $categories = ProductCategory::active()->ordered()->get();
        $featuredProducts = Product::with('category')->active()->featured()->ordered()->limit(8)->get();
        
        $industries = [
            (object)['name' => 'Speciality Chemicals', 'icon' => '🧪', 'desc' => 'High-purity chemicals for advanced manufacturing.', 'slug' => 'speciality-chemicals'],
            (object)['name' => 'Petrochemicals', 'icon' => '🛢️', 'desc' => 'Core building blocks for various industries.', 'slug' => 'petrochemicals'],
            (object)['name' => 'Pharmaceuticals', 'icon' => '💊', 'desc' => 'Active pharmaceutical ingredients and excipients.', 'slug' => 'pharmaceuticals'],
            (object)['name' => 'Dyes & Intermediates', 'icon' => '🎨', 'desc' => 'Colorants and intermediate compounds.', 'slug' => 'dyes-intermediates'],
            (object)['name' => 'Agrochemicals', 'icon' => '🌱', 'desc' => 'Chemicals for agricultural applications.', 'slug' => 'agrochemicals'],
            (object)['name' => 'Food Industry', 'icon' => '🍞', 'desc' => 'Food-grade additives and preservatives.', 'slug' => 'food-industry'],
        ];

        $recentPosts = BlogPost::published()->latest('published_at')->limit(3)->get();
        
        $stats = [
            'years' => $settings['stat_years'] ?? '7',
            'products' => $settings['stat_products'] ?? '80',
            'industries' => $settings['stat_industries'] ?? '6',
            'reach' => $settings['stat_reach'] ?? 'Pan-India',
        ];

        return view('home', compact('settings', 'categories', 'featuredProducts', 'industries', 'recentPosts', 'stats'));
    }
}
