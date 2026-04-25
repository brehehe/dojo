<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('techniques', function (Blueprint $table) {
            $table->foreignId('kyu_level_id')->nullable()->after('name')->constrained('kyu_levels')->nullOnDelete();
            $table->string('description')->nullable()->after('kyu_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('techniques', function (Blueprint $table) {
            $table->dropForeign(['kyu_level_id']);
            $table->dropColumn(['kyu_level_id', 'description']);
        });
    }
};
