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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('athlete_status')->default('pending')->after('status');
        });

        // Set existing verified registrations to verified
        DB::table('registrations')->where('status', 'verified')->update(['athlete_status' => 'verified']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('athlete_status');
        });
    }
};
