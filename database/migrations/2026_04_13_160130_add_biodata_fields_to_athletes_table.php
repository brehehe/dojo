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
            $table->string('birth_place')->nullable()->after('gender');
            $table->string('blood_type', 5)->nullable()->after('birth_place');
            $table->text('address')->nullable()->after('nik');
            $table->string('phone')->nullable()->after('address');
            $table->string('photo_path')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'blood_type', 'address', 'phone', 'photo_path']);
        });
    }
};
