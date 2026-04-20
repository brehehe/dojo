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
        Schema::table('courts', function (Blueprint $table) {
            $table->foreignId('active_match_id')->nullable()->constrained('match_numbers')->nullOnDelete();
            $table->foreignId('active_registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->string('active_bracket_node')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropForeign(['active_match_id']);
            $table->dropForeign(['active_registration_id']);
            $table->dropColumn(['active_match_id', 'active_registration_id', 'active_bracket_node']);
        });
    }
};
