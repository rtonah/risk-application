<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_resolution_details_to_incident_ro_table.php

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
        Schema::table('incident_ro', function (Blueprint $table) {
            $table->text('resolution_details')->nullable()->after('business_impact');
            $table->timestamp('resolved_at')->nullable()->after('resolution_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_ro', function (Blueprint $table) {
            $table->dropColumn('resolution_details');
            $table->dropColumn('resolved_at');
        });
    }
};