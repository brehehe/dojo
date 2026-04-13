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
        if (!Schema::hasColumn('registration_athlete', 'weight_group_id')) {
            Schema::table('registration_athlete', function (Blueprint $table) {
                $table->foreignId('weight_group_id')->nullable()->after('athlete_id')->constrained('weight_groups')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_athlete', function (Blueprint $table) {
            $table->dropForeign(['weight_group_id']);
            $table->dropColumn('weight_group_id');
        });
    }
};
