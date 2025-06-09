<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('it_request_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('it_request_id');
$table->foreign('it_request_id')->references('id')->on('it_requests')->onDelete('cascade');


            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_request_messages');
    }
};

