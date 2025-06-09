<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('it_requests', function (Blueprint $table) {
            $table->id();

            // Référence au demandeur
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('category'); // musoni, odoo, etc.
            $table->string('status')->default('ouvert'); // ouvert, en_cours, traite
            $table->string('priority')->default('normal'); // normal, urgent, etc.

            // Référence à l’agent IT assigné
            $table->unsignedInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('due_at')->nullable();     // SLA
            $table->timestamp('closed_at')->nullable();  // Fermeture

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_requests');
    }
};
