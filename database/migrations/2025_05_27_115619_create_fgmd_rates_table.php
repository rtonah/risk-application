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
        Schema::create('fgmd_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('min_days'); // ex : 1
            $table->integer('max_days'); // ex : 179
            $table->decimal('rate', 5, 2); // ex : 0.60
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fgmd_rates');
    }
};
