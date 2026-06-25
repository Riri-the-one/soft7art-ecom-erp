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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        
        // Clés étrangères : constrained() devine automatiquement la table liée
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'agent qui a saisi la commande
        
        // Le statut avec "pending" (en attente) par défaut
        $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'returned', 'canceled'])->default('pending');
        
        $table->decimal('delivery_fee', 8, 2)->default(0);
        $table->decimal('total_amount', 10, 2)->default(0);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
