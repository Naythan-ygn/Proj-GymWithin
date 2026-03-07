<?php
// app/Livewire/Admin/CategoryManager.php
namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Flux\Flux;

class CategoryManager extends Component
{
    public $name = '';

    public $editingId = null;

    public $editingCategory = null; // Stores the ID of the category being edited

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name',
    ];

    public function save()
    {
        $this->validate();

        if ($this->editingCategory) {
            $category = Category::find($this->editingCategory);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);
            Flux::toast('Category updated successfully.');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);
            Flux::toast('New category added.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingCategory = $category->id;
        $this->name = $category->name;
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        Flux::toast('Category removed.', variant: 'danger');
    }

    public function resetForm()
    {
        $this->reset(['name', 'editingCategory']);
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::latest()->get(),
        ]);
    }
}
