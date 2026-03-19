<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable',
            'content' => 'required',
            'category' => 'nullable|max:255',
            'featured_image' => 'nullable|url',
            'author' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        if (!isset($validated['is_published'])) $validated['is_published'] = false;

        BlogPost::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable',
            'content' => 'required',
            'category' => 'nullable|max:255',
            'featured_image' => 'nullable|url',
            'author' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        if (!isset($validated['is_published'])) $validated['is_published'] = false;

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
