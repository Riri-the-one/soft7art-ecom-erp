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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('photo_path')->nullable();
        
        // Prix d'achat et de vente (8 chiffres au total, 2 après la virgule)
        $table->decimal('purchase_price', 8, 2); 
        $table->decimal('selling_price', 8, 2);
        
        // Stock actuel (ne peut pas être négatif)
        $table->unsignedInteger('stock_quantity')->default(0); 
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
