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
        Schema::create('referee_score_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_id')->constrained()->onDelete('cascade');
            $table->foreignId('referee_id')->constrained()->onDelete('cascade');
            $table->integer('judge_index'); // 1-5
            
            // Polymorphic to handle Embu (Registration) and Randori (Match Result)
            $table->morphs('scorable'); 
            
            $table->json('details'); // Stores GOHO, JUHO, Ekspresi etc.
            $table->decimal('total_calculated_score', 8, 2)->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referee_score_details');
    }
};
