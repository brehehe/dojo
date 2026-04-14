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
        Schema::create('embu_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');

            // 5 Judges scores
            $table->decimal('judge_1', 8, 2)->default(0);
            $table->decimal('judge_2', 8, 2)->default(0);
            $table->decimal('judge_3', 8, 2)->default(0);
            $table->decimal('judge_4', 8, 2)->default(0);
            $table->decimal('judge_5', 8, 2)->default(0);

            $table->decimal('total_score', 12, 2)->default(0);
            $table->integer('rank')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embu_scores');
    }
};
