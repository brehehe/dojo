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
        Schema::create('rundowns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('pertandingan');
            $table->longText('description')->nullable();
            $table->timestamp('date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drawing_match_numbers', function (Blueprint $table) {
            $table->dropForeign(['rundown_id']);
        });

        Schema::dropIfExists('rundowns');
    }
};
