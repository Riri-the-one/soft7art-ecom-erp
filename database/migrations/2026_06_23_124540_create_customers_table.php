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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // Ajout de la colonne email avec la contrainte d'unicité
            $table->string('email')->unique();
            
            $table->string('phone');
            $table->string('city');
            $table->text('address');
            
            // Par défaut, un nouveau client n'est pas blacklisté (false)
            $table->boolean('is_blacklisted')->default(false); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};