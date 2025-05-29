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
         Schema::create('grace_period_values', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->integer('loan_duration'); // Durée du prêt
            $table->integer('grace_period_capital'); // Délai de grâce sur le capital
            $table->integer('grace_period_interest_payment'); // Délai de grâce sur le paiement des intérêts
            $table->decimal('grace_on_interest_charged', 8, 2); // Grâce On Interest Charged (Intérêt)
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grace_period_values');
    }
};
