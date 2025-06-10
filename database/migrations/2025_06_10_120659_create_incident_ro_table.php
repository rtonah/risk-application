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
        Schema::create('incident_ro', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // déclarant
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();

            // Ajouts demandés :
            $table->text('business_impact')->nullable(); // Impact métier
            $table->string('incident_type')->nullable(); // Type d’incident (ex. technique, humain...)
            $table->enum('origin', ['interne', 'externe'])->nullable(); // Origine
            $table->string('attachment_path')->nullable(); // Pièce jointe

            $table->enum('priority', ['faible', 'moyenne', 'élevée'])->default('moyenne');
            $table->enum('status', ['ouvert', 'en cours', 'résolu', 'clôturé'])->default('ouvert');
            $table->foreignId('branches_id')->nullable()->constrained(); // assigné à
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_ro');
    }
};
