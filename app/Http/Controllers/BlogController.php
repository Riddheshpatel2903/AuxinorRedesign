<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\Page;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(9);
        $categories = BlogPost::published()->distinct()->pluck('category');
        $page = Page::query()->where('slug', 'insights')->active()->first();
        $sections = collect($page->content ?? []);
        $pageSections = $sections->keyBy('type');
        return view('insights.index', compact('posts', 'categories', 'pageSections', 'sections'));
    }

    public function show($slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $relatedPosts = BlogPost::published()->where('category', $post->category)
                            ->where('id', '!=', $post->id)
                            ->latest('published_at')->limit(3)->get();
        return view('insights.show', compact('post', 'relatedPosts'));
    }
}
