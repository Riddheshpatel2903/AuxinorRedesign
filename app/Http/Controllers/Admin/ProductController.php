<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cas_number', 'like', "%{$search}%")
                  ->orWhere('chemical_formula', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = ProductCategory::all();

        if ($request->ajax()) {
            return view('admin.products._table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:products,slug',
            'chemical_formula' => 'nullable|max:255',
            'cas_number' => 'nullable|max:255',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'applications' => 'nullable',
            'specifications' => 'nullable|array',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        if (!isset($validated['is_active'])) $validated['is_active'] = false;
        if (!isset($validated['is_featured'])) $validated['is_featured'] = false;

        // Process Primary Image
        if ($request->hasFile('image')) {
            $validated['image'] = $this->imageService->upload($request->file('image'), 'products');
        }

        // Process Gallery Images
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $this->imageService->upload($file, 'products/gallery');
            }
            $validated['gallery'] = $gallery;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug,' . $product->id,
            'chemical_formula' => 'nullable|max:255',
            'cas_number' => 'nullable|max:255',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'applications' => 'nullable',
            'specifications' => 'nullable|array',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (!isset($validated['is_active'])) $validated['is_active'] = false;
        if (!isset($validated['is_featured'])) $validated['is_featured'] = false;

        // Update Primary Image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                $this->imageService->delete($product->image);
            }
            $validated['image'] = $this->imageService->upload($request->file('image'), 'products');
        } else {
            unset($validated['image']);
        }

        // Update Gallery Images (append new ones)
        if ($request->hasFile('gallery')) {
            $existingGallery = $product->gallery ?? [];
            $newGallery = [];
            foreach ($request->file('gallery') as $file) {
                $newGallery[] = $this->imageService->upload($file, 'products/gallery');
            }
            $validated['gallery'] = array_merge($existingGallery, $newGallery);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Cleanup main image
        if ($product->image) {
            $this->imageService->delete($product->image);
        }

        // Cleanup gallery images
        if (is_array($product->gallery)) {
            foreach ($product->gallery as $galImg) {
                $this->imageService->delete($galImg);
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
