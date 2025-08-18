<?php

use App\Models\Facture;
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
        Schema::create('ligne_factures', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->integer('quantite')->unsigned();
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('taux_tva', 5, 2);
            $table->decimal('ligne_total_ht', 12, 2)->default(0);
            $table->decimal('ligne_total_tva', 12, 2)->default(0);
            $table->decimal('ligne_total_ttc', 12, 2)->default(0);
            $table->foreignIdFor(Facture::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_factures');
    }
};
