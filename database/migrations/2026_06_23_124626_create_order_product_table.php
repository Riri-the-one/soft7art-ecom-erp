<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('order_product', function (Blueprint $table) {
        $table->id();
        
        // Liens vers la commande et le produit
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        
        // Les données spécifiques à cet achat
        $table->integer('quantity');
        $table->decimal('unit_price', 8, 2); // Le prix figé au moment de l'achat
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
