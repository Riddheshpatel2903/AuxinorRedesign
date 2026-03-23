<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Enums\SectionType;
use Illuminate\Http\Request;

/**
 * AdminPageController
 * 
 * Manages CMS pages (create, read, update, delete)
 * 
 * Single Responsibility: Page CRUD operations only
 * Product/Industry/Settings management handled elsewhere
 */
class AdminPageController extends Controller
{
    /**
     * List all pages
     */
    public function index()
    {
        $pages = Page::latest('updated_at')->paginate(20);

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Show create page form
     */
    public function create()
    {
        return view('admin.pages.create', [
            'sectionTypes' => SectionType::options(),
        ]);
    }

    /**
     * Store new page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:pages,slug',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'is_active'         => 'boolean',
        ]);

        $page = Page::create($validated);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully');
    }

    /**
     * Show edit page form
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', [
            'page' => $page,
            'sections' => $page->content ?? [],
            'sectionTypes' => SectionType::options(),
        ]);
    }

    /**
     * Update page
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'is_active'         => 'boolean',
        ]);

        $page->update($validated);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully');
    }

    /**
     * Delete page
     */
    public function destroy(Page $page)
    {
        // Don't allow deleting if it has content
        if (!empty($page->content)) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete page with content. Clear content first.');
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully');
    }

    /**
     * Publish page (mark all sections as published)
     */
    public function publish(Page $page)
    {
        $page->publish();

        return response()->json([
            'ok' => true,
            'message' => 'Page published successfully',
        ]);
    }

    /**
     * Get page overview (quick stats for dashboard)
     */
    public function show(Page $page)
    {
        return response()->json([
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'is_active' => $page->is_active,
                'sections_count' => count($page->content ?? []),
                'is_published' => $page->isPublished(),
                'url' => $page->url,
            ],
        ]);
    }
}
