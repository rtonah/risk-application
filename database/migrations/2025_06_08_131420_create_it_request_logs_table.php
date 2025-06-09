<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('it_request_logs', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers it_requests
            $table->unsignedBigInteger('it_request_id');
            $table->foreign('it_request_id')->references('id')->on('it_requests')->onDelete('cascade');

            // Clé étrangère vers users (nullable)
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->string('action'); // Exemple : "Création", "Changement de statut", etc.
            $table->text('details')->nullable(); // Informations supplémentaires

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_request_logs');
    }
};
