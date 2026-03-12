<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Product; // Make sure this matches your actual Product model!

#[Layout('layouts.client-app')]
#[Title('Member Dashboard - GymWithin')]
class UserDashboard extends Component
{
    public function render()
    {
        // Safely fetch 4 random products (with a fallback if the model isn't ready)
        $products = class_exists(Product::class)
            ? Product::inRandomOrder()->take(6)->get()
            : collect([]);

        $categories = [
            ['name' => 'Strength', 'icon' => 'fas fa-dumbbell'],
            ['name' => 'Cardio', 'icon' => 'fas fa-running'],
            ['name' => 'Accessories', 'icon' => 'fas fa-check-square'],
            ['name' => 'Recovery', 'icon' => 'fas fa-heartbeat'],
        ];

        return view('livewire.user.user-dashboard', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
