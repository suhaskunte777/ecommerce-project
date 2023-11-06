<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        User::factory(50)->create();

        Category::factory(5)->create();

        $categories = Category::all();
        $users = User::all();

        Product::factory(50)->make()->each(function ($product) use ($categories, $users) {
            $product->category_id = $categories->random()->id;
            $product->save();

            Review::factory(rand(5, 25))->make()->each(function ($review) use ($users, $product) {
                $review->user_id = $users->random()->id;
                $review->product_id = $product->id;
                $review->save();
            });
        });
    }
}
