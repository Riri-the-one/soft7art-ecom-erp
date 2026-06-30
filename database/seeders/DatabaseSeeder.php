<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des 3 comptes employés avec les bons rôles et mots de passe sécurisés
        User::factory()->create([
            'name' => 'Directeur Super Admin',
            'email' => 'admin@erp.com',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        $agent = User::factory()->create([
            'name' => 'Agent Commercial',
            'email' => 'agent@erp.com',
            'role' => 'agent',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Gestionnaire Stock',
            'email' => 'stock@erp.com',
            'role' => 'stock_manager',
            'password' => Hash::make('password'),
        ]);

        // 2. Génération de 20 faux clients et 50 faux produits
        $customers = Customer::factory(20)->create();
        $products = Product::factory(50)->create();

        // Liste des statuts pour alimenter dynamiquement les indicateurs des tableaux de bord
        $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'canceled'];

        // 3. Génération de 30 commandes aléatoires avec des produits à l'intérieur
        foreach (range(1, 30) as $i) {
            $order = Order::factory()->create([
                'customer_id' => $customers->random()->id,
                'user_id' => $agent->id,
                'total_amount' => 0,
                'status' => $statuses[array_rand($statuses)], // Assure la diversité pour le calcul des taux de succès
            ]);

            // Sécurisation : shuffle() et take() garantissent de toujours retourner une collection, même pour 1 seul produit
            $orderProducts = $products->shuffle()->take(rand(1, 4));
            $totalAmount = 0;

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                
                // On attache le produit à la commande (Table Pivot OrderProduct)
                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price
                ]);
                
                $totalAmount += $quantity * $product->selling_price;
            }

            // On met à jour le prix total de la commande
            $order->update([
                'total_amount' => $totalAmount + 30.00 // Total produits + frais de livraison fixes
            ]);
        }
    }
}