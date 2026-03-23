<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageSection;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->active()->firstOrFail();
        
        $data = [
            'page' => $page,
            'content' => $page->content ?? []
        ];

        if ($slug === 'industries') {
            $data['industries'] = \App\Models\Industry::active()->ordered()->get();
        }

        // Render the page (or a generic builder view if no specific view exists)
        if (view()->exists($slug)) {
            return view($slug, $data);
        }

        return view('page', $data);
    }
}
