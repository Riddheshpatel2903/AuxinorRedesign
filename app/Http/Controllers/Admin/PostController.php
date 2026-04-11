<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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
            'featured_image' => 'nullable|image|max:5120',
            'author' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        if (!isset($validated['is_published'])) $validated['is_published'] = false;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->imageService->upload($request->file('featured_image'), 'insights');
        }

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
            'featured_image' => 'nullable|image|max:5120',
            'author' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        if (!isset($validated['is_published'])) $validated['is_published'] = false;

        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($post->featured_image) {
                $this->imageService->delete($post->featured_image);
            }
            $validated['featured_image'] = $this->imageService->upload($request->file('featured_image'), 'insights');
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        // Delete image if it exists
        if ($post->featured_image) {
            $this->imageService->delete($post->featured_image);
        }
        
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
