<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('branches', function (Blueprint $table) {

            $table->id();
            $table->string('name')->comment('Nom de la branche, DRA, Agence ou Sous-agence');
            $table->enum('type', ['DRA', 'AGENCY', 'SUB_AGENCY'])->comment('Type de branche : DRA, Agence ou Sous-agence');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Référence vers la branche parente');
            $table->string('region')->nullable()->comment('Nom de la région pour les DRA uniquement');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Ajouter le commentaire de table manuellement après la création
        DB::statement("ALTER TABLE branches COMMENT = 'Liste des directions régionales, agences et sous-agences avec hiérarchie'");



        // Ajouter le commentaire de table manuellement après la création
        DB::statement("ALTER TABLE branches COMMENT = 'Liste des directions régionales, agences et sous-agences avec hiérarchie'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
