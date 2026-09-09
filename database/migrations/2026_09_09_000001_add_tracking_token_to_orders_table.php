<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute un jeton unique et imprévisible par commande, utilisé pour
     * générer un lien de suivi public (sans authentification) à envoyer
     * au client. Le token fait office d'autorisation : sans lui, impossible
     * de deviner l'URL de suivi d'une autre commande.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_token', 40)->nullable()->unique()->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tracking_token');
        });
    }
};
