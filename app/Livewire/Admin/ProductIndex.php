<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
// use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $selectedProduct; // For the Preview Drawer
    public $productToDelete; // For the Delete Modal

    // Reset pagination when searching
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function showPreview($id)
    {
        $this->selectedProduct = Product::find($id);
        $this->modal('product-preview-drawer')->show();
    }

    public function confirmDelete($id)
    {
        $this->productToDelete = $id;
        $this->modal('delete-product-modal')->show();
    }

    public function deleteProduct(): void
    {
        $product = Product::findOrFail($this->productToDelete);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        $this->modal('delete-product-modal')->hide();

        // Fix: Remove 'view:' and use the string directly
        Flux::toast('Product removed from inventory.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.product-index', [
            'products' => Product::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                })
                ->when($this->categoryFilter, function ($query) {
                    $query->where('category', $this->categoryFilter);
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
