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
        Schema::table('public_events', function (Blueprint $table) {
            if (!Schema::hasColumn('public_events', 'description')) {
                $table->text('description')->nullable()->after('location_name_en');
            }
            if (!Schema::hasColumn('public_events', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_events', function (Blueprint $table) {
            if (Schema::hasColumn('public_events', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('public_events', 'description_en')) {
                $table->dropColumn('description_en');
            }
        });
    }
};
