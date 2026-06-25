<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchasePrice = fake()->randomFloat(2, 10, 500); // Prix d'achat entre 10 et 500
        
        return [
            'name' => fake()->words(3, true), // Un faux nom de produit de 3 mots
            'description' => fake()->sentence(),
            'photo_path' => null,
            'purchase_price' => $purchasePrice,
            // Le prix de vente est le prix d'achat multiplié par une marge (ex: x1.5)
            'selling_price' => $purchasePrice * fake()->randomFloat(2, 1.2, 2.0), 
            'stock_quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
