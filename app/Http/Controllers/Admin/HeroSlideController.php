<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        return view('admin.hero-slides.index', ['slides' => HeroSlide::orderBy('sort_order')->get()]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'image_url' => ['required', function($attr, $val, $fail) {
                if (!filter_var($val, FILTER_VALIDATE_URL) && !str_starts_with($val, '/')) {
                    $fail('Please provide a valid image URL or upload an image.');
                }
            }],
            'overlay_opacity' => 'nullable|numeric|between:0,1'
        ]);
        HeroSlide::create($data + ['sort_order' => HeroSlide::max('sort_order') + 1]);
        return back()->with('success', 'Slide added.');
    }

    public function update(Request $r, HeroSlide $slide)
    {
        $data = $r->validate([
            'image_url' => ['required', function($attr, $val, $fail) {
                if (!filter_var($val, FILTER_VALIDATE_URL) && !str_starts_with($val, '/')) {
                    $fail('Please provide a valid image URL or upload an image.');
                }
            }],
            'overlay_opacity' => 'nullable|numeric|between:0,1',
            'is_active' => 'boolean'
        ]);
        $slide->update($data);
        return back()->with('success', 'Slide updated.');
    }

    public function destroy(HeroSlide $slide)
    {
        $slide->delete();
        return back()->with('success', 'Slide deleted.');
    }

    public function reorder(Request $r)
    {
        if (is_array($r->order)) {
            foreach ($r->order as $i => $id) {
                HeroSlide::where('id', $id)->update(['sort_order' => $i]);
            }
        }
        return response()->json(['ok' => true]);
    }

    public function upload(Request $r)
    {
        $r->validate(['image' => 'required|image|max:4096']);
        $path = $r->file('image')->store('hero', 'public');
        return response()->json(['url' => Storage::url($path)]);
    }
}
