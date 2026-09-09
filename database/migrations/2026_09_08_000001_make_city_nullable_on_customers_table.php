<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le formulaire de création client (resources/views/customers/create.blade.php)
     * ne collecte pas la ville : la colonne 'city', NOT NULL sans valeur par défaut,
     * provoquait une erreur SQL à chaque création de client via StoreCustomerRequest.
     * On la rend nullable pour refléter la réalité du formulaire actuel.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('city')->nullable(false)->change();
        });
    }
};
