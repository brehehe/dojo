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
        Schema::create('schedule_paniteras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rundown_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_time_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role_type'); // 'panitera' or 'koordinator'
            $table->unsignedTinyInteger('slot_index'); // 1 for Koordinator, 1 to 4 for Panitera
            $table->timestamps();

            // Enforce unique user per shift (rundown + session) to prevent double-booking
            // But we also need to enforce unique role & slot per court & shift
            $table->unique(['rundown_id', 'session_time_id', 'court_id', 'role_type', 'slot_index'], 'unique_schedule_panitera_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_paniteras');
    }
};
