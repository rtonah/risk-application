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
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->unsignedInteger('processed_by')->nullable()->after('status');;
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('cascade');

            // $table->unsignedBigInteger('processed_by')->nullable()->after('status');
            // $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_payments', function (Blueprint $table) {
            //
        });
    }
};
