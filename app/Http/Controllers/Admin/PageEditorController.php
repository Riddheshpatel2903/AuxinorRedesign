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
        $sections = PageSection::forPage($pageSlug)->get();
        $globalSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.editor.editor', compact('sections', 'pageSlug', 'globalSettings'));
    }

    public function updateStyle(Request $r)
    {
        $sec = PageSection::findOrFail($r->section_id);
        $sec->update(['styles' => array_merge($sec->styles ?? [], is_array($r->styles) ? $r->styles : [])]);
        return response()->json(['ok' => true, 'style_string' => $sec->getStyleString()]);
    }

    public function updateContent(Request $r)
    {
        $sec = PageSection::findOrFail($r->section_id);
        $content = is_array($r->content) ? $r->content : [];
        
        // Intercept global settings
        foreach($content as $key => $value) {
            if (str_starts_with($key, 'el_setting:')) {
                $settingKey = substr($key, 11); // remove "el_setting:"
                \App\Models\Setting::updateOrCreate(['key' => $settingKey], ['value' => $value]);
                unset($content[$key]); // Don't save it to section
            }
        }
        
        $sec->update(['content' => array_merge($sec->content ?? [], $content)]);
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
        PageSection::forPage($slug)->update(['published_at' => now()]);
        return response()->json(['ok' => true, 'message' => 'Page published']);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('editor', 'public');
            return response()->json(['ok' => true, 'url' => url('storage/' . $path)]);
        }

        return response()->json(['ok' => false], 400);
    }
}
