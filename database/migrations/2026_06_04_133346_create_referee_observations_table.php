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
        Schema::create('referee_observations', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('contingent_id')->constrained('contingents')->cascadeOnDelete();
            $blueprint->foreignId('referee_id')->nullable()->constrained('referees')->cascadeOnDelete();
            $blueprint->string('observer_name');
            $blueprint->date('observation_date');
            $blueprint->string('court');
            $blueprint->string('round');
            $blueprint->string('match_time');
            $blueprint->string('referee_number')->nullable();
            $blueprint->string('contingent_away')->nullable();
            $blueprint->string('contingent_home')->nullable();
            $blueprint->decimal('total_score', 8, 2)->default(0.00);
            $blueprint->string('category')->nullable();
            $blueprint->string('kepada')->nullable();
            $blueprint->string('dari')->nullable();
            $blueprint->date('tanggal_laporan')->nullable();
            $blueprint->text('kelebihan')->nullable();
            $blueprint->text('area_perbaikan')->nullable();
            $blueprint->text('rekomendasi')->nullable();
            $blueprint->json('data')->nullable(); // Stores detailed scores, lists, checkmarks, sampling
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referee_observations');
    }
};
