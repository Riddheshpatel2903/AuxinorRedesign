<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with(['products' => fn($q) => $q->active()->ordered()])->active()->ordered()->get();
        $allProducts = Product::with('category')->active()->ordered()->paginate(20);
        return view('products.index', compact('categories', 'allProducts'));
    }

    public function byCategory(Request $request, $slug)
    {
        $category = ProductCategory::where('slug', $slug)->active()->firstOrFail();
        
        $query = Product::where('category_id', $category->id)->active()->ordered();
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->paginate(16);
        $categories = ProductCategory::active()->ordered()->get();
        
        return view('products.category', compact('category', 'products', 'categories'));
    }

    public function show($catSlug, $slug)
    {
        $product = Product::with('category')
                    ->whereHas('category', fn($q) => $q->where('slug', $catSlug))
                    ->where('slug', $slug)->active()->firstOrFail();
                    
        $related = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)->active()->limit(4)->get();
                    
        return view('products.show', compact('product', 'related'));
    }
}
