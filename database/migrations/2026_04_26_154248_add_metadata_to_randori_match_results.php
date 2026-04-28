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
        Schema::table('randori_match_results', function (Blueprint $table) {
            $table->json('metadata')->nullable()->after('score_blue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('randori_match_results', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
