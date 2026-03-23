<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::ordered()->get();
        return view('admin.industries.index', compact('industries'));
    }

    public function create()
    {
        return view('admin.industries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:industries,slug',
            'icon' => 'nullable|max:255',
            'desc' => 'nullable',
            'image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('industries', 'public');
        }

        Industry::create($validated);

        return redirect()->route('admin.industries.index')->with('success', 'Industry added successfully.');
    }

    public function edit(Industry $industry)
    {
        return view('admin.industries.edit', compact('industry'));
    }

    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:industries,slug,' . $industry->id,
            'icon' => 'nullable|max:255',
            'desc' => 'nullable',
            'image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (!isset($validated['is_active'])) $validated['is_active'] = false;

        if ($request->hasFile('image')) {
            if ($industry->image) {
                Storage::disk('public')->delete($industry->image);
            }
            $validated['image'] = $request->file('image')->store('industries', 'public');
        }

        $industry->update($validated);

        return redirect()->route('admin.industries.index')->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        if ($industry->image) {
            Storage::disk('public')->delete($industry->image);
        }
        $industry->delete();

        return redirect()->route('admin.industries.index')->with('success', 'Industry removed successfully.');
    }
}
