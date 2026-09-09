<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Attribue un emplacement de rayon (ex: "Rayon A-02") à tous les produits
     * qui n'en ont pas encore, pour le système code-barres/rayonnage. Sûr à
     * relancer plusieurs fois : ne touche jamais aux produits qui ont déjà
     * un emplacement renseigné manuellement.
     */
    public function run(): void
    {
        $zones = ['A', 'B', 'C', 'D', 'E'];

        Product::whereNull('shelf_location')
            ->orderBy('id')
            ->get()
            ->each(function (Product $product, int $index) use ($zones) {
                $zone = $zones[$index % count($zones)];
                $shelfNumber = str_pad((string) (intdiv($index, count($zones)) + 1), 2, '0', STR_PAD_LEFT);

                $product->update(['shelf_location' => "Rayon {$zone}-{$shelfNumber}"]);
            });
    }
}
