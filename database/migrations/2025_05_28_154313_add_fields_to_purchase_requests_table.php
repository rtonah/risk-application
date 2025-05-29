<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->date('expected_delivery_date')->nullable()->after('title'); // <- corrigé ici
            $table->string('department')->nullable()->after('expected_delivery_date');
            $table->string('priority')->default('Normale')->after('department');
            $table->text('notes')->nullable()->after('priority');
            $table->unsignedInteger('supervisor_id')->nullable()->after('user_id');
            $table->unsignedInteger('purchase_manager_id')->nullable()->after('supervisor_id');
            $table->decimal('total_estimated_cost', 15, 2)->default(0)->after('priority');

            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('purchase_manager_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['purchase_manager_id']);
            $table->dropColumn([
                'title',
                'expected_delivery_date',
                'department',
                'priority',
                'notes',
                'supervisor_id',
                'purchase_manager_id',
                'total_estimated_cost',
            ]);
        });
    }
};

