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
            $table->unsignedBigInteger('drawing_id')->nullable()->after('registration_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embu_scores', function (Blueprint $table) {
            $table->dropColumn('drawing_id');
        });
    }
};
