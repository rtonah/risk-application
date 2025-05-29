<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('supervisor_id')->nullable();
            $table->unsignedInteger('purchase_manager_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('purchase_manager_id')->references('id')->on('users')->nullOnDelete();

            $table->enum('status', ['draft', 'validated', 'processed', 'sent_to_odoo'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_requests');
    }
}
