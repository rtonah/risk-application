<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id(); // => BIGINT UNSIGNED (ok car c’est une autre table)

            // ➤ Corrigé : int unsigned pour être compatible avec users.id
            $table->unsignedInteger('user_id')->nullable();

            $table->string('search_term');
            $table->unsignedInteger('matched_results')->default(0);
            $table->timestamp('searched_at')->useCurrent();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
