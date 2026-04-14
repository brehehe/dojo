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
        Schema::create('randori_match_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->onDelete('cascade');
            $table->integer('bracket_node_index');

            $table->foreignId('winner_athlete_id')->nullable()->constrained('athletes')->onDelete('cascade');
            $table->string('winner_color')->nullable(); // merah, biru

            $table->decimal('score_red', 8, 2)->default(0);
            $table->decimal('score_blue', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('randori_match_results');
    }
};
