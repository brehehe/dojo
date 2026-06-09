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
        Schema::table('embu_scores', function (Blueprint $table) {
            $table->string('waktu')->nullable()->after('nilai_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embu_scores', function (Blueprint $table) {
            $table->dropColumn('waktu');
        });
    }
};
