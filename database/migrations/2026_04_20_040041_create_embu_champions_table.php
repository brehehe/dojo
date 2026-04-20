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
        Schema::create('embu_champions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained('match_numbers')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->unsignedSmallInteger('rank');
            $table->decimal('penyisihan_score', 12, 2)->default(0);
            $table->decimal('final_score', 12, 2)->default(0);
            $table->decimal('accumulated_score', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['match_number_id', 'registration_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embu_champions');
    }
};
