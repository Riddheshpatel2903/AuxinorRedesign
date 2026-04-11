<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageEditorController extends Controller
{
    public function index()
    {
        return view('admin.editor.index');
    }

    public function page($slug)
    {
        $pageSlug = $slug;
        $sections = PageSection::forPage($pageSlug)
            ->orWhere('page_slug', 'global')
            ->orderBy('sort_order')
            ->get();
            
        $globalSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.editor.editor', compact('sections', 'pageSlug', 'globalSettings'));
    }

    public function updateStyle(Request $r)
    {
        $sec = PageSection::findOrFail($r->input('section_id'));
        $styles = $r->input('styles');
        $sec->update(['styles' => array_merge($sec->styles ?? [], is_array($styles) ? $styles : [])]);
        return response()->json(['ok' => true, 'style_string' => $sec->getStyleString()]);
    }

    public function updateContent(Request $r)
    {
        $sec = PageSection::findOrFail($r->input('section_id'));
        $content = $r->input('content');
        $styles = $r->input('styles');
        
        $content = is_array($content) ? $content : [];
        $styles = is_array($styles) ? $styles : [];
        
        // Intercept global settings
        foreach($content as $key => $value) {
            if (str_starts_with($key, 'el_setting:')) {
                $settingKey = substr($key, 11);
                \App\Models\Setting::set($settingKey, $value);
                // Keep the key in section content for editor context
            }
        }
        
        $sec->update([
            'content' => array_merge($sec->content ?? [], $content),
            'styles' => array_merge($sec->styles ?? [], $styles)
        ]);

        return response()->json(['ok' => true]);
    }

    public function saveSettings(Request $r)
    {
        $settings = $r->input('settings', []);
        foreach ($settings as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }
        \Illuminate\Support\Facades\Log::info('saveSettings called', ['keys' => array_keys($settings)]);
        return response()->json(['ok' => true]);
    }

    public function saveAll(Request $r)
    {
        \Log::info("SaveAll started. Payload size: " . strlen($r->getContent()));
        $data = $r->input('sections', []);
        foreach ($data as $sectionData) {
            $sec = PageSection::find($sectionData['id']);
            if ($sec) {
                $newContent = $sectionData['content'] ?? [];
                $existingContent = $sec->content ?? [];
                
                // Normalizing keys to fix double-prefixing bugs
                $normalizedContent = [];
                foreach ($newContent as $k => $v) {
                    $cleanK = str_replace('el_el_setting:', 'el_setting:', $k);
                    $normalizedContent[$cleanK] = $v;
                }
                $newContent = $normalizedContent;

                // 1. Identify and preserve existing global settings that weren't sent by frontend
                foreach ($existingContent as $key => $value) {
                    if (str_starts_with($key, 'el_setting:') && !isset($newContent[$key])) {
                        $newContent[$key] = $value;
                    }
                }

                // 2. Process all global settings in the NEW content array and sync to Settings table
                foreach($newContent as $key => $value) {
                    if (str_starts_with($key, 'el_setting:')) {
                        $settingKey = substr($key, 11);
                        \App\Models\Setting::set($settingKey, $value);
                        // We keep the el_setting: key in the section content as well for the editor reference
                    }
                }

                $sec->update([
                    'content' => $newContent,
                    'styles' => $sectionData['styles'] ?? []
                ]);
            }
        }
        return response()->json(['ok' => true]);
    }

    public function toggleVisibility(Request $r)
    {
        $sec = PageSection::findOrFail($r->section_id);
        $sec->update(['is_visible' => $r->is_visible]);
        return response()->json(['ok' => true]);
    }

    public function reorder(Request $r)
    {
        if (is_array($r->order)) {
            foreach ($r->order as $i => $id) {
                PageSection::where('id', $id)->update(['sort_order' => $i]);
            }
        }
        return response()->json(['ok' => true]);
    }

    public function publish($slug)
    {
        // Update both page-specific and global sections
        PageSection::whereIn('page_slug', [$slug, 'global'])->update(['published_at' => now()]);
        
        // Clear caches to ensure changes reflect on live site
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Log::info("Full Cache cleared after publishing page/globals: {$slug}");
        } catch(\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Cache clear failed on publish: " . $e->getMessage());
        }
        
        return response()->json(['ok' => true, 'message' => 'Page and Global content published']);
    }

    public function uploadImage(Request $request, \App\Services\ImageService $imageService)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $url = $imageService->upload($request->file('image'), 'editor');
            return response()->json(['ok' => true, 'url' => $url]);
        }

        return response()->json(['ok' => false], 400);
    }
}
