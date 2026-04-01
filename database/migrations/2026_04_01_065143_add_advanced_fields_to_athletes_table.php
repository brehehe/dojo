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
        Schema::table('athletes', function (Blueprint $table) {
            $table->string('kyu')->nullable();
            $table->string('dojo_origin')->nullable();
            $table->string('city')->nullable();
            $table->string('bpjs_number')->nullable();
            $table->boolean('bpjs_status')->default(false);
            $table->string('bpjs_card_path')->nullable();
            $table->integer('age')->nullable();
            $table->string('age_group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['kyu', 'dojo_origin', 'city', 'bpjs_number', 'bpjs_status', 'bpjs_card_path', 'age', 'age_group']);
        });
    }
};
