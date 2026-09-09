<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Emplacement de rangement en entrepôt (ex: "Rayon A-02"), utilisé par le
     * système code-barres/rayonnage pour retrouver rapidement un produit.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shelf_location', 50)->nullable()->after('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shelf_location');
        });
    }
};
