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
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->unsignedInteger('status_updated_by')->nullable()->after('status');

            // Ajout manuel de la contrainte de clé étrangère
            $table->foreign('status_updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // ou ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            //
        });
    }
};
