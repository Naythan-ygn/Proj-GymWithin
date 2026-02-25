<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['supplements', 'apparel', 'gear'];
        $category = $this->faker->randomElement($categories);

        // Product names based on category for more realism
        $names = [
            'supplements' => ['Whey Protein Isolate', 'Pre-Workout Blast', 'Creatine Monohydrate', 'BCAA Recovery'],
            'apparel' => ['GymWithin Oversized Tee', 'Performance Leggings', 'Stringer Tank', 'Athlete Hoodie'],
            'gear' => ['Leather Lifting Belt', 'Wrist Wraps (Pair)', 'Knee Sleeves', 'Gym Bag 40L'],
        ];

        $name = $this->faker->randomElement($names[$category]);

        return [
            'name' => $name,
            'sku' => 'GW-' . strtoupper(Str::random(6)), // Matches your form logic
            'description' => $this->faker->paragraph(2),
            'price' => $this->faker->randomFloat(2, 10, 150),
            'category' => $category,
            'stock' => $this->faker->numberBetween(0, 50),
            'image_path' => null, // Leave null so it uses your UI placeholder logic
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
