<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('national_id')->unique();
            $table->text('reason');
            $table->enum('status', ['blacklisted', 'unblocked'])->default('blacklisted');
            $table->string('document_path')->nullable();

            $table->unsignedBigInteger('unblocked_by')->nullable();
            $table->foreign('unblocked_by')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');


            $table->timestamp('unblocked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};
