<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_results', function (Blueprint $table) {
            $table->id();

            // Match context
            $table->foreignId('match_number_id')->constrained('match_numbers')->cascadeOnDelete();
            $table->string('draft_type')->comment('Embu or Randori');
            $table->unsignedSmallInteger('rank')->comment('1=Juara 1, 2=Juara 2, 3=Juara 3, 4=Juara 3 Bersama');

            // Participant
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->string('athlete_names')->nullable()->comment('Cached athlete names');
            $table->string('contingent_name')->nullable()->comment('Cached contingent name');

            // Scores (Embu)
            $table->decimal('penyisihan_score', 12, 2)->default(0);
            $table->decimal('final_score', 12, 2)->default(0);
            $table->decimal('accumulated_score', 12, 2)->default(0);

            // Randori specific
            $table->string('bracket_section')->nullable()->comment('Upper/Lower bracket position');

            // Meta
            $table->string('generated_by')->nullable()->comment('User who generated');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            // Unique constraint: one rank per match
            $table->unique(['match_number_id', 'rank'], 'tournament_results_match_rank_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_results');
    }
};
