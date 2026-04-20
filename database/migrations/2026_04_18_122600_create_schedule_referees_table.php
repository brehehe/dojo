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
        Schema::dropIfExists('match_number_referee');

        Schema::create('schedule_referees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rundown_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_time_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete(); // Nullable for Dewan Arbitrase (session-wide)
            $table->foreignId('referee_id')->constrained()->cascadeOnDelete();
            $table->integer('judge_index'); // 0: Dewan Arbitrase, 1: Ketua, 2-5: Daerah/Pembantu
            $table->timestamps();

            // Allow multiple referees per block, but ensure a single referee doesn't hold multiple indexes in the same block/court
            $table->unique(['rundown_id', 'session_time_id', 'court_id', 'judge_index'], 'unique_judge_per_court');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_referees');

        Schema::create('match_number_referee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referee_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }
};
