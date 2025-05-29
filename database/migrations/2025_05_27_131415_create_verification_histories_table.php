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
        Schema::create('verification_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('loan_number');
            $table->integer('loan_duration_days');
            
            // Résultats de vérification
            $table->boolean('grace_capital_conform');
            $table->boolean('grace_interest_conform');
            $table->boolean('grace_interest_charged_conform');
            $table->boolean('standing_instruction_activated');
            $table->boolean('fgmd_conform');

            // Valeurs attendues et réelles pour FGMD
            $table->decimal('fgmd_expected_rate', 5, 2)->nullable();
            $table->decimal('fgmd_actual_rate', 5, 2)->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_histories');
    }
};
