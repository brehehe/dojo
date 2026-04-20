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
        Schema::create('randori_judge_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->onDelete('cascade');
            $table->string('bracket_node')->comment('e.g., ub_0_0');
            $table->tinyInteger('judge_index')->comment('1 to 5');
            
            // Aka (Red) Scores
            $table->integer('waza_ari_aka')->default(0);
            $table->integer('ippon_aka')->default(0);
            $table->integer('hansoku_aka')->default(0);
            
            // Shiro (White) Scores
            $table->integer('waza_ari_shiro')->default(0);
            $table->integer('ippon_shiro')->default(0);
            $table->integer('hansoku_shiro')->default(0);

            $table->timestamps();

            $table->unique(['match_number_id', 'bracket_node', 'judge_index'], 'unique_judge_score_node');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('randori_judge_scores');
    }
};
