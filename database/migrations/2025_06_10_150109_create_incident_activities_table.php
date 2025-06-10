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
        Schema::create('incident_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_ro_id')->constrained('incident_ro')->onDelete('cascade');

            // --- CORRECTION ICI ---
            // Le type de 'id' dans la table 'users' est 'int unsigned'.
            // Nous devons donc utiliser 'unsignedInteger' pour 'user_id' afin qu'il corresponde.
            $table->unsignedInteger('user_id')->nullable(); // Déclare la colonne
            $table->foreign('user_id') // Définit la clé étrangère
                  ->references('id')   // Référence la colonne 'id'
                  ->on('users')       // Dans la table 'users'
                  ->onDelete('set null'); // Comportement en cas de suppression

            $table->string('type'); // Ex: 'created', 'status_changed', 'resolved', 'updated'
            $table->text('description'); // Détails de l'action (Ex: 'Statut changé de Ouvert à En cours')
            $table->json('old_value')->nullable(); // Ancien état (ex: old_status: 'ouvert')
            $table->json('new_value')->nullable(); // Nouvel état (ex: new_status: 'en cours')
            $table->timestamps(); // created_at sera la date de l'activité
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_activities');
    }
};