<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Même bug que pour 'city' (cf. migration 2026_09_08_000001) : le formulaire
     * de création client (resources/views/customers/create.blade.php) marque
     * 'phone' et 'address' comme optionnels ("Téléphone (Optionnel)",
     * "Adresse (Optionnelle)") et StoreCustomerRequest les valide en 'nullable',
     * mais les colonnes restaient NOT NULL sans valeur par défaut en base →
     * violation de contrainte SQL dès qu'un de ces deux champs est laissé vide.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
        });
    }
};
