<?php

namespace App\Http\Controllers;

use App\Models\Product; // Add this
use App\Models\Category; // Add this
use Illuminate\Http\Request; // Use the standard Request
use Illuminate\Routing\Controller;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the category slug and search query from the URL
        $categorySlug = $request->query('category');
        $search = trim($request->query('search', ''));

        // 2. Fetch products, optionally filtered by category and search text
        $query = Product::query()->with('category');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        // 3. Fetch all categories for the sidebar
        $categories = Category::all();

        return view('equipment', compact('products', 'categories', 'search'));
    }

    public function show(Product $product)
    {
        // Eager load category to avoid N+1 queries
        $product->load('category');

        // Fetch related products (same category, excluding current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('equipment-show', compact('product', 'relatedProducts'));
    }
}
