<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Flux\Flux;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    // Form Fields
    public $name = '';
    public $sku = '';
    public $price = '';
    public $stock = 0;
    public $description = '';
    public $image; // Temporary file for upload
    public $existingImage; // Current image path
    public $categories; // For the dropdown
    public $category_id = null; // To store the selected ID

    public function mount(?Product $product = null)
    {
        $this->categories = \App\Models\Category::all(); // Fetch from DB

        if ($product && $product->exists) {
            $this->category_id = $product->category_id;
            $this->product = $product;
            $this->name = $product->name;
            $this->sku = $product->sku;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->description = $product->description;
            $this->existingImage = $product->image_path;
        } else {
            // Auto-generate a SKU prefix for new products
            $this->sku = 'GW-' . strtoupper(Str::random(6));
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . ($this->product->id ?? 'NULL'),
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];

        $validated = $this->validate($rules);

        // 1. Remove 'image' from the array so Laravel doesn't try to insert the file object
        unset($validated['image']);

        // 2. Handle the file upload and save the path to the CORRECT column name
        if ($this->image) {
            $validated['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->product) {
            $this->product->update($validated);
            $message = 'Product updated successfully.';
        } else {
            // 3. Now $validated only contains keys that exist in your DB (name, sku, category, etc.)
            Product::create($validated);
            $message = 'Product added to inventory.';
        }

        Flux::toast($message, variant: 'success');

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.product-form');
    }
}
