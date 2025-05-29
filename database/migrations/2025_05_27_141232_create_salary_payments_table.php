<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id'); // Matricule de l'employé
            $table->string('account_number'); // Compte courant
            $table->decimal('amount', 15, 2); // Montant du virement
            $table->string('label')->nullable(); // Libellé du paiement
            $table->string('operation_code')->nullable(); // Code retourné par Musoni
            $table->foreignId('payment_type_id')->constrained('payment_types')->onDelete('cascade');
            $table->date('payment_date')->nullable(); // Date de versement
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
