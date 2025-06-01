<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->enum('blacklist_type', ['client', 'fournisseur', 'prestataire'])
                ->default('client')
                ->after('id');

            $table->string('company_name')->nullable()->after('full_name');
            $table->text('notes')->nullable()->after('reason');
        });
    }

    public function down()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->dropColumn('blacklist_type');
            $table->dropColumn('company_name');
            $table->dropColumn('notes');
        });
    }
};

