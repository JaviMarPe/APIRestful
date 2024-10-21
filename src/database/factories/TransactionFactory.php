<?php

namespace Database\Factories;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sellers = Seller::has('products')->get()->random();
        $buyers = User::all()->except($sellers->id)->random();
        return [
            'quantity' => fake()->numberBetween(1, 10),
            'buyer_id' => $buyers->id,
            'product_id' => $sellers->products->random(),
        ];
    }
}
