<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GrapesEditorController extends Controller
{
    public function index()
    {
        $pages = PageContent::select('id','page_slug','page_title','is_published','last_edited_at','last_edited_by')
            ->orderBy('updated_at', 'desc')->get();
        return view('admin.grapesjs.pages-list', compact('pages'));
    }

    public function show(string $slug)
    {
        $page = PageContent::firstOrCreate(
            ['page_slug' => $slug],
            ['page_title' => Str::title(str_replace('-', ' ', $slug)), 'is_published' => false]
        );
        return view('admin.grapesjs.editor', ['page' => $page, 'slug' => $slug]);
    }

    public function load(string $slug)
    {
        $page = PageContent::where('page_slug', $slug)->first();
        if (!$page) {
            return response()->json(['components' => '[]', 'styles' => '[]']);
        }
        return response()->json([
            'components' => $page->components_json ?? '[]',
            'styles'     => $page->styles_json ?? '[]',
        ]);
    }

    public function save(Request $request, string $slug)
    {
        $request->validate([
            'components' => 'required|string',
            'styles'     => 'required|string',
            'html'       => 'required|string',
        ]);

        $page = PageContent::updateOrCreate(
            ['page_slug' => $slug],
            [
                'components_json' => $request->input('components'),
                'styles_json'     => $request->input('styles'),
                'html_output'     => $request->input('html'),
                'last_edited_by'  => auth()->user()->name ?? 'admin',
                'last_edited_at'  => now(),
            ]
        );

        $page->saveVersion(auth()->user()->name ?? 'admin');
        Cache::forget("page_content_{$slug}");

        return response()->json([
            'status'   => 'saved',
            'saved_at' => now()->toDateTimeString(),
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'files'   => 'required',
            'files.*' => 'image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120',
        ]);

        $files = $request->file('files') ?: $request->file('files[]');
        if (!$files) {
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        $files = is_array($files) ? $files : [$files];
        $uploaded = [];

        foreach ($files as $file) {
            $path = $file->store('editor-uploads', 'public');
            $uploaded[] = Storage::url($path);
            // Alternatively, return objects if needed
            /* $uploaded[] = [
                'src'  => Storage::url($path),
                'name' => $file->getClientOriginalName(),
                'type' => 'image',
            ]; */
        }

        return response()->json(['data' => $uploaded]);
    }

    public function listImages()
    {
        $files = Storage::disk('public')->files('editor-uploads');
        return response()->json(array_map(fn($f) => [
            'src'  => Storage::url($f),
            'name' => basename($f),
            'type' => 'image',
        ], $files));
    }

    public function togglePublish(string $slug)
    {
        $page = PageContent::where('page_slug', $slug)->firstOrFail();
        $page->update(['is_published' => !$page->is_published]);
        Cache::forget("page_content_{$slug}");
        return response()->json(['status' => 'ok', 'is_published' => $page->is_published]);
    }

    public function restoreVersion(string $slug, int $versionId)
    {
        $page    = PageContent::where('page_slug', $slug)->firstOrFail();
        $version = $page->versions()->findOrFail($versionId);
        $page->update([
            'components_json' => $version->components_json,
            'styles_json'     => $version->styles_json,
            'html_output'     => $version->html_output,
            'last_edited_at'  => now(),
            'last_edited_by'  => (auth()->user()->name ?? 'admin') . ' (restored)',
        ]);
        Cache::forget("page_content_{$slug}");
        return response()->json(['status' => 'restored']);
    }
}
