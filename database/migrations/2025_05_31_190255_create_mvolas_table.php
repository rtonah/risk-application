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
        Schema::create('mvolas', function (Blueprint $table) {
            $table->id();
            $table->string('Transaction_Date')->nullable();
            $table->string('Transaction_Id')->unique();
            $table->string('Tsansaction_Initiateur')->nullable();
            $table->string('Type')->nullable();
            $table->string('Canal')->nullable();
            $table->string('Compte')->nullable();
            $table->decimal('Montant', 12, 2)->nullable();
            $table->string('RRP')->nullable();
            $table->string('De')->nullable();
            $table->string('Vers')->nullable();
            $table->string('Balance_avant')->nullable();
            $table->string('Balance_apres')->nullable();
            $table->string('Details_1')->nullable();
            $table->string('Account')->nullable();
            $table->string('Validateur')->nullable();
            $table->string('Num_notif')->nullable();
            $table->string('code_operation')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mvolas');
    }
};
