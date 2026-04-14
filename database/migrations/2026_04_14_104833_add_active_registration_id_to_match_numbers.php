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
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->foreignId('active_registration_id')->nullable()->constrained('registrations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_numbers', function (Blueprint $table) {
            $table->dropColumn('active_registration_id');
        });
    }
};
