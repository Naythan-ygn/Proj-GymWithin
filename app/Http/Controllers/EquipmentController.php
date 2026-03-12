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
        // 1. Get the category slug from the URL (e.g., ?category=apparel)
        $categorySlug = $request->query('category');

        // 2. Fetch products, optionally filtered by category
        $query = Product::query()->with('category');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->latest()->get();

        // 3. Fetch all categories for the sidebar
        $categories = Category::all();

        // Pass 'products' instead of 'equipment' to match the updated view
        return view('equipment', compact('products', 'categories'));
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
