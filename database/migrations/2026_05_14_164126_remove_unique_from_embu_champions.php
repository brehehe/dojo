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
        Schema::table('embu_champions', function (Blueprint $table) {
            $table->dropUnique(['match_number_id', 'registration_id']);
        });
    }

    public function down(): void
    {
        Schema::table('embu_champions', function (Blueprint $table) {
            $table->unique(['match_number_id', 'registration_id']);
        });
    }
};
