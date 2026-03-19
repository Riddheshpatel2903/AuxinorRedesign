<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(9);
        $categories = BlogPost::published()->distinct()->pluck('category');
        return view('insights.index', compact('posts', 'categories'));
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
