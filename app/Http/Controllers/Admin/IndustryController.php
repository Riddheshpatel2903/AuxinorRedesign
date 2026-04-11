<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $industries = Industry::orderBy('order')->get();
        return view('admin.industries.index', compact('industries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.industries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'products' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'is_active' => 'boolean'
        ]);

        $industry = new Industry();
        $industry->name = $validated['name'];
        $industry->description = $validated['description'];
        $industry->products = $validated['products'];
        $industry->icon = $validated['icon'];
        $industry->order = $validated['order'];
        $industry->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('industries', 'public');
            $industry->image_path = '/storage/' . $path;
        }

        $industry->save();

        return redirect()->route('admin.industries.index')->with('success', 'Industry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used separately right now
        return redirect()->route('admin.industries.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Industry $industry)
    {
        return view('admin.industries.edit', compact('industry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'products' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'order' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'is_active' => 'boolean'
        ]);

        $industry->name = $validated['name'];
        $industry->description = $validated['description'];
        $industry->products = $validated['products'];
        $industry->icon = $validated['icon'];
        $industry->order = $validated['order'];
        $industry->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('industries', 'public');
            $industry->image_path = '/storage/' . $path;
        }

        $industry->save();

        return redirect()->route('admin.industries.index')->with('success', 'Industry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Industry $industry)
    {
        $industry->delete();
        return redirect()->route('admin.industries.index')->with('success', 'Industry deleted successfully.');
    }
}
