<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de tes 3 comptes employés avec les bons rôles
        User::factory()->create([
            'name' => 'Directeur Super Admin',
            'email' => 'admin@erp.com',
            'role' => 'super_admin',
            'password' => bcrypt('password'), // Le mot de passe sera 'password'
        ]);

        $agent = User::factory()->create([
            'name' => 'Agent Commercial',
            'email' => 'agent@erp.com',
            'role' => 'agent',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Gestionnaire Stock',
            'email' => 'stock@erp.com',
            'role' => 'stock_manager',
            'password' => bcrypt('password'),
        ]);

        // 2. Génération de 20 faux clients et 50 faux produits
        $customers = Customer::factory(20)->create();
        $products = Product::factory(50)->create();

        // 3. Génération de 30 commandes aléatoires avec des produits à l'intérieur
        foreach (range(1, 30) as $i) {
            $order = Order::factory()->create([
                'customer_id' => $customers->random()->id,
                'user_id' => $agent->id,
                'total_amount' => 0,
            ]);

            // On prend entre 1 et 4 produits au hasard dans le catalogue
            $orderProducts = $products->random(rand(1, 4));
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
                'total_amount' => $totalAmount + 30.00 // Total produits + livraison
            ]);
        }
    }
}