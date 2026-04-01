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
        Schema::create('contingents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('leader_name');
            $table->string('leader_phone');
            $table->string('leader_email')->nullable();
            $table->text('address')->nullable();
            $table->integer('total_cost')->default(0);
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->string('referral_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contingents');
    }
};
