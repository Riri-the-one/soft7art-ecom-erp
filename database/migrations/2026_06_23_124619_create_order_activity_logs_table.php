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
    Schema::create('order_activity_logs', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // La personne qui a fait la modif
        
        $table->string('old_status')->nullable(); // Nullable car à la création, il n'y a pas d'ancien statut
        $table->string('new_status');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_activity_logs');
    }
};
