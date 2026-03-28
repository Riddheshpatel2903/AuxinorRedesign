<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function show(string $slug = 'home')
    {
        $html = Cache::remember("page_content_{$slug}", 3600, function () use ($slug) {
            return PageContent::where('page_slug', $slug)
                ->where('is_published', true)
                ->value('html_output');
        });

        if (!$html) {
            $sections = \App\Models\PageSection::forPage($slug)->get();

            if ($slug === 'home' || $slug === 'index') {
                $categories = \App\Models\ProductCategory::all();
                $featuredProducts = \App\Models\Product::with('category')->take(8)->get();
                $stats = [
                    'years' => 15,
                    'products' => 500,
                    'industries' => 12,
                    'reach' => '20+ Countries'
                ];
                $globalSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
                return view('home', compact('categories', 'featuredProducts', 'stats', 'globalSettings', 'sections'));
            }

            $view = "pages.{$slug}";
            if (view()->exists($view)) {
                return view($view, compact('sections'));
            }
            if (view()->exists($slug)) {
                return view($slug, compact('sections'));
            }
            abort(404);
        }

        return view('pages.dynamic', ['html' => $html, 'slug' => $slug, 'sections' => $sections]);
    }
}
