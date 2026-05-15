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
        Schema::create('match_number_merges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('age_group_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['randori', 'embu']);
            $table->timestamps();
        });

        Schema::create('match_number_merge_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_number_merge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('match_number_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_number_merge_details');
        Schema::dropIfExists('match_number_merges');
    }
};
