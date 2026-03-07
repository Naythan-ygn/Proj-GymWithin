<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'David',
            'email' => 'david@gmail.com',
            'password' => 'david123',
            'role' => 'user',
        ]);

        $categories = collect(['Supplements', 'Apparel', 'Equipment'])->map(function ($name) {
            return Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        });

        Product::factory(20)->create([
            'category_id' => fn() => $categories->random()->id,
        ]);
    }
}
