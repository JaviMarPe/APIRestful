<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $quantityUsers = 1000;
        $quantityCategories = 30;
        $quantityProducts = 1000;
        $quantityTransactions = 1000;

        User::factory()->count($quantityUsers)->create();
        Category::factory()->count($quantityCategories)->create();

        Product::factory()->count($quantityProducts)->create()->each(
            function ($product){
                $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categorias);
            }
        );

        Transaction::factory()->count($quantityTransactions)->create();

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
