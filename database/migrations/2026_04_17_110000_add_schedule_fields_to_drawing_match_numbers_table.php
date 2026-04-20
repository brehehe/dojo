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
        Schema::table('drawing_match_numbers', function (Blueprint $table) {
            $table->date('schedule_date')->nullable()->after('court_id');
            $table->foreignId('rundown_id')->nullable()->after('schedule_date')->constrained('rundowns')->nullOnDelete();
            $table->foreignId('session_time_id')->nullable()->after('rundown_id')->constrained('session_times')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drawing_match_numbers', function (Blueprint $table) {
            $table->dropForeign(['rundown_id']);
            $table->dropForeign(['session_time_id']);
            $table->dropColumn(['schedule_date', 'rundown_id', 'session_time_id']);
        });
    }
};
