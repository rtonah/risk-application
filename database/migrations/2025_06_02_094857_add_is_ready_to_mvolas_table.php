<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mvolas', function (Blueprint $table) {
            $table->boolean('is_ready')->default(false)->after('status');
            $table->unsignedTinyInteger('processing_attempts')->default(0);
            $table->text('last_error_message')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mvolas', function (Blueprint $table) {
            $table->dropColumn('is_ready');
        });
    }
};

