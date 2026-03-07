<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'name' => $this->faker->words(3, true),
            'sku' => 'GW-' . strtoupper($this->faker->unique()->bothify('??#?##')),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'stock' => $this->faker->numberBetween(0, 100),
            'image_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
