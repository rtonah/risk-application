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
        Schema::create('cbs_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: musoni_env1
            $table->text('login');  // login crypté
            $table->text('password'); // mot de passe crypté
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbs_credentials');
    }
};
