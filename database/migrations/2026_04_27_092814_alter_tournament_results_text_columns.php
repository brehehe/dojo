<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournament_results', function (Blueprint $table) {
            $table->text('athlete_names')->nullable()->change();
            $table->text('contingent_name')->nullable()->change();
            $table->text('generated_by')->nullable()->change();
            $table->text('bracket_section')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tournament_results', function (Blueprint $table) {
            $table->string('athlete_names')->nullable()->change();
            $table->string('contingent_name')->nullable()->change();
            $table->string('generated_by')->nullable()->change();
            $table->string('bracket_section')->nullable()->change();
        });
    }
};
