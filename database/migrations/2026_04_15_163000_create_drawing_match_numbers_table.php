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
        Schema::create('drawing_match_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pool_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
            $table->string('round')->default('Penyisihan'); // Penyisihan, Final
            $table->integer('sequence_number')->default(0);
            $table->enum('draft_type', ['embu', 'randori'])->nullable();
            $table->json('metadata')->nullable(); // For composition etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawing_match_numbers');
    }
};
