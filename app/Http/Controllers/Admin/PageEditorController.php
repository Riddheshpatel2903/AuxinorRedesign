<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\CMS\SectionSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * PageEditorController (REFACTORED - PHASE 5)
 * 
 * Now only handles the visual editor UI (layout, rendering, etc.)
 * 
 * All business logic delegated to:
 * - AdminPageController (page CRUD)
 * - SectionController (section management)
 * - SectionContentController (content editing)
 * - SectionStyleController (styling)
 * 
 * REMOVED:
 * ❌ Industry editing
 * ❌ Product editing
 * ❌ Category editing
 * ❌ Settings editing
 * ❌ Mixed element parsing
 * 
 * These are now in their dedicated controllers.
 */
class PageEditorController extends Controller
{
    /**
     * Show list of editable pages
     */
    public function index()
    {
        $pages = Page::latest('updated_at')->get()
            ->map(fn($page) => [
                'slug' => $page->slug,
                'title' => $page->title ?: ucfirst(str_replace('-', ' ', $page->slug)),
                'url' => $page->url,
                'sections_count' => $page->countSections(),
                'is_published' => $page->isPublished(),
            ]);

        return view('admin.editor.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Show editor for a page
     */
    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Load global settings
        $globalSettings = cache()->remember('global_settings', 600, function () {
            return \App\Models\Setting::pluck('value', 'key')->toArray();
        });

        // Load all section schemas
        $schemas = collect(config('sections'))
            ->map(fn($def, $type) => SectionSchema::for($type)->definition());

        return view('admin.editor.editor', [
            'page' => $page,
            'pageSlug' => $slug,
            'content' => $page->content ?? [],
            'globalSettings' => $globalSettings,
            'schemas' => $schemas,
            'previewUrl' => route('admin.editor.preview', $slug) . "?editor=1&_t=" . time(),
        ]);
    }

    /**
     * Preview page with JSON content
     */
    public function preview(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        return view('page', [
            'page' => $page,
            'content' => $page->content ?? []
        ]);
    }

    /**
     * Save page content (JSON)
     */
    public function save(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'content' => 'required|array',
        ]);

        // Optional: Perform schema validation here
        foreach ($validated['content'] as $section) {
            $type = $section['type'] ?? null;
            $schema = SectionSchema::tryFor($type);
            if ($schema) {
                // $errors = $schema->validate($section['props'] ?? []);
                // if (!empty($errors)) ...
            }
        }

        $page->update(['content' => $validated['content']]);

        return response()->json([
            'ok' => true,
            'message' => 'Page content saved successfully',
        ]);
    }

    /**
     * Publish page changes
     */
    public function publish(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->publish();
        Artisan::call('view:clear');

        return response()->json([
            'ok' => true,
            'message' => 'Page published successfully',
        ]);
    }

    /**
     * Upload image for a section
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        try {
            $path = $request->file('image')->store('editor', 'public');
            return response()->json([
                'url' => \Illuminate\Support\Facades\Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

